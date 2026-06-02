<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts and Styles -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>

    <div class="layout-wrapper">
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebar-overlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="layout-sidebar">
            <a href="{{ url('/') }}" class="sidebar-brand">
                <div class="sidebar-brand-logo">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <div class="sidebar-brand-text">
                    Vita<span>Dulcedo</span>
                </div>
            </a>

            <ul class="sidebar-menu">
                <li class="menu-header">Dashboards</li>
                <li class="menu-item">
                    <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-house menu-item-icon"></i>
                        <span class="menu-item-text">Dashboard</span>
                    </a>
                </li>
                
                <li class="menu-header">Pages</li>
                <li class="menu-item">
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fa-regular fa-user menu-item-icon"></i>
                        <span class="menu-item-text">Users</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.donasi') }}" class="{{ request()->routeIs('admin.donasi') ? 'active' : '' }}">
                        <i class="fa-solid fa-hand-holding-dollar menu-item-icon"></i>
                        <span class="menu-item-text">Donasi</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.kegiatan') }}" class="{{ request()->routeIs('admin.kegiatan') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-check menu-item-icon"></i>
                        <span class="menu-item-text">Kegiatan</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.campaigns.index') }}" class="{{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-bullseye menu-item-icon"></i>
                        <span class="menu-item-text">Kebutuhan Panti</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-user">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=7367f0&color=fff" alt="User" class="user-avatar">
                <div class="user-info">
                    <div class="user-name">Admin User</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="layout-content-wrapper" id="layout-content-wrapper">
            
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle-btn" id="sidebar-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="topbar-search d-none d-md-block">
                        <i class="fa-solid fa-search search-icon"></i>
                        <input type="text" class="form-control" placeholder="Search (Ctrl+/)">
                    </div>
                </div>

                <div class="topbar-right">
                    <button class="topbar-icon-btn">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge-dot"></span>
                    </button>
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=7367f0&color=fff" alt="User" class="topbar-avatar ms-2">
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="content-area">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="layout-footer">
                <div>
                    &copy; {{ date('Y') }} <a href="#">Vita Dulcedo</a>. All rights reserved.
                </div>
                <div>
                    Made with <i class="fa-solid fa-heart text-danger"></i>
                </div>
            </footer>
        </div>
    </div>

</body>
</html>
