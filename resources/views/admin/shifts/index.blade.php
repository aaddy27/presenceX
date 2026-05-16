@extends('layouts.admin')

@section('title', 'Shift Management')
@section('page_title', 'Work Shifts')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <!-- Add Shift Form -->
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm h-fit">
        <div class="mb-8">
            <h3 class="text-xl font-bold text-slate-800">Add New Shift</h3>
            <p class="text-sm text-slate-500 font-medium">Define new timing rules for your staff</p>
        </div>

        <form action="{{ route('admin.shifts.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Shift Name</label>
                <input type="text" name="shift_name" 
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                    placeholder="e.g. Morning Shift" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Start Time</label>
                    <input type="time" name="start_time" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">End Time</label>
                    <input type="time" name="end_time" 
                        class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                        required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Grace Time (Minutes)</label>
                <input type="number" name="grace_time_minutes" value="15" 
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none">
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5">
                Save Shift Configuration
            </button>
        </form>
    </div>

    <!-- Shifts List -->
    <div class="xl:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50">
            <h3 class="text-xl font-bold text-slate-800">Existing Shifts</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Shift Name</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Timings</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Grace Period</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($shifts as $shift)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5 font-bold text-slate-800">{{ $shift->shift_name }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold">{{ $shift->start_time }}</span>
                                <i class="fas fa-arrow-right text-[10px] text-slate-300"></i>
                                <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold">{{ $shift->end_time }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="text-sm font-bold text-slate-600">{{ $shift->grace_time_minutes }} mins</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.shifts.edit', $shift->id) }}" class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-50 flex items-center justify-center transition-all">
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
    </div>
</div>
@endsection
