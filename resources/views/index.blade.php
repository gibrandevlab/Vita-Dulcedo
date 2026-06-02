@extends('layouts.app')

@section('content')
<!-- Section 1: Hero Section -->
<section class="relative bg-slate-900 text-white overflow-hidden">
    <div class="absolute inset-0">
        <!-- Placeholder warm background image with overlay -->
        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-60" alt="Anak-anak tersenyum">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/50 to-transparent"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 flex flex-col justify-center min-h-[80vh]">
        <div class="max-w-3xl">
            <span class="inline-block py-1 px-3 rounded-full bg-emerald-500/20 text-emerald-300 text-sm font-semibold tracking-wider mb-6 border border-emerald-500/30">
                #BersamaBerbagiKebaikan
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-6 leading-tight">
                Uluran Tangan Anda,<br>
                <span class="text-emerald-400">Masa Depan Bagi Mereka.</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl leading-relaxed">
                Selamat Datang di Panti Asuhan Vita Dulcedo, Harapan Indah, Bekasi. Bersama merajut harapan dan masa depan anak-anak nusantara, termasuk adik-adik kita dari Bomomani, Papua.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('donasi') }}" class="inline-flex justify-center items-center px-8 py-4 border border-transparent text-base font-bold rounded-full text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 focus:ring-offset-slate-900 transition-all shadow-lg hover:shadow-emerald-500/30">
                    Donasi Sekarang
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('kunjungan') }}" class="inline-flex justify-center items-center px-8 py-4 border-2 border-white/30 text-base font-bold rounded-full text-white hover:bg-white hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white focus:ring-offset-slate-900 transition-all backdrop-blur-sm">
                    Jadwalkan Kunjungan
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Section 2: Core Stats Counter -->
<section class="py-12 bg-white border-b border-slate-100 relative -mt-10 z-10 mx-4 sm:mx-6 lg:mx-8 rounded-2xl shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100">
            <div class="p-4">
                <p class="text-5xl font-black text-emerald-600 mb-2">{{ $stats['anak_asuh'] ?? '30+' }}</p>
                <p class="text-slate-500 font-medium tracking-wide uppercase text-sm">Anak Asuh</p>
            </div>
            <div class="p-4">
                <p class="text-5xl font-black text-emerald-600 mb-2">{{ $stats['pengurus'] ?? '5+' }}</p>
                <p class="text-slate-500 font-medium tracking-wide uppercase text-sm">Pengurus Panti</p>
            </div>
            <div class="p-4">
                <p class="text-5xl font-black text-emerald-600 mb-2">100%</p>
                <p class="text-slate-500 font-medium tracking-wide uppercase text-sm">Transparansi Penyaluran</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: About & Unique Mission Story -->
<section class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="order-2 lg:order-1 relative">
                <div class="absolute inset-0 bg-emerald-200 rounded-3xl transform translate-x-4 translate-y-4 -z-10"></div>
                <img src="https://images.unsplash.com/photo-1594708767771-a7502209ff51?q=80&w=1000&auto=format&fit=crop" alt="Pendidikan Anak" class="rounded-3xl shadow-xl w-full h-[500px] object-cover">
                <div class="absolute -bottom-6 -right-6 bg-white p-6 rounded-2xl shadow-xl max-w-xs">
                    <div class="flex items-center gap-4">
                        <div class="bg-emerald-100 p-3 rounded-full text-emerald-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <p class="font-bold text-slate-800 text-sm">Fokus Pendidikan & Kemandirian</p>
                    </div>
                </div>
            </div>
            
            <div class="order-1 lg:order-2">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6 leading-tight">
                    Panti Asuhan Vita Dulcedo: Merajut Asa dan Karakter
                </h2>
                <div class="w-20 h-1.5 bg-emerald-500 mb-8 rounded-full"></div>
                <div class="space-y-6 text-slate-600 text-lg leading-relaxed">
                    <p>
                        Panti Asuhan Vita Dulcedo adalah panti asuhan putri yang dikelola oleh suster-suster Kongregasi KYM di bawah naungan Yayasan Vita Dulcedo. Cabang Harapan Indah Bekasi didirikan pada tanggal 06 Agustus 2014 dengan misi kemanusiaan dan pendidikan.
                    </p>
                    <p>
                        Visi kami berlandaskan pada pendidikan formal dan iman, membangun perkembangan karakter yang jujur, mandiri, dan berakhlak mulia. Kami juga bekerjasama dengan Keuskupan Agung Jakarta (Misi Domestik KAJ) untuk mendidik anak-anak yang berasal dari Bomomani, Papua.
                    </p>
                </div>
                <div class="mt-10">
                    <a href="{{ route('about') }}" class="text-emerald-600 font-bold hover:text-emerald-700 flex items-center gap-2 group">
                        Pelajari lebih lanjut tentang sejarah kami
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 4: Core Features / How It Works (Grid) -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Sistem Terintegrasi untuk Kebaikan</h2>
            <p class="text-lg text-slate-500">Platform kami dirancang untuk menjembatani niat baik Anda dengan kebutuhan nyata anak-anak panti secara transparan dan mudah.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <!-- Feature 1 -->
            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Donasi Transparan</h3>
                <p class="text-slate-600 leading-relaxed">
                    Pilih nominal, transfer dengan aman, dan dapatkan konfirmasi instan. Laporan penggunaan dana dapat diakses langsung oleh donatur.
                </p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Kunjungan Terjadwal</h3>
                <p class="text-slate-600 leading-relaxed">
                    Sistem reservasi online untuk kunjungan. Mencegah kerumunan dan memastikan setiap donatur memiliki quality time yang bermakna dengan anak-anak.
                </p>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Pendidikan & Karakter</h3>
                <p class="text-slate-600 leading-relaxed">
                    Setiap bantuan yang masuk difokuskan untuk biaya pendidikan formal, pelatihan keterampilan mandiri, dan kebutuhan nutrisi harian.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: "Kabar Panti" / Latest Activities -->
<section class="py-24 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 mb-2">Kabar Panti</h2>
                <p class="text-slate-500">Ikuti kegiatan terbaru dan perkembangan anak asuh kami.</p>
            </div>
            <a href="{{ route('kegiatan') }}" class="hidden md:inline-flex items-center text-emerald-600 font-semibold hover:text-emerald-700">
                Lihat Semua Kegiatan
                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($latestNews ?? [] as $news)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300 border border-slate-100 flex flex-col">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $news['image'] }}" alt="{{ $news['title'] }}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-emerald-700 shadow-sm">
                        {{ $news['date'] }}
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-900 mb-3 leading-snug">
                        <a href="#" class="hover:text-emerald-600 transition-colors">{{ $news['title'] }}</a>
                    </h3>
                    <p class="text-slate-600 text-sm flex-1">{{ $news['excerpt'] }}</p>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="#" class="text-emerald-600 text-sm font-semibold hover:text-emerald-700 flex items-center">
                            Baca selengkapnya
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-slate-500">
                Belum ada kegiatan terbaru yang dibagikan.
            </div>
            @endforelse
        </div>
        
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('kegiatan') }}" class="inline-flex items-center text-emerald-600 font-semibold border border-emerald-600 px-6 py-2 rounded-full hover:bg-emerald-50">
                Lihat Semua Kegiatan
            </a>
        </div>
    </div>
</section>

<!-- Section 6: Pre-Footer (Contact & Map) -->
<section class="bg-emerald-900 text-white relative">
    <!-- Decorative pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row relative z-10">
        <!-- Contact Details -->
        <div class="flex-1 px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Mari Berkunjung & Berbagi</h2>
            <p class="text-emerald-100 mb-10 text-lg max-w-md">Kehadiran Anda adalah semangat bagi mereka. Jadwalkan kunjungan Anda atau hubungi kami untuk informasi lebih lanjut.</p>
            
            <div class="space-y-6">
                <div class="flex items-start">
                    <div class="bg-emerald-800 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg text-white mb-1">Alamat Panti</h4>
                        <p class="text-emerald-200">Jl. Flamboyan Blok KM 10-11,<br>Kota Harapan Indah, Bekasi</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-emerald-800 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg text-white mb-1">WhatsApp / Telepon</h4>
                        <p class="text-emerald-200">0813-1567-2350</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-emerald-800 p-3 rounded-full mr-4">
                        <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg text-white mb-1">Email</h4>
                        <p class="text-emerald-200">vitadulcedoharapanindah@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Google Maps -->
        <div class="w-full lg:w-1/2 h-80 lg:h-auto min-h-[300px] relative bg-slate-200">
            <!-- Map iframe placeholder -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.748283995878!2d106.97495!3d-6.183495!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698bb2c2b3dc1d%3A0x8e8316dfa996bd51!2sKota%20Harapan%20Indah!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                class="absolute inset-0 w-full h-full border-0" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
@endsection
