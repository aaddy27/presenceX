<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\BreakLog;
use App\Models\StaffDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Sync bulk attendance logs from mobile client.
     */
    public function syncAttendance(array $logs): array
    {
        $processed = 0;
        $errors = [];

        foreach ($logs as $log) {
            try {
                DB::transaction(function () use ($log, &$processed) {
                    $staff = StaffDetail::with('shift')->where('staff_code', $log['staff_code'])->first();
                    
                    if (!$staff) {
                        throw new \Exception("Staff code {$log['staff_code']} not found.");
                    }

                    $date = $log['date'];
                    $punchIn = isset($log['punch_in']) ? Carbon::parse($log['punch_in']) : null;
                    $punchOut = isset($log['punch_out']) ? Carbon::parse($log['punch_out']) : null;

                    $attendance = AttendanceLog::updateOrCreate(
                        ['staff_id' => $staff->id, 'date' => $date],
                        [
                            'punch_in' => $punchIn ? $punchIn->toTimeString() : null,
                            'punch_out' => $punchOut ? $punchOut->toTimeString() : null,
                        ]
                    );

                    // Process Breaks if any
                    if (isset($log['breaks']) && is_array($log['breaks'])) {
                        foreach ($log['breaks'] as $break) {
                            BreakLog::updateOrCreate(
                                [
                                    'attendance_log_id' => $attendance->id,
                                    'break_start' => Carbon::parse($break['break_start'])->toTimeString()
                                ],
                                [
                                    'break_end' => isset($break['break_end']) ? Carbon::parse($break['break_end'])->toTimeString() : null,
                                    'break_type' => $break['break_type'] ?? 'Lunch'
                                ]
                            );
                        }
                    }

                    // Update Status and Productive Hours
                    $this->updateAttendanceStats($attendance, $staff);
                    $processed++;
                });
            } catch (\Exception $e) {
                $errors[] = "Error processing log for {$log['staff_code']} on {$log['date']}: " . $e->getMessage();
            }
        }

        return [
            'processed' => $processed,
            'errors' => $errors
        ];
    }

    /**
     * Calculate status and productive hours.
     */
    public function updateAttendanceStats(AttendanceLog $attendance, StaffDetail $staff): void
    {
        $shift = $staff->shift;
        $status = 'Present';
        
        if (!$attendance->punch_in) {
            $attendance->status = 'Absent';
            $attendance->save();
            return;
        }

        $punchIn = Carbon::parse($attendance->punch_in);
        $shiftStart = Carbon::parse($shift->start_time);
        $graceTime = $shift->grace_time_minutes;

        // Check Late In
        if ($punchIn->gt($shiftStart->addMinutes($graceTime))) {
            $status = 'Late In';
        }

        // Check Early Out
        if ($attendance->punch_out) {
            $punchOut = Carbon::parse($attendance->punch_out);
            $shiftEnd = Carbon::parse($shift->end_time);
            
            if ($punchOut->lt($shiftEnd)) {
                $status = ($status === 'Late In') ? 'Half Day' : 'Early Out';
            }

            // Calculate productive hours
            $totalMinutes = $punchOut->diffInMinutes($punchIn);
            
            // Deduct breaks
            $breakMinutes = 0;
            foreach ($attendance->breakLogs as $break) {
                if ($break->break_end) {
                    $breakMinutes += Carbon::parse($break->break_end)->diffInMinutes(Carbon::parse($break->break_start));
                }
            }

            $productiveMinutes = max(0, $totalMinutes - $breakMinutes);
            $attendance->productive_hours = round($productiveMinutes / 60, 2);

            // Check Half Day threshold
            if ($productiveMinutes < $shift->half_day_minutes_threshold) {
                $status = 'Half Day';
            }
        }

        $attendance->status = $status;
        $attendance->save();
    }
}
