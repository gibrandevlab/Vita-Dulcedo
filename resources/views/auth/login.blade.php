@extends('layouts.guest')

@section('content')
<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-emerald-50/40 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">

    <!-- Subtle Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-40"></div>
        <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] bg-amber-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-40"></div>
    </div>

    <!-- Main Card -->
    <div class="max-w-4xl w-full bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col md:flex-row relative z-10 border border-white">

        <!-- Ilustrasi Vektor (Kiri) -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-b from-emerald-50/50 to-white/10 p-12 items-center justify-center relative border-r border-slate-100/50">
            <!-- Pola titik-titik samar -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9IiNlMmU4ZjAiLz48L3N2Zz4=')] opacity-60 z-0"></div>
            <!-- Gambar Vektor -->
            <img src="{{ asset('assets/Kindergarten student-cuate.svg') }}" alt="Ilustrasi Belajar" class="w-full max-w-[340px] relative z-10 drop-shadow-2xl transform hover:-translate-y-2 hover:scale-105 transition-all duration-700 ease-out">
        </div>

        <!-- Area Form (Kanan) -->
        <div class="w-full md:w-1/2 px-8 py-16 sm:px-16 flex flex-col justify-center bg-white">
            <div class="mb-10">
                <span class="inline-block py-1.5 px-3 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold tracking-wider mb-4 border border-emerald-100">
                    PANTI ASUHAN VITA DULCEDO
                </span>
                <h2 class="text-3xl font-extrabold text-slate-800 mb-2 tracking-tight">Selamat Datang</h2>
                <p class="text-slate-500 font-medium text-sm">Masuk ke akun Anda untuk melanjutkan perjalanan kebaikan.</p>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm font-medium mb-2">
                {{ $errors->first('login') }}
            </div>
            @endif

            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-2">
                    <label for="login" class="block text-sm font-bold text-slate-700">Nama / No. Telepon</label>
                    <div class="relative group">
                        <input id="login" name="login" type="text" autocomplete="username" required value="{{ old('login') }}" class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 font-medium" placeholder="Ketik nama atau no. telepon">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                    <div class="relative group">
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 font-medium" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative flex items-center justify-center w-5 h-5 mr-3">
                            <input id="remember-me" name="remember-me" type="checkbox" class="peer appearance-none w-5 h-5 border-2 border-slate-300 rounded focus:ring-4 focus:ring-emerald-500/20 focus:outline-none checked:border-emerald-600 checked:bg-emerald-600 transition-all cursor-pointer">
                            <svg class="absolute w-3 h-3 text-white pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-800 transition-colors">Ingat Saya</span>
                    </label>
                    <a href="#" onclick="alert('Lupa password? Silakan hubungi Admin Panti untuk mendapatkan PIN Spesial 5-digit guna mereset akun Anda.')" class="text-sm font-bold text-emerald-600 hover:text-emerald-500 transition-colors">
                        Lupa Password?
                    </a>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl shadow-[0_8px_20px_-6px_rgba(5,150,105,0.4)] hover:shadow-[0_15px_25px_-6px_rgba(5,150,105,0.5)] hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-emerald-500/30">
                        Masuk ke Dasbor
                    </button>
                </div>
            </form>

            <div class="mt-10 text-center">
                <p class="text-sm font-medium text-slate-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-500 transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-emerald-500 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:origin-left">Daftar Sekarang</a>
                </p>
            </div>
        </div>

    </div>
</section>
@endsection
