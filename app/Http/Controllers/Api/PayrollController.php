<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Generate payroll for a staff member.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff_details,id',
            'month' => 'required|date_format:Y-m',
        ]);

        $payroll = $this->payrollService->generatePayroll($request->staff_id, $request->month);

        return response()->json($payroll);
    }
}
