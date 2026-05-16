<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\StaffDetail;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = StaffDetail::with('shift')->latest()->paginate(10);
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $shifts = Shift::all();
        return view('admin.staff.create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_code' => 'required|unique:staff_details',
            'full_name' => 'required',
            'phone' => 'required',
            'designation' => 'required',
            'shift_id' => 'required|exists:shifts,id',
            'basic_salary' => 'required|numeric',
        ]);

        StaffDetail::create($request->all() + ['face_embedding' => '[]']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member added successfully.');
    }

    public function edit(StaffDetail $staff)
    {
        $shifts = Shift::all();
        return view('admin.staff.edit', compact('staff', 'shifts'));
    }

    public function update(Request $request, StaffDetail $staff)
    {
        $request->validate([
            'staff_code' => 'required|unique:staff_details,staff_code,' . $staff->id,
            'full_name' => 'required',
            'phone' => 'required',
            'designation' => 'required',
            'shift_id' => 'required|exists:shifts,id',
            'basic_salary' => 'required|numeric',
            'status' => 'required|in:Active,Inactive',
        ]);

        $staff->update($request->all());

        return redirect()->route('admin.staff.index')->with('success', 'Staff details updated successfully.');
    }

    public function destroy(StaffDetail $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
