@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 transition-all hover:shadow-md hover:border-indigo-100">
        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Staff</p>
            <h2 class="text-3xl font-bold text-slate-900">{{ $stats['total_staff'] }}</h2>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 transition-all hover:shadow-md hover:border-emerald-100">
        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl">
            <i class="fas fa-user-check"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Active Staff</p>
            <h2 class="text-3xl font-bold text-slate-900">{{ $stats['active_staff'] }}</h2>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 transition-all hover:shadow-md hover:border-amber-100">
        <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl">
            <i class="fas fa-business-time"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Shifts</p>
            <h2 class="text-3xl font-bold text-slate-900">{{ $stats['total_shifts'] }}</h2>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 transition-all hover:shadow-md hover:border-indigo-100">
        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl">
            <i class="fas fa-clipboard-user"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Today's Presence</p>
            <h2 class="text-3xl font-bold text-slate-900">{{ $stats['today_attendance'] }}</h2>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Activity -->
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Recent Attendance Logs</h3>
            <a href="{{ route('admin.attendance.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">View All activity</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Staff</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Punch In</th>
                        <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($stats['recent_logs'] as $log)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-4">
                            <div class="font-bold text-slate-800">{{ $log->staff->full_name }}</div>
                            <div class="text-xs text-slate-500 font-medium">{{ $log->staff->staff_code }}</div>
                        </td>
                        <td class="px-8 py-4 text-sm text-slate-600 font-medium">{{ $log->date->format('d M, Y') }}</td>
                        <td class="px-8 py-4 text-sm text-slate-600 font-bold">{{ $log->punch_in ?? '--:--' }}</td>
                        <td class="px-8 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $log->status === 'Present' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $log->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-10 text-center text-slate-400 font-medium italic">No recent activity detected</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Quick Actions</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.staff.create') }}" class="group flex items-center justify-between p-4 bg-slate-50 hover:bg-indigo-600 rounded-2xl transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 group-hover:scale-90 transition-transform">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-white transition-colors">Add Employee</span>
                </div>
                <i class="fas fa-chevron-right text-slate-300 group-hover:text-white/50 transition-colors"></i>
            </a>
            
            <a href="{{ route('admin.shifts.index') }}" class="group flex items-center justify-between p-4 bg-slate-50 hover:bg-amber-500 rounded-2xl transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-amber-500 group-hover:scale-90 transition-transform">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-white transition-colors">Manage Shifts</span>
                </div>
                <i class="fas fa-chevron-right text-slate-300 group-hover:text-white/50 transition-colors"></i>
            </a>

            <a href="{{ route('admin.payroll.index') }}" class="group flex items-center justify-between p-4 bg-slate-50 hover:bg-emerald-600 rounded-2xl transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-90 transition-transform">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-white transition-colors">Run Payroll</span>
                </div>
                <i class="fas fa-chevron-right text-slate-300 group-hover:text-white/50 transition-colors"></i>
            </a>

            <a href="{{ route('admin.holidays.index') }}" class="group flex items-center justify-between p-4 bg-slate-50 hover:bg-indigo-600 rounded-2xl transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 group-hover:scale-90 transition-transform">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <span class="font-bold text-slate-700 group-hover:text-white transition-colors">Set Holidays</span>
                </div>
                <i class="fas fa-chevron-right text-slate-300 group-hover:text-white/50 transition-colors"></i>
            </a>

            <!-- Tailwind Indicator -->
            <div class="mt-6 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center gap-3">
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                </div>
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-widest">Tailwind v4 Active</span>
            </div>
        </div>
    </div>
</div>
@endsection
