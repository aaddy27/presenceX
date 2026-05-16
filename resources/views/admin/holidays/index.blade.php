@extends('layouts.admin')

@section('title', 'Holiday Calendar')
@section('page_title', 'Holidays')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add Holiday Form -->
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm h-fit">
        <div class="mb-8">
            <h3 class="text-xl font-bold text-slate-800">Add New Holiday</h3>
            <p class="text-sm text-slate-500 font-medium">Mark important dates on the calendar</p>
        </div>

        <form action="{{ route('admin.holidays.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Holiday Date</label>
                <div class="relative">
                    <i class="fas fa-calendar-day absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="date" name="date" 
                        class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none font-bold text-slate-700" 
                        required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Description</label>
                <input type="text" name="description" 
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-indigo-600 transition-all outline-none" 
                    placeholder="e.g. Independence Day" required>
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5">
                Register Holiday
            </button>
        </form>
    </div>

    <!-- Holidays List -->
    <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-800">Holiday Calendar</h3>
            <div class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold uppercase tracking-widest">
                Upcoming Events
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Event Description</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($holidays as $holiday)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex flex-col items-center justify-center text-indigo-600 shrink-0">
                                    <span class="text-[10px] font-black uppercase leading-none">{{ $holiday->date->format('M') }}</span>
                                    <span class="text-sm font-black leading-none">{{ $holiday->date->format('d') }}</span>
                                </div>
                                <span class="font-bold text-slate-800">{{ $holiday->date->format('Y') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 font-semibold text-slate-600">{{ $holiday->description }}</td>
                        <td class="px-8 py-5 text-center">
                            <form action="{{ route('admin.holidays.destroy', $holiday->id) }}" method="POST" onsubmit="return confirm('Delete this holiday?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg hover:bg-rose-50 text-slate-300 hover:text-rose-500 transition-all flex items-center justify-center">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
