@extends('layouts.admin')

@section('title', 'Add New Staff')
@section('page_title', 'Onboard New Employee')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-10 border-b border-slate-50 bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-800">Employee Details</h3>
            <p class="text-sm text-slate-500 font-medium">Please fill in all the required information for the new staff member.</p>
        </div>

        <form action="{{ route('admin.staff.store') }}" method="POST" class="p-10">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Staff Code (Unique)</label>
                    <input type="text" name="staff_code" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        placeholder="e.g. STF001" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Full Name</label>
                    <input type="text" name="full_name" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        placeholder="John Doe" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Phone Number</label>
                    <input type="text" name="phone" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        placeholder="+1 234 567 890" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Designation</label>
                    <input type="text" name="designation" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        placeholder="Software Engineer" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Joining Date</label>
                    <input type="date" name="joining_date" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Assigned Shift</label>
                    <select name="shift_id" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none appearance-none" 
                        required>
                        <option value="">-- Select Shift --</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->shift_name }} ({{ $shift->start_time }} - {{ $shift->end_time }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Basic Salary</label>
                    <input type="number" name="basic_salary" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        placeholder="50000" step="0.01" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Monthly Allowances</label>
                    <input type="number" name="monthly_allowances" value="0" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        step="0.01">
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-50">
                <a href="{{ route('admin.staff.index') }}" class="px-8 py-4 text-slate-500 font-bold hover:text-slate-700 transition-colors">Cancel</a>
                <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i> Save Employee
                </button>
            </div>
        </form>
    </div>

    <div class="mt-8 p-6 bg-indigo-50/50 border border-indigo-100 rounded-3xl flex gap-6 items-start">
        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
            <i class="fas fa-info-circle text-xl"></i>
        </div>
        <div>
            <h4 class="font-bold text-indigo-900 mb-1">Face Recognition Note</h4>
            <p class="text-sm text-indigo-700/80 leading-relaxed font-medium">
                Once saved, this employee will be available on the mobile application. The facial vector (embedding) will be captured and linked automatically during their first registration on the tablet device.
            </p>
        </div>
    </div>
</div>
@endsection
