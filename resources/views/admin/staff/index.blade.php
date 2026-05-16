@extends('layouts.admin')

@section('title', 'Staff Directory')
@section('page_title', 'Employee Management')

@section('content')
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Staff Directory</h3>
            <p class="text-sm text-slate-500 font-medium">Manage and monitor all your organization members</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-100 hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Add New Staff
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Employee</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Designation</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Shift</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Salary</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($staff as $member)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center font-bold">
                                {{ strtoupper(substr($member->full_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-800">{{ $member->full_name }}</div>
                                <div class="text-xs text-slate-400 font-bold uppercase tracking-tighter">{{ $member->staff_code }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-sm font-semibold text-slate-600">{{ $member->designation }}</td>
                    <td class="px-8 py-5">
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-bold">
                            <i class="fas fa-clock text-[10px]"></i> {{ $member->shift->shift_name }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm font-bold text-slate-800">${{ number_format($member->basic_salary, 2) }}</td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $member->status === 'Active' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                            {{ $member->status }}
                        </span>
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.staff.edit', $member->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 transition-all">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-50 transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($staff->hasPages())
    <div class="p-8 border-t border-slate-50">
        {{ $staff->links() }}
    </div>
    @endif
</div>
@endsection
