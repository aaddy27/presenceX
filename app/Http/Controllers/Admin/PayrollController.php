<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffDetail;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $staff = StaffDetail::all();
        
        $payrolls = [];
        if ($request->has('staff_id')) {
            $payrolls[] = $this->payrollService->generatePayroll($request->staff_id, $month);
        }

        return view('admin.payroll.index', compact('staff', 'payrolls', 'month'));
    }
}
