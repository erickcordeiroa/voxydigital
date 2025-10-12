<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Http\Requests\Banner\StoreBannerRequest;
use App\Http\Requests\Banner\UpdateBannerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    public function index(): Response
    {
        $banners = Banner::where('tenant_id', app('tenant_id'))
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('banners/Index', [
            'banners' => $banners,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('banners/Create');
    }

    public function store(StoreBannerRequest $request): RedirectResponse
    {

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
        }

        Banner::create([
            'tenant_id' => app('tenant_id'),
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'link_url' => $request->link_url,
            'link_text' => $request->link_text,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active'),
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ]);

        return redirect()->route('banners.index')
            ->with('success', 'Banner criado com sucesso!');
    }

    public function show(Banner $banner): Response
    {
        return Inertia::render('banners/Show', [
            'banner' => $banner,
        ]);
    }

    public function edit(Banner $banner): Response
    {
        return Inertia::render('banners/Edit', [
            'banner' => $banner,
        ]);
    }

    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link_url' => $request->link_url,
            'link_text' => $request->link_text,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->boolean('is_active'),
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ];

        if ($request->hasFile('image')) {
            // Deletar imagem antiga
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('banners.index')
            ->with('success', 'Banner atualizado com sucesso!');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        // Deletar imagem
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('banners.index')
            ->with('success', 'Banner removido com sucesso!');
    }
}