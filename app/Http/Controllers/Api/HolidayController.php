<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Fetch all holidays.
     */
    public function index()
    {
        $holidays = Holiday::orderBy('date', 'asc')->get();
        return response()->json($holidays);
    }

    /**
     * Add a new holiday.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:holidays,date',
            'description' => 'required|string',
        ]);

        $holiday = Holiday::create($request->all());

        return response()->json($holiday, 201);
    }
}
