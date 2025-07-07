<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - App</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    <style>
        /* Custom scrollbar for webkit browsers */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        [x-cloak] { display: none !important; }
    </style>
    @laravelPWA
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ sidebarOpen: true }" class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-30 w-64 bg-emerald-700 text-white flex flex-col custom-scrollbar overflow-y-auto transform lg:translate-x-0 lg:static lg:inset-0">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-emerald-600 flex-shrink-0">
                <img src="{{ asset('images/icon/sidebar-logo.svg') }}" alt="GreenPort Logo" class="h-12 w-12 bg-white rounded-full border border-white">
            </div>

            <!-- Navigation Links -->
            <nav class="flex-grow p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('dashboard*') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                    <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/dashboard-icon.svg') }}" alt="Dashboard Icon">
                    Dashboard
                </a>
                @role('Auditor')
                <a href="{{ route('auditor.checklist-templates.index') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('auditor.checklist-templates.*') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                    <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/checklist-icon.svg') }}" alt="Checklist Icon">
                    Checklist & Kepatuhan
                </a>
                @endrole

                @role('Auditor')
                <div x-data="{ open: @json(request()->is('indikator-dokumen') || request()->is('insert-kriteria-auditor') || request()->is('bukti-pendukung-auditee') || request()->is('insert-sub-kriteria-auditor')) }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-md hover:bg-emerald-600 focus:outline-none {{ request()->is('indikator-dokumen') || request()->is('insert-kriteria-auditor') || request()->is('bukti-pendukung-auditee') || request()->is('insert-sub-kriteria-auditor') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                        <span class="flex items-center">
                            <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/konfigurasi-data-icon.svg') }}" alt="Konfigurasi Data Icon">
                            Indikator & Bobot
                        </span>
                        <img :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" src="{{ asset('images/icon/panah-dropdown-icon.svg') }}" alt="Panah Dropdown">
                    </button>
                    <div x-show="open" x-cloak x-transition class="ml-4 pl-4 border-l-2 border-emerald-500 space-y-1">
                        <a href="{{ route('indikator-dokumen.index') }}" class="block px-3 py-2 rounded-md hover:bg-emerald-600 {{ request()->is('indikator-dokumen') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">Indikator Dokumen</a>
                        <a href="{{ route('kriteria.create') }}" class="block px-3 py-2 rounded-md hover:bg-emerald-600 {{ request()->is('insert-kriteria-auditor') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">Kriteria Dokumen</a>
                        <a href="{{ route('insert.sub.kriteria.auditor') }}" class="block px-3 py-2 rounded-md hover:bg-emerald-600 {{ request()->is('insert-sub-kriteria-auditor') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">Sub-Kriteria</a>
                        <a href="{{ route('bukti-pendukung.index') }}" class="block px-3 py-2 rounded-md hover:bg-emerald-600 {{ request()->routeIs('bukti-pendukung.*') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">Bukti Pendukung</a>
                        <!-- Manajemen Indikator & Bobot -->
                        <a href="{{ route('indikator.index') }}" class="block px-3 py-2 rounded-md hover:bg-emerald-600 {{ request()->routeIs('indikator.*') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">Manajemen Indikator</a>
                    </div>
                </div>
                @endrole
                 {{-- <div x-data="{ open: @json(request()->is('tambah-dokumen') || request()->is('tambah-dokumen*')) }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-md hover:bg-emerald-600 focus:outline-none {{ request()->is('tambah-dokumen*') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                        <span class="flex items-center">
                            <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/dokumen-icon.svg') }}" alt="Dokumen Icon">
                            Dokumen
                        </span>
                        <img :class="{'rotate-180': open}" class="h-5 w-5 transform transition-transform duration-200" src="{{ asset('images/icon/panah-dropdown-icon.svg') }}" alt="Panah Dropdown">
                    </button>
                    <div x-show="open" x-cloak x-transition class="ml-4 pl-4 border-l-2 border-emerald-500 space-y-1">
                        <a href="{{ route('tambah.dokumen') }}" class="block px-3 py-2 rounded-md hover:bg-emerald-600 {{ request()->is('tambah-dokumen') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">Input Dokumen</a>
                        <a href="#" class="block px-3 py-2 rounded-md hover:bg-emerald-600">List Dokumen</a>
                    </div>
                </div> --}}


                @role('Auditor')
                <a href="{{ route('history') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->is('history*') || request()->is('lihat-history') || request()->is('tambah-history') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                    <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/history-icon.svg') }}" alt="History Icon">
                    Riwayat Audit
                </a>
                @endrole
                @role('Auditor')
                <a href="{{ route('pelaporan') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->is('pelaporan*') || request()->is('tambah-pelaporan') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                    <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/pelaporan-icon.svg') }}" alt="Pelaporan Icon">
                    Laporan Audit
                </a>
                @endrole
                 @role('Auditor')
                 <a href="{{ route('visitasi.lapangan') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->is('visitasi-lapangan') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                    <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/visitasi-lapangan-icon.svg') }}" alt="Visitasi Lapangan Icon">
                    Visitasi Lapangan
                </a>
                 @endrole
            @role('Auditor')
            <a href="{{ route('daftar.audit.auditor') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('daftar.audit.auditor') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/audit-schedule-icon.svg') }}" alt="Jadwal Audit Icon">
                Jadwal Audit
            </a>
            
            <a href="{{ route('regulasi') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('regulasi') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/regulasi-icon.svg') }}" alt="Regulasi Icon">
                Regulasi & Standar
            </a>
            <a href="{{ route('forum') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('forum') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/forum-icon.svg') }}" alt="Forum Icon">
                Forum & Konsultasi
            </a>
            <a href="{{ route('sertifikasi') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('sertifikasi') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/sertifikasi-icon.svg') }}" alt="Sertifikasi Icon">
                Sertifikasi & Penghargaan
            </a>
            @endrole

            @role('Auditee')
            <a href="{{ route('detail.audit.auditee') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('detail.audit.auditee') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/audit-schedule-icon.svg') }}" alt="Jadwal Audit Icon">
                Jadwal & Tugas
            </a>
            <a href="{{ route('auditee.tugas.index') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('auditee.tugas.index') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/checklist-icon.svg') }}" alt="Checklist Icon">
                Isi Checklist
            </a>
            <a href="{{ route('bukti-pendukung.index') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('bukti-pendukung.index') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/upload-icon.svg') }}" alt="Upload Icon">
                Upload Dokumen
            </a>
            <a href="{{ route('history') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('history') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/laporan-icon.svg') }}" alt="Laporan Icon">
                Laporan & Riwayat
            </a>
            
            <a href="{{ route('regulasi') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('regulasi') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/regulasi-icon.svg') }}" alt="Regulasi Icon">
                Regulasi & Standar
            </a>
            <a href="{{ route('forum') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('forum') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/forum-icon.svg') }}" alt="Forum Icon">
                Forum & Konsultasi
            </a>
            <a href="{{ route('sertifikasi') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->routeIs('sertifikasi') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/sertifikasi-icon.svg') }}" alt="Sertifikasi Icon">
                Sertifikasi & Penghargaan
            </a>
            @endrole

            </nav>

            <!-- Bottom Links -->
            <div class="p-4 border-t border-emerald-600 mt-auto flex-shrink-0">
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2.5 rounded-md hover:bg-emerald-600 {{ request()->is('profile') ? 'bg-lime-200 text-emerald-800 font-semibold' : '' }}">
                    <img class="h-6 w-6 mr-3" src="{{ asset('images/icon/pengaturan-icon.svg') }}" alt="Pengaturan Icon">
                    Pengaturan
                </a>
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="flex items-center px-3 py-2.5 rounded-md text-red-500 hover:bg-red-600 hover:text-white">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                    Keluar
                </a>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex items-center justify-between h-16 px-6 bg-white border-b">
                <!-- Hamburger Menu (Mobile) -->
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 focus:outline-none">
                    <img class="h-6 w-6" src="{{ asset('images/icon/hamburger-icon.svg') }}" alt="Hamburger Icon">
                </button>
                
                <!-- Placeholder for search or other header content on the left -->
                <div></div>

                <!-- Right side of header -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('notifikasi') }}" class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
                        <img class="h-6 w-6" src="{{ asset('images/icon/notifikasi-icon.svg') }}" alt="Notifikasi Icon">
                    </a>
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <div @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 focus:outline-none">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name }}">
                            <span class="hidden md:block text-gray-700">{{ Auth::user()->name }}</span>
                            <img :class="{'rotate-180': dropdownOpen}" class="h-5 w-5 text-gray-500 transform transition-transform duration-200" src="{{ asset('images/icon/panah-dropdown-icon.svg') }}" alt="Panah Dropdown">
                        </div>
                        <!-- <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
                             x-transition:enter="transition ease-out duration-100 transform"
                             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75 transform"
                             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan Akun</a>
                            
                            Logout Form
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                   class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Keluar
                                </a>
                            </form>
                        </div> -->
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6 custom-scrollbar">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="font-bold">Sukses</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="ml-auto text-green-700 hover:text-green-900
                                &times;
                            </button>
                        </div>
                    </div>
                @endif

                @if (isset($header))
                    <header class="bg-white shadow rounded-lg mb-6">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
