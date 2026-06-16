@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="bg-white border rounded-xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- LEFT --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            @include('components.icons.userRound') Dashboard Admin
            </h1>

            <p class="text-gray-500 mt-1">
                Selamat datang, 
                <span class="font-semibold text-gray-800">
                    {{ $karyawan->nama ?? 'Admin' }}
                </span>
            </p>
        </div>

        {{-- RIGHT --}}
        <div class="flex items-center gap-3">
            <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
                {{ now()->format('d M Y') }}
            </div>
        </div>

    </div>


    {{-- ========================= --}}
    {{-- STATISTIK --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- UNIT --}}
        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Unit</h2>

            <div class="grid grid-cols-2 gap-4">

                <a href="/IndexUnits?status=Aktif"
                class="p-4 bg-green-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Aktif</p>
                    <p class="text-2xl font-bold">
                        {{ $stats['unit_aktif'] }}
                    </p>
                </a>

                <a href="/IndexUnits?status=Nonaktif"
                class="p-4 bg-red-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Nonaktif</p>
                    <p class="text-2xl font-bold">
                        {{ $stats['unit_tidak_aktif'] }}
                    </p>
                </a>

            </div>
        </div>

        {{-- PENGHUNI --}}
        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Penghuni</h2>

            <div class="grid grid-cols-2 gap-4">

                <a href="/IndexPenghuni?status=Aktif"
                class="p-4 bg-green-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Aktif</p>
                    <p class="text-2xl font-bold">
                        {{ $stats['penghuni_aktif'] }}
                    </p>
                </a>

                <a href="/IndexPenghuni?status=Nonaktif"
                class="p-4 bg-red-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Nonaktif</p>
                    <p class="text-2xl font-bold">
                        {{ $stats['penghuni_tidak_aktif'] }}
                    </p>
                </a>

            </div>
        </div>

        {{-- KARYAWAN --}}
        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Karyawan</h2>

            <div class="grid grid-cols-2 gap-4">

                <a href="/IndexKaryawan?status=Aktif"
                class="p-4 bg-green-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Aktif</p>
                    <p class="text-2xl font-bold">
                        {{ $stats['karyawan_aktif'] }}
                    </p>
                </a>

                <a href="/IndexKaryawan?status=Nonaktif"
                class="p-4 bg-red-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Nonaktif</p>
                    <p class="text-2xl font-bold">
                        {{ $stats['karyawan_tidak_aktif'] }}
                    </p>
                </a>

            </div>
        </div>

    </div>

</div>

@endsection