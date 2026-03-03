@extends('layouts.app')

@section('title', 'Dashboard Departemen')

@section('content')

@php
    // =========================
    // DUMMY USER
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
        'open' => 1,
        'on_progress' => 2,
        'waiting' => 1,
        'close' => 0,
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
            'status' => 'on_progress',
            'department' => 'Maintenance',
            'date' => '2026-02-10',
        ],
        [
            'wo' => 'WO-002',
            'ticket' => 'CMP-002',
            'title' => 'Ganti Lampu',
            'description' => 'Penggantian lampu kamar mandi.',
            'status' => 'waiting',
            'department' => 'Electrical',
            'date' => '2026-02-09',
        ],
    ];

    // =========================
    // HELPER BADGE STATUS WO
    // =========================
    function badgeWO($status) {
        $map = [
            'open' => ['Open', 'bg-blue-100 text-blue-700'],
            'on_progress' => ['On Progress', 'bg-yellow-100 text-yellow-700'],
            'waiting' => ['Waiting', 'bg-orange-100 text-orange-700'],
            'close' => ['Close', 'bg-green-100 text-green-700'],
        ];

        [$label, $class] = $map[$status] ?? ['Unknown', 'bg-gray-100 text-gray-700'];

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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="p-4 rounded-lg bg-blue-50">
                <p class="text-sm text-gray-600">Total</p>
                <p class="text-2xl font-bold">{{ $statsWO['total'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-blue-50">
                <p class="text-sm text-gray-600">Open</p>
                <p class="text-2xl font-bold">{{ $statsWO['open'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-yellow-50">
                <p class="text-sm text-gray-600">On Progress</p>
                <p class="text-2xl font-bold">{{ $statsWO['on_progress'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-orange-50">
                <p class="text-sm text-gray-600">Waiting</p>
                <p class="text-2xl font-bold">{{ $statsWO['waiting'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-green-50">
                <p class="text-sm text-gray-600">Close</p>
                <p class="text-2xl font-bold">{{ $statsWO['close'] }}</p>
            </div>
        </div>
    </div>
<!-- 
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
                            {!! badgeWO($wo['status']) !!}
                        </div>

                        <p class="font-medium">{{ $wo['title'] }}</p>
                        <p class="text-sm text-gray-600">{{ $wo['description'] }}</p>

                        <p class="text-xs text-gray-500 mt-1">
                            Ticket {{ $wo['ticket'] }}
                            • {{ $wo['department'] }}
                            • {{ $wo['date'] }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Tidak ada work order
                </div>
            @endforelse
        </div>
    </div> -->

</div>
@endsection