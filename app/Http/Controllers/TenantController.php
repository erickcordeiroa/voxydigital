<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tenant\TenantRequest;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TenantController extends Controller
{
    private TenantService $service;

    public function __construct(TenantService $service)
    {
        $this->service = $service;
    }

    public function store(TenantRequest $request)
    {
        return $this->service->createTenant($request);
    }

    public function show(): Response
    {
        return Inertia::render('settings/Appearance', [
            'tenant' => app('tenant')
        ]);
    }

    public function update(Request $request)
    {
        return $this->service->updateTenant($request);
    }
}