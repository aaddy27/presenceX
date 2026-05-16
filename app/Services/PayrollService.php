<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\Holiday;
use App\Models\StaffDetail;
use Carbon\Carbon;

class PayrollService
{
    /**
     * Generate payroll for a staff member for a specific month.
     */
    public function generatePayroll(int $staffId, string $month): array
    {
        $staff = StaffDetail::findOrFail($staffId);
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        $perDaySalary = ($staff->basic_salary + $staff->monthly_allowances) / $daysInMonth;

        $logs = AttendanceLog::where('staff_id', $staffId)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $holidaysCount = Holiday::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])->count();

        $stats = [
            'present' => $logs->whereIn('status', ['Present', 'Late In', 'Early Out'])->count(),
            'late_in' => $logs->where('status', 'Late In')->count(),
            'half_day' => $logs->where('status', 'Half Day')->count(),
            'absent' => $logs->where('status', 'Absent')->count(),
            'holidays' => $holidaysCount,
        ];

        // Deductions: 3 Late Ins = 1 Half Day deduction (example logic)
        $lateInDeductions = floor($stats['late_in'] / 3) * 0.5; // Half day salary deduction
        
        $payableDays = $stats['present'] + $stats['holidays'] - ($stats['half_day'] * 0.5) - $lateInDeductions;
        $finalSalary = $payableDays * $perDaySalary;

        return [
            'staff_name' => $staff->full_name,
            'month' => $month,
            'basic_salary' => $staff->basic_salary,
            'allowances' => $staff->monthly_allowances,
            'per_day_salary' => round($perDaySalary, 2),
            'stats' => $stats,
            'deductions' => [
                'late_in_penalty_days' => $lateInDeductions,
                'half_day_penalty_days' => $stats['half_day'] * 0.5,
            ],
            'payable_days' => $payableDays,
            'final_payable_salary' => round($finalSalary, 2)
        ];
    }
}
