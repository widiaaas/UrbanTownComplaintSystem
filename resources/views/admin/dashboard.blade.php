@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="bg-white border rounded-xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- LEFT --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                ⚙️ Dashboard Admin
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- TOTAL UNIT --}}
        <a href="/IndexUnits?status=Aktif" class="block bg-white p-6 rounded-lg border shadow-sm hover:shadow-md transition">
            <p class="text-sm text-gray-500">Total Unit</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $stats['unit'] }}
            </p>
        </a>

        {{-- PENGHUNI AKTIF --}}
        <a href="/IndexPenghuni?status=Aktif" class="block bg-white p-6 rounded-lg border shadow-sm hover:shadow-md transition">
            <p class="text-sm text-gray-500">Penghuni Aktif</p>
            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $stats['penghuni'] }}
            </p>
        </a>

        {{-- KARYAWAN --}}
        <a href="/IndexKaryawan" class="block bg-white p-6 rounded-lg border shadow-sm hover:shadow-md transition">
            <p class="text-sm text-gray-500">Karyawan</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">
                {{ $stats['karyawan'] }}
            </p>
        </a>

    </div>

</div>

@endsection