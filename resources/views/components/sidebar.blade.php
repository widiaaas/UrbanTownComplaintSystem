@php
    $users = [
            'admin' => (object) [
                'name' => 'Admin Demo',
                'role' => 'admin',
                'parent_role' => null,
            ],

            'departemen' => (object) [
                'name' => 'Departemen Teknik',
                'role' => 'departemen',
                'parent_role' => null,
            ],

            'tenant_relation' => (object) [
                'name' => 'Tenant Relation',
                'role' => 'tenant_relation',
                'parent_role' => 'departemen', 
            ],

            'penghuni' => (object) [
                'name' => 'daftarPenanganan Unit',
                'role' => 'penghuni',
                'parent_role' => null,
            ],
        ];
    $currentPath = request()->path();
    $user = $users['departemen']; // ganti admin / daftarPenanganan / departemen
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

        <!-- {{-- DASHBOARD --}}
        <a href="/dashboard"
        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/dashboard', $currentPath) }}">
            @include('components.icons.dashboard')
            <span>Dashboard</span>
        </a> -->

        @if($user->role === 'admin')
            {{-- DASHBOARD --}}
            <a href="/dashboardAdmin"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/dashboard', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            {{-- KELOLA UNIT --}}
            <a href="/IndexUnits"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
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

                {{-- PARENT --}}
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">

                    <div class="flex items-center gap-3">
                        @include('components.icons.users')
                        <span>Kelola Pengguna</span>
                    </div>

                    {{-- ARROW --}}
                    <svg :class="open ? 'rotate-90' : ''"
                        class="h-4 w-4 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- SUB --}}
                <div x-show="open" x-collapse class="ml-10 space-y-1">

                    <a href="/IndexKaryawan"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('/IndexKaryawan', $currentPath) }}">
                        <span>Karyawan</span>
                    </a>

                    <a href="/IndexPenghuni"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('/IndexPenghuni', $currentPath) }}">
                        <span>Penghuni</span>
                    </a>

                </div>
            </div>

            @elseif($user->role === 'penghuni')
            {{-- DASHBOARD --}}
            <a href="/dashboardPenghuni"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/dashboard', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            {{-- PENGAJUAN KELUHAN--}}
            <a href="/ajukanKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.form')
                <span>Ajukan Keluhan</span>
            </a>

            {{-- RIWAYAT KELUHAN --}}
            <a href="/riwayatKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>keluhan Saya</span>
            </a>
            
        @elseif($user->role === 'tenant_relation')
            {{-- DASHBOARD --}}
            <a href="/dashboardTenantRelation"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/dashboard', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>

            <!-- {{-- KELOLA KELUHAN--}}
            <a href="/ajukanKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Keluhan</span>
            </a> -->

            <!-- {{-- WORK ORDER --}}
            <a href="/kelolaWorkOrder"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.clipboardDocument')
                <span>Work Order</span>
            </a> -->

            <!-- {{-- KELOLA KELUHAN --}}
            <a href="/units"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Kelola Keluhan</span>
            </a> -->

            {{-- KELOLA KELUHAN (SUB MENU) --}}
            @php
                $userMenuOpen =
                    request()->is('keluhanMasuk*') ||
                    request()->is('daftarPenanganan*');
            @endphp

            <div x-data="{ open: {{ $userMenuOpen ? 'true' : 'false' }} }" class="space-y-1">

                {{-- PARENT --}}
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">

                    <div class="flex items-center gap-3">
                        @include('components.icons.clipboardList')
                        <span>Kelola Keluhan</span>
                    </div>

                    {{-- ARROW --}}
                    <svg :class="open ? 'rotate-90' : ''"
                        class="h-4 w-4 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- SUB --}}
                <div x-show="open" x-collapse class="ml-10 space-y-1">

                    <a href="/keluhanMasuk"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('keluhanMasuk', $currentPath) }}">
                        <span>Keluhan Masuk</span>
                    </a>

                    <a href="/daftarPenanganan"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('/daftarPenanganan', $currentPath) }}">
                        <span>Daftar Penanganan Keluhan Saya</span>
                    </a>

                </div>
            </div>

            {{-- KNOWLEDGE BASE --}}
            <a href="/kelolaWorkOrder"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.bookOpen')
                <span>Knowledge Base</span>
            </a>

            {{-- LAPORAN --}}
            <a href="/kelolaWorkOrder"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.scrollText')
                <span>Laporan</span>
            </a>

            
         @elseif($user->role === 'departemen')
             {{-- DASHBOARD --}}
            <a href="/dashboardDepartemen"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/dashboard', $currentPath) }}">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>
            
            <!-- {{-- WORK ORDER--}}
            <a href="/ajukanKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.clipboardList')
                <span>Work Order</span>
            </a>

            {{-- PENYELESAIAN WORK ORDER --}}
            <a href="/ajukanKeluhan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.clipboardDocument')
                <span>Penyelesaian Work Oder</span>
            </a> -->

            {{-- KELOLA WORK ORDER (SUB MENU) --}}
            @php
                $userMenuOpen =
                    request()->is('workOrderMasuk*') ||
                    request()->is('daftarWorkOrder*');
            @endphp

            <div x-data="{ open: {{ $userMenuOpen ? 'true' : 'false' }} }" class="space-y-1">

                {{-- PARENT --}}
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">

                    <div class="flex items-center gap-3">
                        @include('components.icons.clipboardList')
                        <span>Kelola Work Order</span>
                    </div>

                    {{-- ARROW --}}
                    <svg :class="open ? 'rotate-90' : ''"
                        class="h-4 w-4 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- SUB --}}
                <div x-show="open" x-collapse class="ml-10 space-y-1">

                    <a href="/workOrderMasuk"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('workOrderMasuk', $currentPath) }}">
                        <span>Work Order Masuk</span>
                    </a>

                    <a href="/daftarWorkOrder"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                    {{ activeMenu('/daftarWorkOrder', $currentPath) }}">
                        <span>Daftar Penanganan Work Order Saya</span>
                    </a>

                </div>
            </div>
            {{-- LAPORAN --}}
            <a href="/kelolaWorkOrder"
            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/units', $currentPath) }}">
                @include('components.icons.scrollText')
                <span>Laporan</span>
            </a>
        @endif

        {{-- PROFILE --}}
        <a href="/profile"
        class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ activeMenu('/dashboard', $currentPath) }}">
            @include('components.icons.user')
            <span>Profile</span>
        </a>
    </nav>

</aside>
