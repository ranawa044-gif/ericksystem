<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-RICK - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .hidden { display: none; }
        @media (min-width: 640px) { .sm\:block { display: block; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">E</div>
                <div class="sidebar-title">E-RICK</div>
            </div>

            <nav class="sidebar-nav">
                @if(auth()->user()->role === 'mahasiswa')
                    <a href="{{ route('dashboard.mahasiswa', auth()->user()->mahasiswa->nim) }}" class="nav-item {{ request()->routeIs('dashboard.mahasiswa') ? 'active' : '' }}">
                @else
                    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->role === 'mahasiswa')
                    <!-- Menu Mahasiswa -->
                    <!-- Note: 'Akademik' merged into the main Dashboard link for mahasiswa -->
                    <a href="{{ route('mahasiswa.prestasi', auth()->user()->mahasiswa->nim) }}" class="nav-item {{ request()->routeIs('mahasiswa.prestasi') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Prestasi
                    </a>
                    <a href="{{ route('pesan.mahasiswa', auth()->user()->mahasiswa->nim) }}" class="nav-item {{ request()->routeIs('pesan.mahasiswa') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Konsultasi
                    </a>
                    <a href="{{ route('mahasiswa.password.edit') }}" class="nav-item {{ request()->routeIs('mahasiswa.password.edit') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pengaturan Akun
                    </a>
                @endif

                @if(auth()->user()->isPengelola())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'dosen')
                    <a href="{{ route('pengelola.mahasiswa') }}" class="nav-item {{ request()->routeIs('pengelola.mahasiswa') || request()->routeIs('pengelola.mahasiswa.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Data Mahasiswa
                    </a>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('pengelola.mata-kuliah') }}" class="nav-item {{ request()->routeIs('pengelola.mata-kuliah') || request()->routeIs('pengelola.mata-kuliah.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Mata Kuliah
                    </a>
                    @endif
                    <a href="{{ route('pengelola.input-nilai') }}" class="nav-item {{ request()->routeIs('pengelola.input-nilai') || request()->routeIs('pengelola.input-nilai.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Input Nilai
                    </a>
                    <a href="{{ route('pengelola.catatan') }}" class="nav-item {{ request()->routeIs('pengelola.catatan') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Catatan Dosen
                    </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('pengelola.dosen') }}" class="nav-item {{ request()->routeIs('pengelola.dosen') || request()->routeIs('pengelola.dosen.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Data Dosen
                    </a>
                    @endif

                    <a href="{{ route('pengelola.rekap.mahasiswa') }}" class="nav-item {{ request()->routeIs('pengelola.rekap.mahasiswa') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Rekap Mahasiswa
                    </a>
                    <a href="{{ route('pengelola.rekap.prestasi') }}" class="nav-item {{ request()->routeIs('pengelola.rekap.prestasi') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Rekap Prestasi
                    </a>

                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('pengelola.pesan.mahasiswa') }}" class="nav-item {{ request()->routeIs('pengelola.pesan.mahasiswa') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        Pesan Mahasiswa
                    </a>
                    <a href="{{ route('advisor.admin') }}" class="nav-item {{ request()->routeIs('advisor.admin') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        SPK Analytics
                    </a>
                    <a href="{{ route('pengelola.dokumentasi') }}" class="nav-item {{ request()->routeIs('pengelola.dokumentasi') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        📚 Dokumentasi Fitur
                    </a>
                    <a href="{{ route('pengelola.analisis_perancangan') }}" class="nav-item {{ request()->routeIs('pengelola.analisis_perancangan') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        📐 Analisis & UML
                    </a>
                    @endif
                @endif

                @if(auth()->user()->role === 'dosen')
                <a href="{{ route('pesan.dosen') }}" class="nav-item {{ request()->routeIs('pesan.dosen') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Konsultasi Mahasiswa
                </a>
                <a href="{{ route('advisor.dosen') }}" class="nav-item {{ request()->routeIs('advisor.dosen') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Smart Advisor
                </a>
                <a href="{{ route('dosen.password.edit') }}" class="nav-item {{ request()->routeIs('dosen.password.edit') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Akun
                </a>
                @endif

                <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.index') || request()->routeIs('reports.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Report Center
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <div class="flex items-center gap-4">
                    <button class="menu-toggle" id="menuToggle" aria-expanded="false" aria-label="Toggle sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="font-semibold text-xl hidden sm:block">@yield('title', 'Dashboard')</h2>
                </div>
                
                <div class="header-user">
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">{{ auth()->user()->role }}</div>
                    </div>
                    <div class="user-avatar">
                        @php
                            $foto = null;
                            if (auth()->user()->role === 'mahasiswa' && auth()->user()->mahasiswa && auth()->user()->mahasiswa->foto) {
                                $foto = asset('uploads/' . auth()->user()->mahasiswa->foto);
                            }
                        @endphp
                        @if($foto)
                            <img src="{{ $foto }}" alt="Avatar">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    </div>
                </div>
            </header>

            <div class="content-area">
                @if (session('success'))
                    <div class="alert alert-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="mb-2">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuToggle = document.getElementById('menuToggle');
            const mainContent = document.querySelector('.main-content');

            function isMobile() {
                return window.innerWidth <= 768;
            }

            function openMobileSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('show');
            }

            function closeMobileSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            }

            function setToggleIcon(open) {
                if (!menuToggle) return;
                menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) {
                    // Close (X) icon
                    menuToggle.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>`;
                } else {
                    // Hamburger icon
                    menuToggle.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>`;
                }
            }

            function toggleSidebar() {
                if (isMobile()) {
                    // mobile: use overlay + open class
                    if (sidebar.classList.contains('open')) {
                        closeMobileSidebar();
                        setToggleIcon(false);
                    } else {
                        openMobileSidebar();
                        setToggleIcon(true);
                    }
                } else {
                    // desktop: collapse/expand sidebar
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('sidebar-collapsed');
                    setToggleIcon(sidebar.classList.contains('collapsed'));
                }
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', toggleSidebar);
                // initialize icon state
                setToggleIcon(false);
            }

            if (overlay) {
                overlay.addEventListener('click', closeMobileSidebar);
            }

            // Ensure correct classes on resize
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    // remove mobile-only classes
                    closeMobileSidebar();
                } else {
                    // remove desktop collapsed when switching to mobile
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('sidebar-collapsed');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
