<nav x-data="{ open: false, profileOpen: false }" class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('assets/logoVitaDulcedo.png') }}" alt="Logo Vita Dulcedo" class="h-16 md:h-20 w-auto object-contain">
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-emerald-600 font-semibold border-b-2 border-emerald-600' : 'text-slate-600 hover:text-emerald-600 font-medium' }} px-1 py-2 transition-colors">Beranda</a>
                <a href="{{ route('donasi') }}" class="{{ request()->routeIs('donasi') ? 'text-emerald-600 font-semibold border-b-2 border-emerald-600' : 'text-slate-600 hover:text-emerald-600 font-medium' }} px-1 py-2 transition-colors">Donasi</a>
                <a href="{{ route('spk.index') }}" class="{{ request()->routeIs('spk.index') ? 'text-emerald-600 font-semibold border-b-2 border-emerald-600' : 'text-slate-600 hover:text-emerald-600 font-medium' }} px-1 py-2 transition-colors">Rekomendasi</a>
                <a href="{{ route('kunjungan') }}" class="{{ request()->routeIs('kunjungan') ? 'text-emerald-600 font-semibold border-b-2 border-emerald-600' : 'text-slate-600 hover:text-emerald-600 font-medium' }} px-1 py-2 transition-colors">Kunjungan</a>
                <a href="{{ route('kegiatan') }}" class="{{ request()->routeIs('kegiatan') ? 'text-emerald-600 font-semibold border-b-2 border-emerald-600' : 'text-slate-600 hover:text-emerald-600 font-medium' }} px-1 py-2 transition-colors">Kegiatan</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-emerald-600 font-semibold border-b-2 border-emerald-600' : 'text-slate-600 hover:text-emerald-600 font-medium' }} px-1 py-2 transition-colors">Tentang Kami</a>
                
                @auth
                    <!-- Profile Dropdown -->
                    <div class="relative ml-4">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-2.5 px-4 py-2 rounded-full bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors focus:outline-none">
                            <span class="w-7 h-7 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-xs font-black shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="text-sm font-extrabold text-slate-700 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="profileOpen ? 'rotate-180 text-emerald-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="profileOpen" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="transform opacity-0 scale-95 translate-y-1" 
                             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0" 
                             x-transition:leave="transition ease-in duration-150" 
                             x-transition:leave-start="transform opacity-100 scale-100 translate-y-0" 
                             x-transition:leave-end="transform opacity-0 scale-95 translate-y-1" 
                             class="absolute right-0 mt-3 w-64 bg-white border border-slate-100 rounded-2xl shadow-xl py-3 z-50 divide-y divide-slate-50" 
                             style="display: none;">
                            
                            {{-- Info Profil --}}
                            <div class="px-4 py-3 pb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 text-white flex items-center justify-center font-black shadow-sm text-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-slate-800 truncate leading-tight">{{ Auth::user()->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-semibold truncate mt-0.5">{{ Auth::user()->login }}</p>
                                    </div>
                                </div>
                                <div class="mt-2.5 flex items-center justify-between">
                                    <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                                        {{ Auth::user()->role === 'admin' ? 'Pihak Panti' : 'Donatur' }}
                                    </span>
                                </div>
                            </div>
                            
                            {{-- Aksi Menu --}}
                            @if(Auth::user()->role === 'admin')
                            <div class="py-1">
                                <a href="{{ route('admin.donasi') }}" class="block px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-emerald-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    Kelola Donasi (Admin)
                                </a>
                                <a href="{{ route('admin.kegiatan') }}" class="block px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-emerald-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Kelola Kegiatan (Admin)
                                </a>
                                <a href="{{ route('admin.campaigns.index') }}" class="block px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-emerald-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Kebutuhan Panti (Admin)
                                </a>
                                <a href="{{ route('admin.users') }}" class="block px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-emerald-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    Kelola Pengguna (Admin)
                                </a>
                            </div>
                            @else
                            <div class="py-1">
                                <a href="{{ route('user.history') }}" class="block px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-emerald-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Riwayat Donasi & Kegiatan
                                </a>
                            </div>
                            @endif
                            
                            {{-- Keluar --}}
                            <div class="py-1">
                                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50/50 transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar / Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="ml-4 inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white hover:from-emerald-700 hover:to-emerald-600 text-sm font-bold rounded-full shadow-sm hover:shadow transition-all duration-300">
                        Login / Masuk
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500">
                    <span class="sr-only">Buka menu utama</span>
                    <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-cloak x-show="open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-cloak x-show="open" x-transition class="md:hidden border-t border-slate-100" style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 shadow-lg bg-white">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('home') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Beranda</a>
            <a href="{{ route('donasi') }}" class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('donasi') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Donasi</a>
            <a href="{{ route('spk.index') }}" class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('spk.index') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Rekomendasi</a>
            <a href="{{ route('kunjungan') }}" class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('kunjungan') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Kunjungan</a>
            <a href="{{ route('kegiatan') }}" class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('kegiatan') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Kegiatan</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('about') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Tentang Kami</a>
            
            <div class="mt-4 pt-4 border-t border-slate-100">
                @auth
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 text-white flex items-center justify-center font-black shadow-sm text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-slate-800 truncate leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-slate-400 font-semibold truncate mt-0.5">{{ Auth::user()->login }}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                                {{ Auth::user()->role === 'admin' ? 'Pihak Panti' : 'Donatur' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.donasi') }}" class="block w-full text-center py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                            Kelola Donasi (Admin)
                        </a>
                        <a href="{{ route('admin.kegiatan') }}" class="block w-full text-center py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                            Kelola Kegiatan (Admin)
                        </a>
                        <a href="{{ route('admin.campaigns.index') }}" class="block w-full text-center py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                            Kebutuhan Panti (Admin)
                        </a>
                        <a href="{{ route('admin.users') }}" class="block w-full text-center py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                            Kelola Pengguna (Admin)
                        </a>
                        @else
                        <a href="{{ route('user.history') }}" class="block w-full text-center py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors">
                            Riwayat Donasi & Kegiatan
                        </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="block w-full text-center py-2.5 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-bold rounded-xl transition-colors">
                                Keluar / Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white hover:from-emerald-700 hover:to-emerald-600 text-sm font-bold rounded-xl shadow-sm transition-all">
                        Login / Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
