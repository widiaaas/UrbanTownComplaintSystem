@extends('layouts.app')

@section('title', 'Dashboard Departemen')

@section('content')

@php
    // =========================
    // DUMMY USER (SIMULASI AUTH)
    // =========================
    $user = [ 
        'name' => 'Jun',
        'role' => 'departemen',
    ];

    // =========================
    // STATISTIK WORK ORDER
    // =========================
    $statsWO = [
        'total' => 4,
        'active' => 2,
        'pending' => 1,
        'completed' => 1,
    ];

    // =========================
    // WORK ORDER TERBARU
    // =========================
    $workOrders = [
        [
            'wo' => 'WO-001',
            'ticket' => 'CMP-001',
            'title' => 'Perbaikan AC',
            'description' => 'Cek freon dan unit indoor.',
            'status' => 'dalam_pengerjaan',
            'priority' => 'tinggi',
            'department' => 'Maintenance',
            'date' => '2026-02-10',
        ],
        [
            'wo' => 'WO-002',
            'ticket' => 'CMP-002',
            'title' => 'Ganti Lampu',
            'description' => 'Penggantian lampu kamar mandi.',
            'status' => 'pending',
            'priority' => 'sedang',
            'department' => 'Electrical',
            'date' => '2026-02-09',
        ],
    ];

    // =========================
    // HELPER BADGE
    // =========================
    function badge($label, $type) {
        $map = [
            'pending' => 'bg-orange-100 text-orange-700',
            'dalam_pengerjaan' => 'bg-yellow-100 text-yellow-700',
            'selesai' => 'bg-green-100 text-green-700',
            'tinggi' => 'bg-red-100 text-red-700',
            'sedang' => 'bg-orange-100 text-orange-700',
            'rendah' => 'bg-gray-100 text-gray-700',
        ];

        $class = $map[$type] ?? 'bg-gray-100 text-gray-700';

        return "<span class='px-2 py-1 rounded-full text-xs font-medium $class'>$label</span>";
    }
@endphp

<div class="space-y-8">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Departemen</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang, {{ $user['name'] }}
        </p>
    </div>

    {{-- ========================= --}}
    {{-- STATISTIK WORK ORDER --}}
    {{-- ========================= --}}
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Statistik Work Order</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-lg bg-blue-50">
                <p class="text-sm text-gray-600">Total</p>
                <p class="text-2xl font-bold">{{ $statsWO['total'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-yellow-50">
                <p class="text-sm text-gray-600">Aktif</p>
                <p class="text-2xl font-bold">{{ $statsWO['active'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-orange-50">
                <p class="text-sm text-gray-600">Pending</p>
                <p class="text-2xl font-bold">{{ $statsWO['pending'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-green-50">
                <p class="text-sm text-gray-600">Selesai</p>
                <p class="text-2xl font-bold">{{ $statsWO['completed'] }}</p>
            </div>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- WORK ORDER TERBARU --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-lg border shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Work Order Terbaru</h2>
        </div>

        <div class="divide-y">
            @forelse($workOrders as $wo)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold">{{ $wo['wo'] }}</span>
                            {!! badge($wo['status'], $wo['status']) !!}
                            {!! badge($wo['priority'], $wo['priority']) !!}
                        </div>

                        <p class="font-medium">{{ $wo['title'] }}</p>
                        <p class="text-sm text-gray-600">{{ $wo['description'] }}</p>

                        <p class="text-xs text-gray-500 mt-1">
                            Ticket {{ $wo['ticket'] }} • {{ $wo['department'] }} • {{ $wo['date'] }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Tidak ada work order
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
