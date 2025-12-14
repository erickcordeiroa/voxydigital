<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(): Response
    {
        $tenant = app('tenant');
        
        return Inertia::render('public/checkout/Index', [
            'tenant' => $tenant,
        ]);
    }
}
