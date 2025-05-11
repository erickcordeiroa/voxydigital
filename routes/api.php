<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::resource('/leads', LeadController::class);
Route::resource('/services', ServiceController::class);