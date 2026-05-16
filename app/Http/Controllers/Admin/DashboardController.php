<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Shift;
use App\Models\StaffDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_staff' => StaffDetail::count(),
            'active_staff' => StaffDetail::where('status', 'Active')->count(),
            'total_shifts' => Shift::count(),
            'today_attendance' => AttendanceLog::where('date', Carbon::today())->count(),
            'recent_logs' => AttendanceLog::with('staff')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
