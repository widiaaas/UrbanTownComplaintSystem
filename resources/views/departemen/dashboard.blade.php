@extends('layouts.app')

@section('title', 'Dashboard Departemen')

@section('content')


<div class="space-y-8">

    {{-- ========================= --}}
    {{-- HEADER --}}
    {{-- ========================= --}}
    <div class="bg-white border rounded-xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        {{-- LEFT --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                📊 Dashboard Departemen
            </h1>

            <p class="text-gray-500 mt-1">
                Selamat datang, 
                <span class="font-semibold text-gray-800">
                    {{ $karyawan->nama }}
                </span>
            </p>
        </div>

        {{-- RIGHT --}}
        <div class="flex items-center gap-3">

            {{-- TANGGAL --}}
            <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
                {{ now()->format('d M Y') }}
            </div>

        </div>

    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- TOTAL Masuk --}}
        <div class="bg-white p-5 rounded-lg shadow border">
            <p class="text-sm text-gray-500">Total WO Masuk</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">
                {{ $totalWOMasuk }}
            </p>
        </div>

        <!-- {{-- OVERDUE --}}
        <div class="bg-white p-5 rounded-lg shadow border">
            <p class="text-sm text-gray-500">WO Terlambat</p>
            <p class="text-2xl font-bold text-red-600 mt-2">
                {{ $overdue }}
            </p>
        </div> -->

        {{-- PROGRESS --}}
        <div class="bg-white p-5 rounded-lg shadow border">
            <p class="text-sm text-gray-500">Progress Penyelesaian</p>
            <p class="text-2xl font-bold text-green-600 mt-2">
                {{ round($progress) }}%
            </p>

            {{-- progress bar --}}
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                <div class="bg-green-500 h-2 rounded-full"
                    style="width: {{ round($progress) }}%">
                </div>
            </div>
        </div>

    </div>

    {{-- ========================= --}}
    {{-- STATISTIK --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

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

    <div class="bg-white p-6 rounded-lg border shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Work Order Terbaru</h2>

        <div class="space-y-3">

            @forelse($recentWO as $wo)
                <div class="flex justify-between items-center border-b pb-2">

                    <div>
                        <p class="font-medium text-gray-800">
                            {{ $wo->judul ?? 'WO #' . $wo->id }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $wo->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- STATUS BADGE --}}
                    <span class="px-2 py-1 text-xs rounded
                        @if($wo->status == 'open') bg-blue-100 text-blue-600
                        @elseif($wo->status == 'on_progress') bg-yellow-100 text-yellow-600
                        @elseif($wo->status == 'waiting') bg-orange-100 text-orange-600
                        @elseif($wo->status == 'close') bg-green-100 text-green-600
                        @endif
                    ">
                        {{ ucfirst(str_replace('_',' ', $wo->status)) }}
                    </span>

                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada work order</p>
            @endforelse

        </div>
    </div>

</div>
@endsection