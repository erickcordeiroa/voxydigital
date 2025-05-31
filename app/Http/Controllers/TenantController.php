<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tenant\TenantRequest;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TenantController extends Controller
{
    public function store(TenantRequest $request) {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $tenant = Tenant::create([
                'name' => $data['name'],
                'domain' => Str::slug($data['name']),
                'document' => $data['document'],
                'whatsapp' => $data['whatsapp'],
                'status' => false,
                'dt_expiration' => Carbon::now()->addDays(15),
            ]);

            $user = User::create([
                'name' => $data['responsible'],
                'email' => $data['email'],
                'password' => Hash::make($data['document']),
                'tenant_id' => $tenant->id,
                'whatsapp' => $data['whatsapp'],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao cadastrar: ' . $e->getMessage()]);
        }
    }

    public function show()
    {
        return Inertia::render('settings/Appearance', [
            'tenant' => app('tenant')
        ]);
    }

    public function update(Request $request)
    {
        try {
            $tenant = Tenant::find(app('tenant_id'));
            $data = $request->all();

            // Processar logo
            if ($request->has('logo')) {
                if ($request->logo === null) {
                    unset($data['logo']);
                }

                if ($request->hasFile('logo')) {
                    if ($tenant->logo) {
                        $this->deleteImage($tenant->logo);
                    }
                    $data['logo'] = $this->uploadImage($request->file('logo'), 'tenants');
                }
            }

            // Processar cover
            if ($request->has('cover')) {
                if ($request->cover === null) {
                    unset($data['cover']);
                }

                if ($request->hasFile('cover')) {
                    if ($tenant->cover) {
                        $this->deleteImage($tenant->cover);
                    }
                    $data['cover'] = $this->uploadImage($request->file('cover'), 'tenants/cover');
                }
            }

            $tenant->update($data);

            return redirect()->route('appearance')
                ->with('success', 'Empresa atualizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('appearance')
                ->with('error', 'Erro ao atualizar a empresa: ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function uploadImage($image, $path)
    {
        // Salva a imagem no diretório 'products' e retorna o caminho
        return $image->store($path, 'public');
    }

    protected function deleteImage($imagePath)
    {
        // Remove a imagem do armazenamento
        Storage::disk('public')->delete($imagePath);
    }
}
