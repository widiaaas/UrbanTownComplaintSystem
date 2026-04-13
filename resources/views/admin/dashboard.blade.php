@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang, {{ $karyawan->nama ?? 'Admin' }}
        </p>
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

    {{-- ========================= --}}
    {{-- AKTIVITAS TERBARU (OPSIONAL) --}}
    {{-- ========================= --}}
    @if(isset($activities) && count($activities) > 0)
    <div class="bg-white rounded-lg border shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Aktivitas Terbaru</h2>
        </div>

        <div class="divide-y">
            @foreach($activities as $a)
                <div class="p-6">
                    <p class="font-medium">{{ $a['title'] }}</p>
                    <p class="text-sm text-gray-500">{{ $a['time'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection