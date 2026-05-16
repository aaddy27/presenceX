@extends('layouts.admin')

@section('title', 'Payroll Management')
@section('page_title', 'Payroll Statement Engine')

@section('content')
<!-- Filter Section -->
<div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm mb-6 no-print">
    <form action="{{ route('admin.payroll.index') }}" method="GET" class="flex flex-col xl:flex-row items-end gap-4">
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-slate-500 mb-1.5 ml-1 uppercase tracking-wider">Payroll Month</label>
            <input type="month" name="month" value="{{ $month }}" 
                class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none font-bold text-slate-700">
        </div>
        <div class="flex-1 w-full">
            <label class="block text-xs font-bold text-slate-500 mb-1.5 ml-1 uppercase tracking-wider">Select Employee</label>
            <select name="staff_id" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none font-bold text-slate-700 appearance-none">
                <option value="">-- Choose Staff Member --</option>
                @foreach($staff as $member)
                    <option value="{{ $member->id }}" {{ request('staff_id') == $member->id ? 'selected' : '' }}>{{ $member->full_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="w-full xl:w-auto px-10 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5 whitespace-nowrap">
            Generate Statement
        </button>
    </form>
</div>

@forelse($payrolls as $payroll)
    <div class="max-w-4xl mx-auto mb-8">
        <!-- Action Buttons (Hidden on Print) -->
        <div class="flex justify-end gap-3 mb-4 no-print">
            <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-print"></i> Print
            </button>
            <button class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-download"></i> PDF
            </button>
        </div>

        <!-- Single Page Compact Payslip -->
        <div class="bg-white p-10 rounded-[2rem] shadow-xl border border-slate-100 print:shadow-none print:border-none print:p-0 print:m-0 overflow-hidden">
            <!-- Company Header -->
            <div class="flex justify-between items-start border-b-2 border-indigo-600 pb-6 mb-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter">PRESENCE<span class="text-indigo-600">X</span></h1>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Biometric Attendance Systems</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-black text-slate-800 uppercase leading-none mb-1">Salary Slip</h2>
                    <p class="text-indigo-600 font-black text-sm uppercase">{{ Carbon\Carbon::parse($payroll['month'])->format('F Y') }}</p>
                </div>
            </div>

            <!-- Employee Details Row -->
            <div class="grid grid-cols-4 gap-4 mb-8 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Name</p>
                    <p class="text-xs font-bold text-slate-800 truncate">{{ $payroll['staff_name'] }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Emp ID</p>
                    <p class="text-xs font-bold text-slate-800">PX-{{ str_pad($payroll['stats']['present'], 3, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Period</p>
                    <p class="text-xs font-bold text-slate-800">{{ Carbon\Carbon::parse($payroll['month'])->format('M Y') }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Pay Days</p>
                    <p class="text-xs font-black text-indigo-600">{{ $payroll['payable_days'] }} Days</p>
                </div>
            </div>

            <!-- Earnings & Deductions -->
            <div class="grid grid-cols-2 gap-8 mb-8">
                <!-- Earnings Column -->
                <div class="space-y-3">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mb-4">Earnings Breakdown</h3>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-500">Basic Salary</span>
                        <span class="font-black text-slate-800">${{ number_format($payroll['basic_salary'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-500">Allowances</span>
                        <span class="font-black text-slate-800">${{ number_format($payroll['allowances'], 2) }}</span>
                    </div>
                    <div class="pt-3 border-t border-slate-50 flex justify-between text-emerald-600 font-black">
                        <span class="text-[10px] uppercase tracking-widest">Gross</span>
                        <span class="text-sm">${{ number_format($payroll['basic_salary'] + $payroll['allowances'], 2) }}</span>
                    </div>
                </div>

                <!-- Deductions Column -->
                <div class="space-y-3">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2 mb-4">Deductions</h3>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-500">Late Arrivals</span>
                        <span class="font-black text-rose-500">-${{ number_format($payroll['deductions']['late_in_penalty_days'] * $payroll['per_day_salary'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-500">Half Days</span>
                        <span class="font-black text-rose-500">-${{ number_format($payroll['deductions']['half_day_penalty_days'] * $payroll['per_day_salary'], 2) }}</span>
                    </div>
                    <div class="pt-3 border-t border-slate-50 flex justify-between text-rose-600 font-black">
                        <span class="text-[10px] uppercase tracking-widest">Total</span>
                        <span class="text-sm">-${{ number_format(($payroll['deductions']['late_in_penalty_days'] + $payroll['deductions']['half_day_penalty_days']) * $payroll['per_day_salary'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Net Pay Box -->
            <div class="bg-slate-900 p-6 rounded-2xl text-white flex justify-between items-center shadow-lg mb-8">
                <div>
                    <p class="text-[8px] font-black uppercase tracking-[0.4em] opacity-50 mb-1">Net Salary Payable</p>
                    <h2 class="text-3xl font-black tracking-tight text-indigo-400">${{ number_format($payroll['final_payable_salary'], 2) }}</h2>
                </div>
                <div class="text-right">
                    <div class="inline-block px-3 py-1 bg-white/10 rounded-full text-[9px] font-bold uppercase tracking-widest mb-1">Status: Paid</div>
                    <p class="text-[10px] opacity-60">Verified Statement</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-end pt-6 border-t border-slate-100">
                <div class="space-y-2">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Attendance Logs</p>
                    <div class="flex gap-2">
                        <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md text-[9px] font-bold">P: {{ $payroll['stats']['present'] }}</span>
                        <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-md text-[9px] font-bold">L: {{ $payroll['stats']['late_in'] }}</span>
                        <span class="bg-rose-50 text-rose-600 px-2 py-0.5 rounded-md text-[9px] font-bold">A: {{ $payroll['stats']['absent'] }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-40 border-b border-slate-200 mb-1"></div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Manager Signature</p>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="flex flex-col items-center justify-center py-20 bg-white rounded-[3rem] border border-slate-100 border-dashed">
        <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-400 text-3xl mb-4">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <h3 class="text-xl font-black text-slate-800">Generate Statement</h3>
        <p class="text-slate-500 text-sm">Select employee above.</p>
    </div>
@endforelse

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; margin: 0 !important; padding: 0 !important; }
        main { padding: 0 !important; margin: 0 !important; }
        aside { display: none !important; }
        header { display: none !important; }
        .md\:pl-64 { padding-left: 0 !important; }
        .max-w-4xl { max-width: 100% !important; margin: 0 !important; }
        .rounded-\[2rem\] { border-radius: 0 !important; }
    }
</style>
@endsection
