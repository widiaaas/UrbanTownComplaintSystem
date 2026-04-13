@php
    // Ambil user login
    $user = Auth::user();
    $currentPath = request()->path();

    // Ambil role karyawan dengan aman
    $karyawanRole = null;
    if ($user && $user->role === 'karyawan') {
        $karyawanRole = optional($user->karyawan)->role;
    }

    // Helper active menu
    function activeMenu($path, $currentPath) {
        return $currentPath === trim($path, '/') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100';
    }
@endphp

<!-- Overlay mobile -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black bg-opacity-25 lg:hidden"
     @click="sidebarOpen = false" x-transition.opacity></div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-200 ease-in-out flex flex-col">

    <div class="flex flex-col h-full p-4">

        {{-- LOGO --}}
        <a href="/" class="flex items-center space-x-3 mb-6">
            <img src="{{ asset('images/logo.png') }}" class="h-9 w-9 object-contain" />
            <div class="leading-tight">
                <h1 class="font-bold text-lg text-gray-900">Unit Complaint System</h1>
                <p class="text-xs text-gray-500">Sistem Keluhan Unit</p>
            </div>
        </a>

        {{-- MENU --}}
        <nav class="flex-1 space-y-1 overflow-y-auto">

        {{-- ================= ADMIN ================= --}}
        @if($karyawanRole === 'admin')

            <a href="/dashboard"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('dashboard', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            <a href="/IndexUnits"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('IndexUnits', $currentPath) }}">
                @include('components.icons.building')
                <span>Kelola Unit</span>
            </a>

            @php
                $userMenuOpen = request()->is('IndexKaryawan*') || request()->is('IndexPenghuni*');
            @endphp

            <div x-data="{ open: {{ $userMenuOpen ? 'true' : 'false' }} }" class="space-y-1">

                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        @include('components.icons.users')
                        <span>Kelola Pengguna</span>
                    </div>
                </button>

                <div x-show="open" x-collapse class="ml-10 space-y-1">
                    <a href="/IndexKaryawan"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ activeMenu('IndexKaryawan', $currentPath) }}">
                        <span>Karyawan</span>
                    </a>

                    <a href="/IndexPenghuni"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ activeMenu('IndexPenghuni', $currentPath) }}">
                        <span>Penghuni</span>
                    </a>
                </div>
            </div>

            {{-- PROFILE --}}
            <a href="/profile"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('profile', $currentPath) }}">
                @include('components.icons.user')
                <span>Profile Admin</span>
            </a>

        {{-- ================= UNIT ================= --}}
        @elseif($user?->role === 'unit')

            <a href="/ajukanKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('ajukanKeluhan', $currentPath) }}">
                @include('components.icons.form')
                <span>Ajukan Keluhan</span>
            </a>

            <a href="/riwayatKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('riwayatKeluhan', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Keluhan Saya</span>
            </a>

            {{-- PROFILE --}}
            <a href="/profile"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('profile', $currentPath) }}">
                @include('components.icons.user')
                <span>Profile Penghuni</span>
            </a>

        {{-- ================= TENANT RELATION ================= --}}
        @elseif($karyawanRole === 'tenant_relation')

            <a href="/dashboard"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('dashboard', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            <a href="/keluhan-masuk"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('keluhanMasuk', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Keluhan Masuk</span>
            </a>

            <a href="/daftar-penanganan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('daftarPenanganan', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Penanganan Saya</span>
            </a>

            <a href="/knowledgeBase"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('knowledgeBase', $currentPath) }}">
                @include('components.icons.bookOpen')
                <span>Knowledge Base</span>
            </a>

            <a href="/rekapPenangananTR"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('rekapPenangananTR', $currentPath) }}">
                @include('components.icons.scrollText')
                <span>Laporan</span>
            </a>

            {{-- PROFILE --}}
            <a href="/profile"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('profile', $currentPath) }}">
                @include('components.icons.user')
                <span>Profile Tenant Relation</span>
            </a>

        {{-- ================= DEPARTEMEN ================= --}}
        @elseif($karyawanRole === 'departemen')

            <a href="/dashboard"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('dashboard', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            <a href="/workOrderMasuk"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('workOrderMasuk', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Work Order Masuk</span>
            </a>

            <a href="/daftarWorkOrder"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('daftarWorkOrder', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Penanganan WO</span>
            </a>

            {{-- PROFILE --}}
            <a href="/profile"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('profile', $currentPath) }}">
                @include('components.icons.user')
                <span>Profile Departemen</span>
            </a>

        @endif

        </nav>

    </div>
</aside>