<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json([
            "services" => Service::orderBy('nome')->get(['id', 'name'])
        ], 200);
    }
}
