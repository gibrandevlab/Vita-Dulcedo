@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-slate-900 mb-4">Halaman Segera Hadir</h1>
        <p class="text-lg text-slate-600 mb-8">Fitur ini sedang dalam tahap pengembangan.</p>
        <a href="{{ route('home') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
