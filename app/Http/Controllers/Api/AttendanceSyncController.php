<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
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
}
