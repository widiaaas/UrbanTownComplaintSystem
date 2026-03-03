@extends('layouts.app')

@section('title', 'Kelola Penghuni')

@section('content')

<div x-data="{ 
        openEdit:false, 
        openDetail:false, 
        openCreate:false,
        selectedTenant:null 
    }" class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Penghuni</h1>
        <button 
            @click="openCreate = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Tambah Penghuni
        </button>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari Penghuni / Unit</label>
            <input type="text" 
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                <option value="">Semua</option>
                <option>Aktif</option>
                <option>Nonaktif</option>
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
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Nama Penghuni</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Unit</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Email</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Telepon</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Status</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 text-center align-middle">
                    <td class="px-4 py-2">Widiawati</td>
                    <td class="px-4 py-2">A-101</td>
                    <td class="px-4 py-2">widiawati@example.com</td>
                    <td class="px-4 py-2">081234567890</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
                    </td>
                    <td class="px-4 py-2 flex justify-center gap-1 flex-wrap">
                        <button class="px-2 py-1 bg-blue-400 text-white rounded hover:bg-blue-500 text-xs"
                            @click="openDetail=true; selectedTenant='Widiawati'">Detail</button>
                        <button class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs"
                            @click="openEdit=true; selectedTenant='Widiawati'">Edit</button>
                        <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs"
                            @click="alert('Hapus penghuni: Widiawati')">Hapus</button>
                    </td>
                </tr>

                {{-- Tambahkan data dummy lain sesuai kebutuhan --}}
            </tbody>
        </table>
    </div>

    {{-- ================= MODAL TAMBAH PENGHUNI ================= --}}
    <div x-show="openCreate" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

        <div 
            @click.outside="openCreate=false"
            class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 space-y-5">

            {{-- HEADER --}}
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    Tambah Penghuni Baru
                </h2>
                <button 
                    @click="openCreate=false"
                    class="text-gray-400 hover:text-gray-600 text-xl">
                    &times;
                </button>
            </div>

            {{-- FORM --}}
            <form class="space-y-4">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nama Penghuni
                    </label>
                    <input 
                        type="text"
                        placeholder="Nama lengkap"
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>

                {{-- Unit --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Unit
                    </label>
                    <select
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                        <option value="">-- Pilih Unit --</option>
                        <option>A-101</option>
                        <option>A-102</option>
                        <option>B-201</option>
                    </select>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Email
                    </label>
                    <input 
                        type="email"
                        placeholder="email@example.com"
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>

                {{-- Telepon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nomor Telepon
                    </label>
                    <input 
                        type="text"
                        placeholder="08xxxxxxxxxx"
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Status
                    </label>
                    <select
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                {{-- FOOTER --}}
                <div class="flex justify-end gap-3 pt-6 border-t">
                    <button
                        type="button"
                        @click="openCreate=false"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
    {{-- ================= MODAL DETAIL PENGHUNI ================= --}}
    <div x-show="openDetail" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openDetail=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Detail Penghuni (<span x-text="selectedTenant"></span>)</h2>
            <div class="space-y-2 text-sm">
                <p><strong>Nama:</strong> Widiawati</p>
                <p><strong>Unit:</strong> A-101</p>
                <p><strong>Email:</strong> widiawati@example.com</p>
                <p><strong>Telepon:</strong> 081234567890</p>
                <p><strong>Status:</strong> <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span></p>
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <button class="btn" @click="openDetail=false">Tutup</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT PENGHUNI ================= --}}
    <div x-show="openEdit" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openEdit=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Edit Penghuni (<span x-text="selectedTenant"></span>)</h2>
            <div class="space-y-4">
                <div>
                    <label class="label">Nama</label>
                    <input type="text" class="input input-bordered w-full" value="Widiawati">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Unit
                    </label>

                    <input 
                        list="unitList"
                        value="A-101"
                        placeholder="Ketik atau pilih unit"
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">

                    <datalist id="unitList">
                        <option value="A-101">
                        <option value="A-102">
                        <option value="B-201">
                        <option value="B-202">
                    </datalist>
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" class="input input-bordered w-full" value="widiawati@example.com">
                </div>
                <div>
                    <label class="label">Telepon</label>
                    <input type="text" class="input input-bordered w-full" value="081234567890">
                </div>
                <div>
                <label class="block text-sm font-medium text-gray-700">
                    Status Penghuni
                </label>
                <select 
                    class="w-full mt-1 border rounded-lg px-3 py-2
                           focus:ring focus:ring-blue-200 focus:border-blue-500">
                    <option value="aktif" selected>Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button class="btn" @click="openEdit=false">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
