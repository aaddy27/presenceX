<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        
        $logs = AttendanceLog::with('staff')
            ->whereDate('date', $date)
            ->latest()
            ->paginate(20);

        return view('admin.attendance.index', compact('logs', 'date'));
    }
}
