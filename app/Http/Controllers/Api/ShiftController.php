<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return response()->json([
            'shifts' => $shifts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
        ]);

        $shift = Shift::create($validated);
        return response()->json($shift, 201);
    }
}
