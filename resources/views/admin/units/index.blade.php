@extends('layouts.app')

@section('title', 'Kelola Unit')

@section('content')

<div 
    x-data="{
        openEdit:false,
        openEditPenghuni:false,
        openDeactivate:false,
        selectedUnit:null,
        openCreateUnit: false
    }"
    class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Unit</h1>
        <button
            @click="openCreateUnit = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Tambah Unit
        </button>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium">Cari Unit / Gedung</label>
            <input type="text" class="w-full mt-1 border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-sm font-medium">Lantai</label>
            <select class="w-full mt-1 border rounded-lg px-3 py-2">
                <option value="">Semua</option>
                <option>1</option>
                <option>2</option>
                <option>10</option>
            </select>
        </div>
        <div class="flex gap-2 items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
            <button class="px-4 py-2 border rounded-lg">Reset</button>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center">Unit</th>
                    <th class="px-4 py-2 text-center">Gedung</th>
                    <th class="px-4 py-2 text-center">Lantai</th>
                    <th class="px-4 py-2 text-center">Penghuni</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <tr 
                x-data="{ status: 'aktif' }"
                class="text-center hover:bg-gray-50">

                <td class="px-4 py-2">A-101</td>
                <td class="px-4 py-2">Tower A</td>
                <td class="px-4 py-2">10</td>
                <td class="px-4 py-2">Widiawati</td>

                <td class="px-4 py-2">
                    <span 
                        x-show="status === 'aktif'"
                        class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
                        Aktif
                    </span>
                    <span 
                        x-show="status === 'nonaktif'"
                        class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">
                        Nonaktif
                    </span>
                </td>
                <td class="px-4 py-2 flex justify-center gap-1 flex-wrap">
                    {{-- EDIT UNIT (SELALU ADA) --}}
                    <button
                        @click="openEdit=true; selectedUnit='A-101'"
                        class="px-2 py-1 text-xs bg-yellow-400 text-white rounded">
                        Edit Unit
                    </button>

                    {{-- GANTI PENGHUNI (HANYA JIKA AKTIF) --}}
                    <template x-if="status === 'aktif'">
                        <button
                            @click="openEditPenghuni=true; selectedUnit='A-101'"
                            class="px-2 py-1 text-xs bg-blue-500 text-white rounded">
                            Ganti Penghuni
                        </button>
                    </template>

                    {{-- NONAKTIFKAN (HANYA JIKA AKTIF) --}}
                    <template x-if="status === 'aktif'">
                        <button
                            @click="openDeactivate=true; selectedUnit='A-101'"
                            class="px-2 py-1 text-xs bg-red-600 text-white rounded">
                            Nonaktifkan
                        </button>
                    </template>

                    {{-- AKTIFKAN (HANYA JIKA NONAKTIF) --}}
                    <template x-if="status === 'nonaktif'">
                        <button
                            @click="status='aktif'"
                            class="px-2 py-1 text-xs bg-green-600 text-white rounded">
                            Aktifkan
                        </button>
                    </template>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ================= MODAL TAMBAH UNIT ================= --}}
    <div 
        x-show="openCreateUnit"
        x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

        <div 
            @click.outside="openCreateUnit = false"
            class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 space-y-5">

            {{-- HEADER --}}
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    Tambah Unit Baru
                </h2>
                <button 
                    @click="openCreateUnit = false"
                    class="text-gray-400 hover:text-gray-600 text-xl">
                    &times;
                </button>
            </div>

            {{-- FORM --}}
            <form class="space-y-4">

                {{-- Nomor Unit --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nomor Unit
                    </label>
                    <input 
                        type="text"
                        placeholder="Contoh: A-101"
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>

                {{-- Gedung --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Gedung
                    </label>
                    <input 
                        type="text"
                        placeholder="Contoh: Tower A"
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>

                {{-- Lantai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Lantai
                    </label>
                    <input 
                        type="number"
                        placeholder="Contoh: 10"
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>

                {{-- Nomor Kamar --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nomor Kamar
                    </label>
                    <input 
                        type="number"
                        placeholder="Contoh: 1"
                        class="w-full mt-1 border rounded-lg px-3 py-2
                            focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>

                {{-- FOOTER --}}
                <div class="flex justify-end gap-3 pt-6 border-t">
                    <button
                        type="button"
                        @click="openCreateUnit = false"
                        class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan Unit
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- ================= MODAL EDIT UNIT ================= --}}
    <div x-show="openEdit" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openEdit=false"
            class="bg-white w-full max-w-md rounded-lg p-6 space-y-4">
            <h2 class="font-semibold text-lg">
                Edit Unit (<span x-text="selectedUnit"></span>)
            </h2>

            <div>
                <label class="text-sm">Gedung</label>
                <input type="text" class="w-full mt-1 border rounded-lg px-3 py-2" value="Tower A">
            </div>

            <div>
                <label class="text-sm">Lantai</label>
                <input type="number" class="w-full mt-1 border rounded-lg px-3 py-2" value="10">
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button @click="openEdit=false" class="px-4 py-2 border rounded-lg">Batal</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT / GANTI PENGHUNI ================= --}}
    <div 
        x-show="openEditPenghuni" 
        x-cloak
        x-data="{
            selectedPenghuniBaru: '',
            passwordGenerated: false,
            generatedPassword: 'Tmp-9XK21',
            penghuniList: [
                { id: 1, nama: 'Andi Saputra', hp: '081234567890', email: 'andi@mail.com' },
                { id: 2, nama: 'Siti Aminah', hp: '082233445566', email: 'siti@mail.com' },
                { id: 3, nama: 'Budi Santoso', hp: '083344556677', email: 'budi@mail.com' }
            ]
        }"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

        <div 
            @click.outside="openEditPenghuni=false"
            class="bg-white w-full max-w-lg rounded-xl shadow-lg 
                max-h-[90vh] flex flex-col">

            {{-- ================= HEADER ================= --}}
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">
                    Pergantian Penghuni Unit 
                    <span class="text-blue-600" x-text="selectedUnit"></span>
                </h2>
            </div>

            {{-- ================= BODY (SCROLLABLE) ================= --}}
            <div class="px-6 py-4 space-y-5 overflow-y-auto">

                {{-- INFO UNIT --}}
                <div class="bg-gray-50 border rounded-lg p-3 text-sm space-y-1">
                    <p><strong>Gedung:</strong> Tower A</p>
                    <p><strong>Lantai:</strong> 10</p>
                </div>

                {{-- PENGHUNI LAMA --}}
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-700">
                        Penghuni Aktif Saat Ini
                    </p>
                    <div class="bg-gray-50 border rounded-lg p-3 text-sm space-y-1">
                        <p><strong>Nama:</strong> Widiawati</p>
                        <p><strong>Email:</strong> widiawati@gmail.com</p>
                    </div>
                </div>

                {{-- GANTI PENGHUNI --}}
                <div class="border-t pt-4 space-y-4">
                    <h3 class="text-sm font-semibold text-red-600">
                        Pilih Penghuni Baru
                    </h3>

                    <select 
                        x-model="selectedPenghuniBaru"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Penghuni --</option>
                        <template x-for="p in penghuniList" :key="p.id">
                            <option :value="p.id" x-text="p.nama"></option>
                        </template>
                    </select>

                    <template x-if="selectedPenghuniBaru">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm space-y-1">
                            <p><strong>Nama:</strong>
                                <span x-text="penghuniList.find(p => p.id == selectedPenghuniBaru).nama"></span>
                            </p>
                            <p><strong>No. HP:</strong>
                                <span x-text="penghuniList.find(p => p.id == selectedPenghuniBaru).hp"></span>
                            </p>
                            <p><strong>Email:</strong>
                                <span x-text="penghuniList.find(p => p.id == selectedPenghuniBaru).email"></span>
                            </p>
                        </div>
                    </template>
                </div>

                {{-- PASSWORD INFO --}}
                <template x-if="passwordGenerated">
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                        <p class="font-semibold text-yellow-800">
                            Password Sementara Penghuni Baru
                        </p>
                        <div class="bg-white border rounded px-3 py-2 font-mono text-center">
                            <span x-text="generatedPassword"></span>
                        </div>
                    </div>
                </template>

            </div>

            {{-- ================= FOOTER ================= --}}
            <div class="px-6 py-4 border-t flex justify-end gap-2 bg-white">
                <button 
                    @click="openEditPenghuni=false"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                    Batal
                </button>

                <button 
                    @click="passwordGenerated = true"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Pergantian
                </button>
            </div>

        </div>
    </div>

    {{-- ================= MODAL NONAKTIFKAN UNIT ================= --}}
    <div x-show="openDeactivate" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openDeactivate=false"
            class="bg-white w-full max-w-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-red-600">Nonaktifkan Unit</h2>
            <p class="text-sm text-gray-600 mt-2">
                Unit <strong x-text="selectedUnit"></strong> akan dinonaktifkan.
            </p>

            <div class="flex justify-end gap-2 mt-6">
                <button @click="openDeactivate=false"
                    class="px-4 py-2 border rounded-lg">
                    Batal
                </button>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Nonaktifkan
                </button>
            </div>
        </div>
    </div>

</div>

@endsection