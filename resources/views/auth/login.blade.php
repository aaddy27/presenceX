<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PresenceX Premium</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-slow { animation: float 9s ease-in-out infinite; }
    </style>
</head>
<body class="h-full overflow-hidden">
    <div class="flex h-full">
        <!-- Left Side: Branding & Advertisement (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-3/5 bg-slate-900 relative items-center justify-center overflow-hidden">
            <!-- Animated Background Blobs -->
            <div class="absolute top-0 -left-20 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
            <div class="absolute bottom-0 -right-20 w-96 h-96 bg-emerald-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float-slow"></div>
            <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>

            <!-- Content Area -->
            <div class="relative z-10 p-16 text-center max-w-2xl">
                <div class="mb-12 inline-block p-4 bg-white/5 rounded-[2.5rem] backdrop-blur-xl border border-white/10 shadow-2xl animate-float">
                    <img src="{{ asset('images/logo.png') }}" alt="PresenceX Logo" class="w-32 h-32 object-contain drop-shadow-2xl">
                </div>
                <h1 class="text-6xl font-black text-white tracking-tighter mb-6 leading-tight">
                    The Future of <span class="text-indigo-400">Biometric</span> Attendance.
                </h1>
                <p class="text-slate-400 text-xl font-medium leading-relaxed mb-12">
                    PresenceX provides seamless face recognition technology for modern organizations. Secure, offline-first, and highly scalable.
                </p>

                <!-- Features Cards -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-6 bg-white/5 border border-white/10 rounded-3xl text-left backdrop-blur-sm transition-all hover:bg-white/10">
                        <i class="fas fa-bolt text-indigo-400 text-2xl mb-4"></i>
                        <h4 class="text-white font-bold mb-2">Real-time Sync</h4>
                        <p class="text-slate-500 text-sm">Advanced local caching ensures your data is always safe.</p>
                    </div>
                    <div class="p-6 bg-white/5 border border-white/10 rounded-3xl text-left backdrop-blur-sm transition-all hover:bg-white/10">
                        <i class="fas fa-shield-halved text-emerald-400 text-2xl mb-4"></i>
                        <h4 class="text-white font-bold mb-2">AI Recognition</h4>
                        <p class="text-slate-500 text-sm">99.9% accuracy with our proprietary face vector engine.</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Branding -->
            <div class="absolute bottom-8 left-16 flex items-center gap-2 text-slate-500 text-xs font-bold uppercase tracking-widest">
                <span>Enterprise Edition</span>
                <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                <span>v4.0.0 Stable</span>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-2/5 flex flex-col items-center justify-center p-8 md:p-16 bg-slate-50 relative">
            <!-- Mobile Logo -->
            <div class="lg:hidden mb-12 flex flex-col items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 mb-4 drop-shadow-xl">
                <h2 class="text-2xl font-black text-slate-900 tracking-tighter">PresenceX</h2>
            </div>

            <div class="w-full max-w-md">
                <div class="mb-10">
                    <h3 class="text-4xl font-black text-slate-900 tracking-tighter mb-3">Login to Dashboard</h3>
                    <p class="text-slate-500 font-medium">Enter your administrative credentials to continue.</p>
                </div>

                @if($errors->any())
                    <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl text-sm font-bold flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                        <i class="fas fa-circle-exclamation text-lg"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 ml-1 uppercase tracking-widest">Administrator Email</label>
                        <div class="relative group">
                            <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                class="w-full pl-14 pr-5 py-5 bg-white border-2 border-slate-100 rounded-3xl focus:border-indigo-600 transition-all outline-none text-slate-900 font-bold shadow-sm" 
                                placeholder="admin@presencex.com" required autofocus>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 ml-1 uppercase tracking-widest">Security Password</label>
                        <div class="relative group">
                            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                            <input type="password" name="password" 
                                class="w-full pl-14 pr-5 py-5 bg-white border-2 border-slate-100 rounded-3xl focus:border-indigo-600 transition-all outline-none text-slate-900 font-bold shadow-sm" 
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="remember" class="peer appearance-none w-6 h-6 border-2 border-slate-200 rounded-lg checked:bg-indigo-600 checked:border-indigo-600 transition-all">
                                <i class="fas fa-check absolute inset-0 m-auto text-white text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900">Remember session</span>
                        </label>
                        <a href="#" class="text-sm font-black text-indigo-600 hover:text-indigo-800 transition-colors">Help?</a>
                    </div>

                    <button type="submit" 
                        class="w-full py-5 bg-slate-900 hover:bg-indigo-600 text-white font-black text-lg rounded-3xl shadow-xl shadow-slate-200 transition-all hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-3">
                        Enter Dashboard <i class="fas fa-arrow-right text-sm"></i>
                    </button>
                </form>

                <div class="mt-16 text-center">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mb-4">Powered by</p>
                    <div class="flex justify-center gap-6 opacity-30 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                        <i class="fab fa-laravel text-3xl"></i>
                        <i class="fab fa-php text-3xl"></i>
                        <i class="fab fa-js text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
