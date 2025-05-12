<?php

namespace App\Middleware;

use App\Exceptions\Auth\TenantNotFoundException;
use App\Models\Tenant;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $domain = explode('.', $host)[0];

        $tenant = Tenant::where('domain', $domain)->first();
        if (!$tenant) {
            throw new Exception('Nenhuma empresa encontrada');
        }

        app()->instance('tenant_id', $tenant->id);
        app()->instance('tenant', $tenant);

        return $next($request);
    }
}