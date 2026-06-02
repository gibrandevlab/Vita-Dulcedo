@extends('layouts.guest')

@section('content')
<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-emerald-50/40 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">

    <!-- Subtle Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-40"></div>
        <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] bg-amber-100 rounded-full mix-blend-multiply filter blur-[80px] opacity-40"></div>
    </div>

    <!-- Main Card -->
    <div class="max-w-md w-full bg-white/90 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] overflow-hidden relative z-10 border border-white p-8 sm:p-12">
        <div class="mb-8 text-center">
            <span class="inline-block py-1.5 px-3 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold tracking-wider mb-4 border border-emerald-100">
                PANTI ASUHAN VITA DULCEDO
            </span>
            <h2 class="text-3xl font-extrabold text-slate-800 mb-2 tracking-tight">Daftar Akun</h2>
            <p class="text-slate-500 font-medium text-sm">Bergabunglah untuk berbagi kebahagiaan dengan mereka.</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm font-medium mb-6">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="space-y-5" action="{{ route('register') }}" method="POST">
            @csrf
            
            {{-- Nama Lengkap --}}
            <div class="space-y-2">
                <label for="name" class="block text-sm font-bold text-slate-700">Nama Lengkap</label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}" class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 font-medium" placeholder="Nama Lengkap Anda">
            </div>

            {{-- Username / No. Telepon --}}
            <div class="space-y-2">
                <label for="login" class="block text-sm font-bold text-slate-700">Username / No. Telepon</label>
                <input id="login" name="login" type="text" required autocomplete="username" value="{{ old('login') }}" class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 font-medium" placeholder="Username atau 08...">
                <p class="text-xs text-slate-400 font-medium">Jika menggunakan nomor telepon, wajib diawali dengan 08...</p>
            </div>

            {{-- Password --}}
            <div class="space-y-2">
                <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all duration-300 font-medium" placeholder="••••••••">
            </div>

            {{-- Info PIN Otomatis --}}
            <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs text-emerald-800 leading-relaxed font-medium">
                    Sistem akan men-generate **PIN Pemulihan 5-digit** secara otomatis setelah Anda mendaftar. Simpan PIN tersebut baik-baik.
                </p>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 px-6 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl shadow-[0_8px_20px_-6px_rgba(5,150,105,0.4)] hover:shadow-[0_15px_25px_-6px_rgba(5,150,105,0.5)] hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-emerald-500/30">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm font-medium text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-500 transition-colors relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-emerald-500 after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:origin-left">Masuk</a>
            </p>
        </div>
    </div>
</section>
@endsection
