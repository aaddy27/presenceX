<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PresenceX Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full text-slate-900">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 hidden md:flex flex-col">
        <div class="p-8 flex items-center gap-3 text-2xl font-bold text-indigo-600">
            <i class="fas fa-fingerprint"></i>
            <span>PresenceX</span>
        </div>
        
        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                <i class="fas fa-th-large w-5"></i> Dashboard
            </a>
            <a href="{{ route('admin.staff.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.staff.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                <i class="fas fa-users w-5"></i> Staff Directory
            </a>
            <a href="{{ route('admin.shifts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.shifts.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                <i class="fas fa-clock w-5"></i> Shifts
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.attendance.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                <i class="fas fa-calendar-check w-5"></i> Attendance
            </a>
            <a href="{{ route('admin.holidays.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.holidays.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                <i class="fas fa-umbrella-beach w-5"></i> Holidays
            </a>
            <a href="{{ route('admin.payroll.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.payroll.*') ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-600' }}">
                <i class="fas fa-money-check-alt w-5"></i> Payroll
            </a>
        </nav>

        <div class="p-4 border-t border-slate-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all font-semibold">
                    <i class="fas fa-sign-out-alt w-5"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="md:pl-64 flex flex-col min-h-screen">
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 px-8 flex items-center justify-between">
            <h1 class="text-xl font-bold text-slate-800">@yield('page_title')</h1>
            
            <div class="flex items-center gap-4 bg-slate-50 px-4 py-2 rounded-full border border-slate-200">
                <span class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-xs"></i>
                </div>
            </div>
        </header>

        <main class="p-8 flex-1">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                    <i class="fas fa-check-circle"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
