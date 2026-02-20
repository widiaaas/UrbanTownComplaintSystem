@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

@php
    $admin = [
        'name' => 'Admin Sistem',
    ];

    $stats = [
        'unit' => 120,
        'penghuni' => 356,
        'karyawan' => 28,
    ];

    $activities = [
        [
            'title' => 'Unit A-101 didaftarkan',
            'time' => '2026-02-12 10:15',
        ],
        [
            'title' => 'Data penghuni Unit B-203 diperbarui',
            'time' => '2026-02-11 14:32',
        ],
    ];
@endphp

<div class="space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600 mt-1">
            Selamat datang, {{ $admin['name'] }}
        </p>
    </div>

    {{-- ========================= --}}
    {{-- STATISTIK (HORIZONTAL) --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <p class="text-sm text-gray-500">Total Unit</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $stats['unit'] }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <p class="text-sm text-gray-500">Penghuni Aktif</p>
            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $stats['penghuni'] }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg border shadow-sm">
            <p class="text-sm text-gray-500">Karyawan</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">
                {{ $stats['karyawan'] }}
            </p>
        </div>

    </div>

    {{-- ========================= --}}
    {{-- AKTIVITAS TERBARU --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-lg border shadow-sm">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Aktivitas Terbaru</h2>
        </div>

        <div class="divide-y">
            @forelse($activities as $a)
                <div class="p-6">
                    <p class="font-medium">{{ $a['title'] }}</p>
                    <p class="text-sm text-gray-500">{{ $a['time'] }}</p>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">
                    Belum ada aktivitas
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
