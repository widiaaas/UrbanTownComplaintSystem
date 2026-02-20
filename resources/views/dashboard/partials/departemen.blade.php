@php
    $statsWO = [
        'total'=>4,'active'=>2,'pending'=>1,'completed'=>1
    ];

    $workOrders = [
        ['wo'=>'WO-001','title'=>'Perbaikan AC','status'=>'dalam_pengerjaan','priority'=>'tinggi'],
        ['wo'=>'WO-002','title'=>'Ganti Lampu','status'=>'pending','priority'=>'sedang'],
    ];
@endphp

{{-- STATISTIK WO --}}
<div class="bg-white p-6 rounded-lg border shadow-sm">
    <h2 class="text-lg font-semibold mb-4">Statistik Work Order</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($statsWO as $label=>$val)
            <div class="p-4 rounded-lg bg-gray-50">
                <p class="text-sm text-gray-600 capitalize">{{ $label }}</p>
                <p class="text-2xl font-bold">{{ $val }}</p>
            </div>
        @endforeach
    </div>
</div>

{{-- LIST WO --}}
<div class="bg-white rounded-lg border shadow-sm">
    <div class="p-6 border-b font-semibold">Work Order Terbaru</div>
    <div class="divide-y">
        @foreach($workOrders as $wo)
            <div class="p-6">
                <p class="font-semibold">{{ $wo['wo'] }}</p>
                <p class="text-sm text-gray-600">{{ $wo['title'] }}</p>
            </div>
        @endforeach
    </div>
</div>
