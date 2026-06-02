<footer class="bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <a href="{{ route('home') }}" class="inline-block mb-4 bg-white px-4 py-2 rounded-xl">
                    <img src="{{ asset('assets/logoVitaDulcedo.png') }}" alt="Logo Vita Dulcedo" class="h-14 md:h-16 w-auto object-contain">
                </a>
                <p class="text-sm leading-relaxed text-slate-400 max-w-sm">
                    Panti Asuhan Vita Dulcedo hadir untuk merajut harapan dan masa depan anak-anak nusantara, dengan fokus misi kemanusiaan untuk anak-anak dari Bomomani, Papua.
                </p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Tautan Cepat</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('donasi') }}" class="hover:text-emerald-400 transition-colors">Donasi</a></li>
                    <li><a href="{{ route('kunjungan') }}" class="hover:text-emerald-400 transition-colors">Jadwal Kunjungan</a></li>
                    <li><a href="{{ route('kegiatan') }}" class="hover:text-emerald-400 transition-colors">Kegiatan Kami</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Kontak</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Jl. Flamboyan Blok KM 10-11,<br>Kota Harapan Indah, Bekasi</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>0813-1567-2350</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>vitadulcedo@gmail.com</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-12 pt-8 border-t border-slate-800 text-center text-sm text-slate-500">
            &copy; {{ date('Y') }} Panti Asuhan Vita Dulcedo. Hak Cipta Dilindungi.
        </div>
    </div>
</footer>
