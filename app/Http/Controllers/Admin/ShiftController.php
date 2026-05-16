<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Shift::create($request->all());
        return redirect()->back()->with('success', 'Shift created successfully.');
    }

    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'shift_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $shift->update($request->all());
        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        if ($shift->staff()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete shift as it has assigned staff.');
        }

        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted successfully.');
    }
}
