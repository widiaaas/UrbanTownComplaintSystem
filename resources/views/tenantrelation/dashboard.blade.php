@extends('layouts.app')

@section('title', 'Dashboard Tenant Relation')

@section('content')

<div class="space-y-8">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Tenant Relation</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang, {{ $karyawan->nama ?? 'Tenant Relation' }}
        </p>
    </div>

    {{-- ========================= --}}
    {{-- STATISTIK --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ================= KELUHAN ================= --}}
        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Statistik Keluhan</h2>

            <div class="grid grid-cols-3 gap-4">

            <a href="/daftarWorkOrder?status=open" class="p-4 bg-blue-50 rounded-lg hover:shadow transition block cursor-pointer">
                <p class="text-sm">Open</p>
                <p class="text-2xl font-bold">{{ $statsKeluhan['open'] }}</p>
            </a>

            <a href="/daftarWorkOrder?status=on_progress" class="p-4 bg-yellow-50 rounded-lg hover:shadow transition block cursor-pointer">
                <p class="text-sm">On Progress</p>
                <p class="text-2xl font-bold">{{ $statsKeluhan['on_progress'] }}</p>
            </a>

            <a href="/daftarWorkOrder?status=close" class="p-4 bg-green-50 rounded-lg hover:shadow transition block cursor-pointer">
                <p class="text-sm">Close</p>
                <p class="text-2xl font-bold">{{ $statsKeluhan['close'] }}</p>
            </a>

            </div>
            </div>
        </div>

        {{-- ================= WORK ORDER ================= --}}
        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Statistik Work Order</h2>

            <div class="grid grid-cols-4 gap-4">

                <a href="/daftarWorkOrder?status=open" class="p-4 bg-blue-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Open</p>
                    <p class="text-2xl font-bold">{{ $statsWO['open'] }}</p>
                </a>

                <a href="/daftarWorkOrder?status=on_progress" class="p-4 bg-yellow-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">On Progress</p>
                    <p class="text-2xl font-bold">{{ $statsWO['on_progress'] }}</p>
                </a>

                <a href="/daftarWorkOrder?status=waiting" class="p-4 bg-orange-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Waiting</p>
                    <p class="text-2xl font-bold">{{ $statsWO['waiting'] }}</p>
                </a>

                <a href="/daftarWorkOrder?status=close" class="p-4 bg-green-50 rounded-lg hover:shadow transition block">
                    <p class="text-sm">Close</p>
                    <p class="text-2xl font-bold">{{ $statsWO['close'] }}</p>
                </a>

            </div>
        </div>

    </div>


</div>

@endsection