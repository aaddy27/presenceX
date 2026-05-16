@extends('layouts.admin')

@section('title', 'Edit Staff')
@section('page_title', 'Update Employee Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-10 border-b border-slate-50 bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-800">Edit: {{ $staff->full_name }}</h3>
            <p class="text-sm text-slate-500 font-medium">Update the employee information and employment status.</p>
        </div>

        <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" class="p-10">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Staff Code</label>
                    <input type="text" name="staff_code" value="{{ $staff->staff_code }}"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Full Name</label>
                    <input type="text" name="full_name" value="{{ $staff->full_name }}"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ $staff->phone }}"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Designation</label>
                    <input type="text" name="designation" value="{{ $staff->designation }}"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Assigned Shift</label>
                    <select name="shift_id" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none appearance-none" 
                        required>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ $staff->shift_id == $shift->id ? 'selected' : '' }}>{{ $shift->shift_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Status</label>
                    <select name="status" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none appearance-none" 
                        required>
                        <option value="Active" {{ $staff->status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ $staff->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Basic Salary</label>
                    <input type="number" name="basic_salary" value="{{ $staff->basic_salary }}"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        step="0.01" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Monthly Allowances</label>
                    <input type="number" name="monthly_allowances" value="{{ $staff->monthly_allowances }}"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        step="0.01">
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-50">
                <a href="{{ route('admin.staff.index') }}" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-700 transition-colors">Cancel</a>
                <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5">
                    Update Staff Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
