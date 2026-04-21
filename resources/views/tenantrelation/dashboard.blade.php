@extends('layouts.app')

@section('title', 'Dashboard Tenant Relation')

@section('content')

<div class="space-y-8">

    {{-- ========================= --}}
    {{-- HEADER (SAMA KONSEP DEPARTEMEN) --}}
    {{-- ========================= --}}
    <div class="bg-white border rounded-xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- LEFT --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                🏢 Dashboard Tenant Relation
            </h1>

            <p class="text-gray-500 mt-1">
                Selamat datang, 
                <span class="font-semibold text-gray-800">
                    {{ $karyawan->nama ?? 'Tenant Relation' }}
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
    {{-- SUMMARY --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- TOTAL KELUHAN MASUK--}}
        <a href="/keluhan-masuk?filter=unassigned" class="block h-full">
            <div class="bg-white p-5 rounded-lg shadow border h-full">
                <p class="text-sm text-gray-500">Total Keluhan Masuk</p>
                <p class="text-2xl font-bold text-blue-600 mt-2">
                    {{ $totalKeluhanMasuk }}
                </p>
            </div>
        </a>

        <!-- {{-- BELUM SELESAI --}}
        <a href="/daftar-penanganan?status=open,on_progress" class="block h-full">
            <div class="bg-white p-5 rounded-lg shadow border h-full">
                <p class="text-sm text-gray-500">Belum Selesai</p>
                <p class="text-2xl font-bold text-red-600 mt-2">
                    {{ $statsKeluhan['open'] + $statsKeluhan['on_progress'] }}
                </p>
            </div>
        </a> -->

        {{-- PROGRESS --}}
        <div class="bg-white p-5 rounded-lg shadow border h-full">
            <p class="text-sm text-gray-500">Progress Penyelesaian</p>
            <p class="text-2xl font-bold text-green-600 mt-2">
                {{ round(($statsKeluhan['close'] / max(1, array_sum($statsKeluhan))) * 100) }}%
            </p>

            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                <div class="bg-green-500 h-2 rounded-full"
                    style="width: {{ round(($statsKeluhan['close'] / max(1, array_sum($statsKeluhan))) * 100) }}%">
                </div>
            </div>
        </div>

    </div>

    {{-- ========================= --}}
    {{-- STATISTIK --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ================= KELUHAN ================= --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Statistik Keluhan</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                <a href="/daftar-penanganan?status=open"
                   class="p-4 bg-blue-100 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Open</p>
                    <p class="text-2xl font-bold">{{ $statsKeluhan['open'] }}</p>
                </a>

                <a href="/daftar-penanganan?status=on_progress"
                   class="p-4 bg-yellow-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">On Progress</p>
                    <p class="text-2xl font-bold">{{ $statsKeluhan['on_progress'] }}</p>
                </a>

                <a href="/daftar-penanganan?status=close"
                   class="p-4 bg-green-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Close</p>
                    <p class="text-2xl font-bold">{{ $statsKeluhan['close'] }}</p>
                </a>

            </div>
        </div>

        {{-- ================= WORK ORDER ================= --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Statistik Work Order</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <a href="/daftarWorkOrder?status=open"
                   class="p-4 bg-blue-100 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Open</p>
                    <p class="text-2xl font-bold">{{ $statsWO['open'] }}</p>
                </a>

                <a href="/daftarWorkOrder?status=on_progress"
                   class="p-4 bg-yellow-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">On Progress</p>
                    <p class="text-2xl font-bold">{{ $statsWO['on_progress'] }}</p>
                </a>

                <a href="/daftarWorkOrder?status=waiting"
                   class="p-4 bg-orange-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Waiting</p>
                    <p class="text-2xl font-bold">{{ $statsWO['waiting'] }}</p>
                </a>

                <a href="/daftarWorkOrder?status=close"
                   class="p-4 bg-green-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Close</p>
                    <p class="text-2xl font-bold">{{ $statsWO['close'] }}</p>
                </a>

            </div>
        </div>

    </div>


    {{-- ========================= --}}
    {{-- KELUHAN TERBARU (PENTING 🔥) --}}
    {{-- ========================= --}}
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Keluhan Terbaru</h2>

        <div class="space-y-3">

            @forelse($recentKeluhan as $keluhan)
                <div class="flex justify-between items-center border-b pb-2">

                    <div>
                        <p class="font-medium text-gray-800">
                            {{ $keluhan->judul ?? 'Keluhan #' . $keluhan->id }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $keluhan->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <span class="px-2 py-1 text-xs rounded
                        @if($keluhan->status == 'open') bg-blue-100 text-blue-600
                        @elseif($keluhan->status == 'on_progress') bg-yellow-100 text-yellow-600
                        @elseif($keluhan->status == 'close') bg-green-100 text-green-600
                        @endif
                    ">
                        {{ ucfirst(str_replace('_',' ', $keluhan->status)) }}
                    </span>

                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada keluhan</p>
            @endforelse

        </div>
    </div>

</div>

@endsection