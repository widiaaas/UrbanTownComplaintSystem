@php
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
    ];

    function badge($label, $type) {
        $map = [
            'baru' => 'bg-blue-100 text-blue-700',
            'diproses' => 'bg-yellow-100 text-yellow-700',
            'selesai' => 'bg-green-100 text-green-700',
            'tinggi' => 'bg-red-100 text-red-700',
            'sedang' => 'bg-orange-100 text-orange-700',
        ];
        return "<span class='px-2 py-1 rounded-full text-xs {$map[$type]}'>$label</span>";
    }
@endphp

{{-- STATISTIK --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-white p-6 rounded-lg border">
        <h2 class="font-semibold mb-4">Statistik Keluhan</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 p-4 rounded">Total {{ $statsKeluhan['total'] }}</div>
            <div class="bg-yellow-50 p-4 rounded">Aktif {{ $statsKeluhan['active'] }}</div>
            <div class="bg-orange-50 p-4 rounded">Pending {{ $statsKeluhan['pending'] }}</div>
            <div class="bg-green-50 p-4 rounded">Selesai {{ $statsKeluhan['completed'] }}</div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg border">
        <h2 class="font-semibold mb-4">Statistik Work Order</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 p-4 rounded">Total {{ $statsWO['total'] }}</div>
            <div class="bg-yellow-50 p-4 rounded">Aktif {{ $statsWO['active'] }}</div>
            <div class="bg-orange-50 p-4 rounded">Pending {{ $statsWO['pending'] }}</div>
            <div class="bg-green-50 p-4 rounded">Selesai {{ $statsWO['completed'] }}</div>
        </div>
    </div>
</div>

{{-- KELUHAN TERBARU --}}
<div class="bg-white rounded-lg border mt-6">
    <div class="p-6 border-b font-semibold">Keluhan Terbaru</div>

    @foreach($complaints as $c)
        <div class="p-6 border-b">
            <div class="flex gap-2 mb-1">
                <strong>{{ $c['ticket'] }}</strong>
                {!! badge($c['status'], $c['status']) !!}
                {!! badge($c['priority'], $c['priority']) !!}
            </div>
            <p>{{ $c['title'] }}</p>
            <p class="text-sm text-gray-600">{{ $c['description'] }}</p>
        </div>
    @endforeach
</div>
