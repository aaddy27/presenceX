<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\StaffDetail;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceSyncController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Process bulk punch logs from Flutter app.
     */
    public function syncUp(Request $request)
    {
        $request->validate([
            'logs' => 'required|array',
            'logs.*.staff_code' => 'required|string',
            'logs.*.date' => 'required|date',
            'logs.*.punch_in' => 'nullable|string',
            'logs.*.punch_out' => 'nullable|string',
            'logs.*.breaks' => 'nullable|array',
        ]);

        $result = $this->attendanceService->syncAttendance($request->logs);

        return response()->json([
            'message' => 'Sync process completed',
            'processed_count' => $result['processed'],
            'errors' => $result['errors']
        ]);
    }

    /**
     * Record a single punch (In or Out).
     */
    public function punch(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff_details,id',
            'type' => 'required|in:in,out',
            'timestamp' => 'nullable|date',
        ]);

        $timestamp = isset($validated['timestamp']) ? Carbon::parse($validated['timestamp']) : now();
        $date = $timestamp->toDateString();
        $time = $timestamp->toTimeString();

        $staff = StaffDetail::with('shift')->findOrFail($validated['staff_id']);

        $attendance = AttendanceLog::updateOrCreate(
            ['staff_id' => $staff->id, 'date' => $date],
            [
                ($validated['type'] === 'in' ? 'punch_in' : 'punch_out') => $time
            ]
        );

        $this->attendanceService->updateAttendanceStats($attendance, $staff);

        return response()->json([
            'message' => 'Punch recorded successfully',
            'attendance' => $attendance
        ]);
    }
}
