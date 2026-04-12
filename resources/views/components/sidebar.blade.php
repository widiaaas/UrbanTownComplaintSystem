@php
    // Ambil user yang sedang login
    $user = Auth::user();
    $currentPath = request()->path();
    
    // 🔥 TAMBAHAN (FIX ROLE)
    $karyawanRole = null;
    if ($user && $user->role === 'karyawan' && $user->karyawan) {
        $karyawanRole = $user->karyawan->role;
    }

    // Helper untuk active menu
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
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-9 object-contain" />
            <div class="leading-tight">
                <h1 class="font-bold text-lg text-gray-900">Unit Complaint System</h1>
                <p class="text-xs text-gray-500">Sistem Keluhan Unit</p>
            </div>
        </a>

        {{-- MENU --}}
        <nav class="flex-1 space-y-1 overflow-y-auto">

        @if($karyawanRole === 'admin')
            {{-- DASHBOARD --}}
            <a href="/dashboardAdmin"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('dashboardAdmin', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            {{-- KELOLA UNIT --}}
            <a href="/IndexUnits"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('IndexUnits', $currentPath) }}">
                @include('components.icons.building')
                <span>Kelola Unit</span>
            </a>

            {{-- KELOLA PENGGUNA (SUB MENU) --}}
            @php
                $userMenuOpen =
                    request()->is('IndexKaryawan*') ||
                    request()->is('IndexPenghuni*');
            @endphp

            <div x-data="{ open: {{ $userMenuOpen ? 'true' : 'false' }} }" class="space-y-1">

                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        @include('components.icons.users')
                        <span>Kelola Pengguna</span>
                    </div>
                    <svg :class="open ? 'rotate-90' : ''"
                        class="h-4 w-4 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="open" x-collapse class="ml-10 space-y-1">
                    <a href="/IndexKaryawan"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('IndexKaryawan', $currentPath) }}">
                        <span>Karyawan</span>
                    </a>

                    <a href="/IndexPenghuni"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('IndexPenghuni', $currentPath) }}">
                        <span>Penghuni</span>
                    </a>
                </div>
            </div>

            {{-- PROFILE --}}
            <a href="/profileAdmin"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('profileAdmin', $currentPath) }}">
                @include('components.icons.user')
                <span>Profile Admin</span>
            </a>

        @elseif($user->role === 'unit')
            {{-- PENGHUNI --}}
            {{-- DASHBOARD --}}
            <a href="/dashboardPenghuni"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('dashboardPenghuni', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            {{-- PENGAJUAN KELUHAN --}}
            <a href="/ajukanKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('ajukanKeluhan', $currentPath) }}">
                @include('components.icons.form')
                <span>Ajukan Keluhan</span>
            </a>

            {{-- RIWAYAT KELUHAN --}}
            <a href="/riwayatKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('riwayatKeluhan', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Keluhan Saya</span>
            </a>

            {{-- PROFILE --}}
            <a href="/profilePenghuni"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('profilePenghuni', $currentPath) }}">
                @include('components.icons.user')
                <span>Profile Penghuni</span>
            </a>
            
            @elseif($karyawanRole === 'tenant_relation')
            {{-- DASHBOARD --}}
            <a href="/dashboardTenantRelation"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('dashboardTenantRelation', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            {{-- KELOLA KELUHAN (SUB MENU) --}}
            @php
                $userMenuOpen =
                    request()->is('keluhanMasuk*') ||
                    request()->is('daftarPenanganan*');
            @endphp

            <div x-data="{ open: {{ $userMenuOpen ? 'true' : 'false' }} }" class="space-y-1">

                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        @include('components.icons.clipboardList')
                        <span>Kelola Keluhan</span>
                    </div>
                    <svg :class="open ? 'rotate-90' : ''"
                        class="h-4 w-4 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="open" x-collapse class="ml-10 space-y-1">
                    <a href="/keluhanMasuk"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('keluhanMasuk', $currentPath) }}">
                        <span>Keluhan Masuk</span>
                    </a>

                    <a href="/daftarPenanganan"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('daftarPenanganan', $currentPath) }}">
                        <span>Daftar Penanganan Keluhan Saya</span>
                    </a>
                </div>
            </div>

            {{-- KNOWLEDGE BASE --}}
            <a href="/knowledgeBase"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('knowledgeBase', $currentPath) }}">
                @include('components.icons.bookOpen')
                <span>Knowledge Base</span>
            </a>

            {{-- LAPORAN (sementara link placeholder) --}}
            <a href="/rekapPenangananTR"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('rekapPenangananTR', $currentPath) }}">
                @include('components.icons.scrollText')
                <span>Laporan</span>
            </a>

            {{-- PROFILE --}}
            <a href="/profileTR"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('profileTR', $currentPath) }}">
                @include('components.icons.user')
                <span>Profile Tenant Relation</span>
            </a>

            @elseif($karyawanRole === 'departemen')
            {{-- DASHBOARD --}}
            <a href="/dashboardDepartemen"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('dashboardDepartemen', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            {{-- KELOLA WORK ORDER (SUB MENU) --}}
            @php
                $userMenuOpen =
                    request()->is('workOrderMasuk*') ||
                    request()->is('daftarWorkOrder*');
            @endphp

            <div x-data="{ open: {{ $userMenuOpen ? 'true' : 'false' }} }" class="space-y-1">

                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                    <div class="flex items-center gap-3">
                        @include('components.icons.clipboardList')
                        <span>Kelola Work Order</span>
                    </div>
                    <svg :class="open ? 'rotate-90' : ''"
                        class="h-4 w-4 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <div x-show="open" x-collapse class="ml-10 space-y-1">
                    <a href="/workOrderMasuk"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('workOrderMasuk', $currentPath) }}">
                        <span>Work Order Masuk</span>
                    </a>

                    <a href="/daftarWorkOrder"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('daftarWorkOrder', $currentPath) }}">
                        <span>Daftar Penanganan Work Order Saya</span>
                    </a>
                </div>
            </div>

            {{-- LAPORAN (sementara link placeholder) --}}
            <a href="#"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                @include('components.icons.scrollText')
                <span>Laporan</span>
            </a>

            {{-- PROFILE --}}
            <a href="/profileDepartemen"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('profileDepartemen', $currentPath) }}">
                @include('components.icons.user')
                <span>Profile Departemen</span>
            </a>
        @endif

    </nav>

</aside>