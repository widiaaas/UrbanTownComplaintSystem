@extends('layouts.app')

@section('title', 'Kelola Karyawan')

@section('content')

<div x-data="{ 
        openEdit:false, 
        openDetail:false, 
        selectedEmployee:null 
    }" class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Karyawan</h1>
        <a href="/CreateEmployee" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">+ Tambah Karyawan</a>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari Nama</label>
            <input type="text" 
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Departemen</label>
            <select class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                <option value="">Semua</option>
                <option>Admin</option>
                <option>Departemen</option>
                <option>Tenant Relation</option>
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
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Nama</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Departemen</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Status</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 text-center align-middle">
                    <td class="px-4 py-2">Widiawati Sihaloho</td>
                    <td class="px-4 py-2">Admin</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
                    </td>
                    <td class="px-4 py-2 flex justify-center gap-1 flex-wrap">
                        <button class="px-2 py-1 bg-blue-400 text-white rounded hover:bg-blue-500 text-xs"
                            @click="openDetail=true; selectedEmployee='Widiawati Sihaloho'">Detail</button>
                        <button class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs"
                            @click="openEdit=true; selectedEmployee='Widiawati Sihaloho'">Edit</button>
                        <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">Hapus</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50 text-center align-middle">
                    <td class="px-4 py-2">Budi Santoso</td>
                    <td class="px-4 py-2">Tenant Relation</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
                    </td>
                    <td class="px-4 py-2 flex justify-center gap-1 flex-wrap">
                        <button class="px-2 py-1 bg-blue-400 text-white rounded hover:bg-blue-500 text-xs"
                            @click="openDetail=true; selectedEmployee='Budi Santoso'">Detail</button>
                        <button class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs"
                            @click="openEdit=true; selectedEmployee='Budi Santoso'">Edit</button>
                        <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ================= MODAL EDIT KARYAWAN ================= --}}
    <div x-show="openEdit" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openEdit=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Edit Karyawan (<span x-text="selectedEmployee"></span>)</h2>
            <div class="space-y-4">
                <div>
                    <label class="label">Nama</label>
                    <input type="text" class="input input-bordered w-full" value="">
                </div>
                <div>
                    <label class="label">Departemen</label>
                    <select class="input input-bordered w-full">
                        <option>Admin</option>
                        <option>Departemen</option>
                        <option>Tenant Relation</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button class="btn" @click="openEdit=false">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL DETAIL KARYAWAN ================= --}}
    <div x-show="openDetail" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openDetail=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Detail Karyawan (<span x-text="selectedEmployee"></span>)</h2>
            <div class="space-y-4">
                <div>
                    <p><strong>Nama:</strong> <span x-text="selectedEmployee"></span></p>
                </div>
                <div>
                    <p><strong>Departemen:</strong> Admin</p>
                </div>
                <div>
                    <p><strong>Status:</strong> Aktif</p>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button class="btn btn-primary" @click="openDetail=false">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
