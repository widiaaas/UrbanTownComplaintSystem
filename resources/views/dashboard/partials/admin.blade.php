@php
    $stats = [
        'unit' => 120,
        'penghuni' => 356,
        'karyawan' => 28,
    ];

    $activities = [
        ['title'=>'Unit A-101 didaftarkan','time'=>'2026-02-12 10:15'],
        ['title'=>'Data penghuni Unit B-203 diperbarui','time'=>'2026-02-11 14:32'],
    ];
@endphp

{{-- STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @include('dashboard.stat', [
        'title' => 'Total Unit',
        'value' => $stats['unit'],
        'color' => 'blue'
    ])

    @include('dashboard.stat', [
        'title' => 'Penghuni Aktif',
        'value' => $stats['penghuni'],
        'color' => 'green'
    ])

    @include('dashboard.stat', [
        'title' => 'Karyawan',
        'value' => $stats['karyawan'],
        'color' => 'purple'
    ])
</div>


{{-- AKTIVITAS --}}
<div class="bg-white rounded-lg border shadow-sm">
    <div class="p-6 border-b font-semibold">Aktivitas Terbaru</div>
    <div class="divide-y">
        @forelse($activities as $a)
            <div class="p-6">
                <p class="font-medium">{{ $a['title'] }}</p>
                <p class="text-sm text-gray-500">{{ $a['time'] }}</p>
            </div>
        @empty
            <p class="p-6 text-center text-gray-500">Belum ada aktivitas</p>
        @endforelse
    </div>
</div>
