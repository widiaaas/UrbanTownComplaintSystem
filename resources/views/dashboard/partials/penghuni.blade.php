@php
    $stats = [
        'total' => 6,
        'active' => 3,
        'pending' => 1,
        'completed' => 2,
    ];
@endphp

{{-- STATISTIK --}}
<div class="bg-white p-6 rounded-lg border">
    <h2 class="font-semibold mb-4">Statistik Keluhan Saya</h2>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded">Total {{ $stats['total'] }}</div>
        <div class="bg-yellow-50 p-4 rounded">Aktif {{ $stats['active'] }}</div>
        <div class="bg-orange-50 p-4 rounded">Pending {{ $stats['pending'] }}</div>
        <div class="bg-green-50 p-4 rounded">Selesai {{ $stats['completed'] }}</div>
    </div>
</div>

{{-- AKSI --}}
<div class="flex justify-end mt-4">
    <a href="/ajukanKeluhan"
       class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        + Ajukan Keluhan
    </a>
</div>
