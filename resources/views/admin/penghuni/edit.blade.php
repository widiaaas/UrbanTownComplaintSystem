@extends('layouts.app')

@section('title', 'Edit Penghuni')

@section('content')
@php
    // DUMMY DATA EDIT
    $penghuni = [
        'nama' => 'Andi Wijaya',
        'email' => 'andi@mail.com',
        'no_hp' => '08123456789',
        'unit' => 'A-101',
        'status' => 'aktif',
    ];
@endphp

<div class="max-w-3xl space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold">Edit Data Penghuni</h1>
        <p class="text-gray-600 text-sm">
            Memperbarui informasi penghuni aktif
        </p>
    </div>

    {{-- FORM --}}
    <form class="bg-white p-6 rounded-lg border shadow-sm space-y-4">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="text-sm font-medium">Nama Lengkap</label>
                <input type="text"
                       value="{{ $penghuni['nama'] }}"
                       class="w-full mt-1 px-3 py-2 border rounded-lg">
            </div>

            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="email"
                       value="{{ $penghuni['email'] }}"
                       class="w-full mt-1 px-3 py-2 border rounded-lg">
            </div>

            <div>
                <label class="text-sm font-medium">No. HP</label>
                <input type="text"
                       value="{{ $penghuni['no_hp'] }}"
                       class="w-full mt-1 px-3 py-2 border rounded-lg">
            </div>

            <div>
                <label class="text-sm font-medium">Unit</label>
                <input type="text"
                       value="{{ $penghuni['unit'] }}"
                       class="w-full mt-1 px-3 py-2 border rounded-lg"
                       disabled>
                <p class="text-xs text-gray-500 mt-1">
                    Unit tidak dapat diubah
                </p>
            </div>

            <div>
                <label class="text-sm font-medium">Status Penghuni</label>
                <select class="w-full mt-1 px-3 py-2 border rounded-lg">
                    <option value="aktif" selected>Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
            </div>

        </div>

        {{-- ACTION --}}
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.penghuni.index') }}"
               class="px-4 py-2 border rounded-lg">
                Batal
            </a>
            <button type="button"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Perubahan
            </button>
        </div>

    </form>
</div>
@endsection
