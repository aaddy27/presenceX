@extends('layouts.admin')

@section('title', 'Edit Shift')
@section('page_title', 'Update Shift Configuration')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm h-fit">
        <div class="mb-8">
            <h3 class="text-xl font-bold text-slate-800">Edit Shift: {{ $shift->shift_name }}</h3>
            <p class="text-sm text-slate-500 font-medium">Modify work timings and grace periods.</p>
        </div>

        <form action="{{ route('admin.shifts.update', $shift->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Shift Name</label>
                <input type="text" name="shift_name" value="{{ $shift->shift_name }}"
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                    required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Start Time</label>
                    <input type="time" name="start_time" value="{{ $shift->start_time }}"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">End Time</label>
                    <input type="time" name="end_time" value="{{ $shift->end_time }}"
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Grace Time (Minutes)</label>
                <input type="number" name="grace_time_minutes" value="{{ $shift->grace_time_minutes }}"
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none">
            </div>
            
            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('admin.shifts.index') }}" class="px-6 py-4 text-slate-500 font-bold hover:text-slate-700">Cancel</a>
                <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5">
                    Update Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
