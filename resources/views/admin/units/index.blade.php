@extends('layouts.app')

@section('title', 'Kelola Unit')

@section('content')

<div x-data="{ 
        openEdit:false, 
        openReset:false, 
        openDeactivate:false, 
        selectedUnit:null 
    }" class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Unit</h1>
        <a href="/CreateUnits" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">+ Tambah Unit</a>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari Unit / Gedung</label>
            <input type="text" placeholder="Contoh: A-101 atau Tower A"
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Lantai</label>
            <select class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                <option value="">Semua</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>10</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filter</button>
            <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Reset</button>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Unit</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Gedung</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Lantai</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Penghuni</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Status</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 text-center align-middle">
                    <td class="px-4 py-2">A-101</td>
                    <td class="px-4 py-2">Tower A</td>
                    <td class="px-4 py-2">10</td>
                    <td class="px-4 py-2">Widiawati</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
                    </td>
                    <td class="px-4 py-2 flex justify-center gap-1 flex-wrap">
                        <button class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs"
                            @click="openEdit=true; selectedUnit='A-101'">Edit Data Unit</button>
                        <button class="px-2 py-1 bg-blue-400 text-white rounded hover:bg-blue-500 text-xs">Ganti Penghuni</button>
                        <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs"
                            @click="openDeactivate=true; selectedUnit='A-101'">Hapus</button>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>

    {{-- ================= MODAL EDIT UNIT ================= --}}
    <div x-show="openEdit" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openEdit=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Edit Data Unit (<span x-text="selectedUnit"></span>)</h2>
            <div class="space-y-4">
                <div>
                    <label class="label">Gedung</label>
                    <input type="text" class="input input-bordered w-full" value="Tower A">
                </div>
                <div>
                    <label class="label">Lantai</label>
                    <input type="number" class="input input-bordered w-full" value="10">
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button class="btn" @click="openEdit=false">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- {{-- ================= MODAL RESET PASSWORD ================= --}}
    <div x-show="openReset" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openReset=false" class="bg-white w-full max-w-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-3">Reset Password</h2>
            <p class="text-sm text-gray-600">
                Password akun unit <strong x-text="selectedUnit"></strong> akan digenerate ulang dan penghuni harus login kembali.
            </p>
            <div class="flex justify-end gap-2 mt-6">
                <button class="btn" @click="openReset=false">Batal</button>
                <button class="btn btn-warning">Reset</button>
            </div>
        </div>
    </div> -->

    <!-- {{-- ================= MODAL NONAKTIFKAN UNIT ================= --}}
    <div x-show="openDeactivate" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openDeactivate=false" class="bg-white w-full max-w-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-3 text-red-600">Nonaktifkan Unit</h2>
            <p class="text-sm text-gray-600">
                Unit <strong x-text="selectedUnit"></strong> akan dinonaktifkan karena kosong.
                Akun tidak bisa digunakan sampai diaktifkan kembali.
            </p>
            <div class="flex justify-end gap-2 mt-6">
                <button class="btn" @click="openDeactivate=false">Batal</button>
                <button class="btn btn-error">Nonaktifkan</button>
            </div>
        </div>
    </div> -->

</div>

@endsection
