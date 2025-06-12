<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    private HomeService $service;

    public function __construct(HomeService $service)
    {
        $this->service = $service;
    }

    public function home(): Response
    {
        $data = $this->service->getHomeData();
        return Inertia::render('public/home/Index', $data);
    }
}