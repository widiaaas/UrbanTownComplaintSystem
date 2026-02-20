@extends('layouts.app')

@section('title', 'Detail Unit')

@section('content')
<div class="space-y-6 max-w-2xl">

    <h1 class="text-2xl font-bold">Detail Unit A-101</h1>

    <div class="bg-white p-6 rounded-lg border shadow-sm space-y-4">

        <div>
            <p class="text-sm text-gray-500">Gedung</p>
            <p class="font-medium">Tower A</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Penghuni Aktif</p>
            <p class="font-medium">Budi Santoso</p>
        </div>

        <div class="pt-4 border-t">
            <h2 class="font-semibold mb-2">Perbarui Penghuni</h2>

            <select class="w-full border rounded-lg px-3 py-2">
                <option>-- Pilih Penghuni --</option>
                <option>Siti Aminah</option>
                <option>Andi Pratama</option>
            </select>

            <div class="flex justify-end mt-4 gap-2">
                <button class="px-4 py-2 bg-gray-200 rounded-lg">
                    Nonaktifkan
                </button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
