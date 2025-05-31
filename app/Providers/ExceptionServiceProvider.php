<?php

use Illuminate\Foundation\Configuration\Exceptions;

return function (Exceptions $exceptions) {
    $exceptions->render(function (\App\Exceptions\TenantNotFoundException $e, $request) {
        return redirect()->route('welcome')->withErrors([
            'tenant' => 'Nenhuma empresa encontrada.',
        ]);
    });

    $exceptions->render(function (\App\Exceptions\TenantInactiveException $e, $request) {
        return redirect()->route('welcome')->withErrors([
            'tenant' => 'A loja que esta tentando acessar está inativa.',
        ]);
    });
};