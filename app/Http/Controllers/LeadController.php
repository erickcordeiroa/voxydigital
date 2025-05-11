<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request) {

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'service' => 'required|exists:services,id',
            'message' => 'nullable|string',
        ]);

        $lead = Lead::create($validated);

        return response()->json(['success' => true]);
    }
}
