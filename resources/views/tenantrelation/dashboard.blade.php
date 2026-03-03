@extends('layouts.app')

@section('title', 'Dashboard Tenant Relation')

@section('content')

@php
    // =========================
    // DUMMY USER (SIMULASI AUTH)
    // =========================
    $user = [
        'name' => 'Jun',
        'role' => 'tenant_relation',
    ];

    // =========================
    // STATISTIK
    // =========================
    $statsKeluhan = [
        'total' => 6,
        'active' => 3,
        'pending' => 1,
        'completed' => 2,
    ];

    $statsWO = [
        'total' => 4,
        'active' => 2,
        'pending' => 1,
        'completed' => 1,
    ];

    // =========================
    // KELUHAN TERBARU
    // =========================
    $complaints = [
        [
            'ticket' => 'CMP-001',
            'title' => 'AC Tidak Dingin',
            'description' => 'AC ruang tamu tidak mengeluarkan udara dingin.',
            'status' => 'diproses',
            'priority' => 'tinggi',
            'unit' => 'A-101',
            'building' => 'Tower A',
            'date' => '2026-02-10',
        ],
        [
            'ticket' => 'CMP-002',
            'title' => 'Lampu Mati',
            'description' => 'Lampu kamar mandi mati.',
            'status' => 'baru',
            'priority' => 'sedang',
            'unit' => 'A-101',
            'building' => 'Tower A',
            'date' => '2026-02-09',
        ],
    ];

    function badge($label, $type) {
        $map = [
            'baru' => 'bg-blue-100 text-blue-700',
            'diproses' => 'bg-yellow-100 text-yellow-700',
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
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Tenant Relation</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang, {{ $user['name'] }}
        </p>
    </div>

    {{-- ========================= --}}
    {{-- STATISTIK --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- STAT KELUHAN --}}
        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Statistik Keluhan</h2>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-lg bg-blue-50">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold">{{ $statsKeluhan['total'] }}</p>
                </div>

                <div class="p-4 rounded-lg bg-yellow-50">
                    <p class="text-sm text-gray-600">Aktif</p>
                    <p class="text-2xl font-bold">{{ $statsKeluhan['active'] }}</p>
                </div>

                <div class="p-4 rounded-lg bg-orange-50">
                    <p class="text-sm text-gray-600">Menunggu</p>
                    <p class="text-2xl font-bold">{{ $statsKeluhan['pending'] }}</p>
                </div>

                <div class="p-4 rounded-lg bg-green-50">
                    <p class="text-sm text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold">{{ $statsKeluhan['completed'] }}</p>
                </div>
            </div>
        </div>

        {{-- STAT WORK ORDER --}}
        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Statistik Work Order</h2>

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-lg bg-blue-50">
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold">{{ $statsWO['total'] }}</p>
                </div>

                <div class="p-4 rounded-lg bg-yellow-50">
                    <p class="text-sm text-gray-600">Aktif</p>
                    <p class="text-2xl font-bold">{{ $statsWO['active'] }}</p>
                </div>

                <div class="p-4 rounded-lg bg-orange-50">
                    <p class="text-sm text-gray-600">Menunggu</p>
                    <p class="text-2xl font-bold">{{ $statsWO['pending'] }}</p>
                </div>

                <div class="p-4 rounded-lg bg-green-50">
                    <p class="text-sm text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold">{{ $statsWO['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- {{-- ========================= --}}
    {{-- KELUHAN TERBARU --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-lg border shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Keluhan Terbaru</h2>
        </div>

        <div class="divide-y">
            @forelse($complaints as $c)
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold">{{ $c['ticket'] }}</span>
                                {!! badge($c['status'], $c['status']) !!}
                                {!! badge($c['priority'], $c['priority']) !!}
                            </div>
                            <p class="font-medium">{{ $c['title'] }}</p>
                            <p class="text-sm text-gray-600">{{ $c['description'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Unit {{ $c['unit'] }} - {{ $c['building'] }} • {{ $c['date'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Tidak ada keluhan
                </div>
            @endforelse
        </div>
    </div> -->

</div>
@endsection
