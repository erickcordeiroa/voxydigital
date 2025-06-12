<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    private DashboardService $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index(): Response
    {
        $dashboardData = $this->dashboard->getDashboardData();

        return Inertia::render('Dashboard', $dashboardData);
    }
}