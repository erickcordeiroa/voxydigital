<?php

namespace App\Middleware;

use App\Exceptions\TenantNotFoundException;
use App\Exceptions\TenantInactiveException;
use App\Models\Tenant;
use Closure;
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
            throw new TenantNotFoundException('Nenhuma empresa encontrada');
        }

        if (!$tenant->status) {
            throw new TenantInactiveException('Empresa inativa');
        }

        $user = $request->user();
        if ($user && $user->tenant_id !== $tenant->id) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'auth' => 'Acesso negado ao subdomínio.',
            ]);
        }

        app()->instance('tenant_id', $tenant->id);
        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
