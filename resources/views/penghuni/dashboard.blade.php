@extends('layouts.app')

@section('title', 'Dashboard Penghuni')

@section('content')

@php
    // =========================
    // DUMMY USER (SIMULASI AUTH)
    // =========================
    $user = [
        'name' => 'Widiawati',
        'role' => 'penghuni',
    ];

    // =========================
    // STATISTIK KELUHAN PENGHUNI
    // =========================
    $stats = [
        'total' => 6,
        'active' => 3,
        'completed' => 2,
        'pending' => 1,
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

    // =========================
    // HELPER BADGE (TAILWIND SAFE)
    // =========================
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

<div class="space-y-6">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Penghuni</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang, {{ $user['name'] }}
        </p>
    </div>

    {{-- ========================= --}}
    {{-- STATISTIK --}}
    {{-- ========================= --}}
    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Statistik Keluhan</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-lg bg-blue-50">
                <p class="text-sm text-gray-600">Total</p>
                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-yellow-50">
                <p class="text-sm text-gray-600">Aktif</p>
                <p class="text-2xl font-bold">{{ $stats['active'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-orange-50">
                <p class="text-sm text-gray-600">Pending</p>
                <p class="text-2xl font-bold">{{ $stats['pending'] }}</p>
            </div>

            <div class="p-4 rounded-lg bg-green-50">
                <p class="text-sm text-gray-600">Selesai</p>
                <p class="text-2xl font-bold">{{ $stats['completed'] }}</p>
            </div>
        </div>
    </div> 

    {{-- ========================= --}}
    {{-- AKSI --}}
    {{-- ========================= --}}
    <div class="flex justify-end">
        <a href="{{ url('/new-complaint') }}"
           class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Ajukan Keluhan
        </a>
    </div>
</div>
@endsection
