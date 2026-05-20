@php

    use Illuminate\Support\Facades\Auth;

    // USER LOGIN
    $user = Auth::user();

    $currentPath = request()->path();

    // DATA KARYAWAN
    $karyawan = $user?->karyawan;

    // DEPARTEMEN
    $departemen =
        $karyawan?->departemen?->nama_departemen;

    /*
    |--------------------------------------------------------------------------
    | ROLE SYSTEM
    |--------------------------------------------------------------------------
    */
    $isAdmin =
        $user?->role === 'admin';

    $isTenantRelation =
        $user?->role === 'tenant_relation';

    $isDepartemen =
        $user?->role === 'departemen';

    $isUnit =
        $user?->role === 'unit';

    /*
    |--------------------------------------------------------------------------
    | ACTIVE MENU
    |--------------------------------------------------------------------------
    */
    function activeMenu($path, $currentPath)
    {
        return request()->is($path)
            ? 'bg-blue-50 text-blue-700 shadow-sm'
            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
    }

@endphp

{{-- ================= MOBILE OVERLAY ================= --}}
<div
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    class="fixed inset-0 z-40
    bg-black/40 backdrop-blur-[1px]
    lg:hidden">
</div>

{{-- ================= SIDEBAR ================= --}}
<aside

    :class="
        sidebarOpen
        ? 'translate-x-0'
        : '-translate-x-full lg:translate-x-0'
    "

    class="
    fixed lg:relative
    inset-y-0 left-0
    z-50

    w-72
    shrink-0

    bg-white/95 backdrop-blur-xl
    border-r border-gray-200/80

    shadow-xl lg:shadow-none

    transition-transform duration-300 ease-in-out

    flex flex-col
    ">

    {{-- CONTAINER --}}
    <div class="flex flex-col h-full px-5 py-5">

        {{-- ================= LOGO ================= --}}
        <a
            href="/"
            class="flex items-center gap-3
            pb-6 mb-4 border-b border-gray-100">

            {{-- LOGO --}}
            <div
                class="w-18 h-18 rounded-2xl
                
                flex items-center justify-center">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="h-8 w-8 object-contain">

            </div>

            {{-- TEXT --}}
            <div class="leading-tight">

                <h1
                    class="font-black text-xl
                    tracking-tight text-gray-900">

                    Unit Complaint

                </h1>

                <p
                    class="text-sm text-gray-500
                    mt-0.5">

                    Sistem Keluhan Unit

                </p>

            </div>

        </a>

        {{-- ================= MENU ================= --}}
        <nav
            class="flex-1 overflow-y-auto
            space-y-1 pr-1">

        {{-- ===================================================== --}}
        {{-- ======================= ADMIN ======================= --}}
        {{-- ===================================================== --}}
        @if($isAdmin)

            {{-- DASHBOARD --}}
            <a
                href="/dashboard"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('dashboard', $currentPath) }}">

                @include('components.icons.dashboard')

                <span>Dashboard</span>

            </a>

            {{-- UNIT --}}
            <a
                href="/IndexUnits"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('IndexUnits*', $currentPath) }}">

                @include('components.icons.building')

                <span>Kelola Unit</span>

            </a>

            {{-- USER MENU --}}
            @php

                $userMenuOpen =
                    request()->is('IndexKaryawan*') ||
                    request()->is('IndexPenghuni*');

            @endphp

            <div
                x-data="{ open: {{ $userMenuOpen ? 'true' : 'false' }} }"
                class="space-y-1">

                {{-- BUTTON --}}
                <button
                    @click="open = !open"
                    class="w-full flex items-center
                    justify-between
                    px-4 py-3 rounded-2xl
                    text-sm font-semibold
                    text-gray-600
                    hover:bg-gray-100
                    hover:text-gray-900
                    transition-all">

                    <div class="flex items-center gap-3">

                        @include('components.icons.users')

                        <span>
                            Kelola Pengguna
                        </span>

                    </div>

                    {{-- ARROW --}}
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 transition-transform"
                        :class="open ? 'rotate-90' : ''"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"/>

                    </svg>

                </button>

                {{-- SUB MENU --}}
                <div
                    x-show="open"
                    x-collapse
                    class="ml-6 mt-1
                    border-l border-gray-200
                    pl-4 space-y-1">

                    <a
                        href="/IndexKaryawan"
                        class="block px-3 py-2
                        rounded-xl text-sm
                        transition-all
                        {{ activeMenu('IndexKaryawan*', $currentPath) }}">

                        Karyawan

                    </a>

                    <a
                        href="/IndexPenghuni"
                        class="block px-3 py-2
                        rounded-xl text-sm
                        transition-all
                        {{ activeMenu('IndexPenghuni*', $currentPath) }}">

                        Penghuni

                    </a>

                </div>

            </div>

            {{-- RIWAYAT HUNIAN --}}
            <a
                href="/riwayat-hunian"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('riwayat-hunian*', $currentPath) }}">

                @include('components.icons.clipboardClock')

                <span>Riwayat Hunian</span>

            </a>

            {{-- PROFILE --}}
            <a
                href="/profile"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('profile*', $currentPath) }}">

                @include('components.icons.user')

                <span>Profile</span>

            </a>

        {{-- ===================================================== --}}
        {{-- ======================== UNIT ======================= --}}
        {{-- ===================================================== --}}
        @elseif($isUnit)

            <a
                href="/ajukanKeluhan"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('ajukanKeluhan*', $currentPath) }}">

                @include('components.icons.form')

                <span>Ajukan Keluhan</span>

            </a>

            <a
                href="/riwayatKeluhan"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('riwayatKeluhan*', $currentPath) }}">

                @include('components.icons.clipboardClock')

                <span>Keluhan Saya</span>

            </a>

            <a
                href="/profile"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('profile*', $currentPath) }}">

                @include('components.icons.user')

                <span>Profile</span>

            </a>

        {{-- ===================================================== --}}
        {{-- ================= TENANT RELATION =================== --}}
        {{-- ===================================================== --}}
        @elseif($isTenantRelation)

            <a
                href="/dashboard"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('dashboard', $currentPath) }}">

                @include('components.icons.dashboard')

                <span>Dashboard</span>

            </a>

            <a
                href="/keluhan-masuk"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('keluhan-masuk*', $currentPath) }}">

                @include('components.icons.clipboardPaste')

                <span>Keluhan Masuk</span>

            </a>

            <a
                href="/daftar-penanganan"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('daftar-penanganan*', $currentPath) }}">

                @include('components.icons.clipboardList')

                <span>Penanganan Saya</span>

            </a>

            <a
                href="/riwayat-keluhan"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('riwayat-keluhan*', $currentPath) }}">

                @include('components.icons.clipboardClock')

                <span>Riwayat Keluhan</span>

            </a>

            <a
                href="/profile"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('profile*', $currentPath) }}">

                @include('components.icons.user')

                <span>Profile</span>

            </a>

        {{-- ===================================================== --}}
        {{-- ==================== DEPARTEMEN ===================== --}}
        {{-- ===================================================== --}}
        @elseif($isDepartemen)

            <a
                href="/dashboard"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('dashboard', $currentPath) }}">

                @include('components.icons.dashboard')

                <span>Dashboard</span>

            </a>

            <a
                href="/work-order-masuk"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('work-order-masuk*', $currentPath) }}">

                @include('components.icons.clipboardPaste')

                <span>Work Order Masuk</span>

            </a>

            <a
                href="/daftar-work-order"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('daftar-work-order*', $currentPath) }}">

                @include('components.icons.clipboardList')

                <span>Penanganan Saya</span>

            </a>

            <a
                href="/riwayat-work-order"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('riwayat-work-order*', $currentPath) }}">

                @include('components.icons.clipboardClock')

                <span>Riwayat Work Order</span>

            </a>

            <a
                href="/profile"
                class="flex items-center gap-3
                px-4 py-3 rounded-2xl
                text-sm font-semibold
                transition-all
                {{ activeMenu('profile*', $currentPath) }}">

                @include('components.icons.user')

                <span>Profile</span>

            </a>

        @endif

        </nav>

    </div>

</aside>