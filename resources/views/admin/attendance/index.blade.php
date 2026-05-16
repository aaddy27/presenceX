@extends('layouts.admin')

@section('title', 'Attendance Logs')
@section('page_title', 'Daily Logs')

@section('content')
<!-- Filter Header -->
<div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm mb-8">
    <form action="{{ route('admin.attendance.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-6">
        <div class="flex-1 w-full">
            <label class="block text-sm font-bold text-slate-500 mb-2 ml-1">Filter by Date</label>
            <div class="relative">
                <i class="fas fa-calendar absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="date" name="date" value="{{ $date }}" 
                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none font-bold text-slate-700">
            </div>
        </div>
        <button type="submit" class="w-full md:w-auto px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5">
            Apply Filter
        </button>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Employee</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Punch In</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Punch Out</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Work Hours</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($logs as $log)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div>
                                <div class="font-bold text-slate-800">{{ $log->staff->full_name }}</div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $log->staff->staff_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center font-bold text-slate-700">{{ $log->punch_in ?? '--:--' }}</td>
                    <td class="px-8 py-5 text-center font-bold text-slate-700">{{ $log->punch_out ?? '--:--' }}</td>
                    <td class="px-8 py-5 text-center">
                        <span class="inline-flex items-center gap-1.5 text-sm font-bold text-indigo-600">
                            <i class="fas fa-stopwatch text-[10px]"></i> {{ $log->productive_hours }}h
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        @php
                            $statusClasses = [
                                'Present' => 'bg-emerald-50 text-emerald-600',
                                'Late In' => 'bg-amber-50 text-amber-600',
                                'Half Day' => 'bg-orange-50 text-orange-600',
                                'Absent' => 'bg-rose-50 text-rose-600',
                                'Early Out' => 'bg-blue-50 text-blue-600',
                                'Holiday' => 'bg-slate-50 text-slate-500'
                            ];
                        @endphp
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusClasses[$log->status] ?? 'bg-slate-100' }}">
                            {{ $log->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="p-8 border-t border-slate-50">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
