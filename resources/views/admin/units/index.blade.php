@extends('layouts.app')

@section('title', 'Kelola Unit')

@section('content')

<div x-data="unitManager()" x-init='init(@json($units))' class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Unit</h1>
        <button
        @click=" createdUnit = null;
                openCreateUnit = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Tambah Unit
        </button>
    </div>

    {{-- ================= FILTER ================= --}}
    <div
        class="bg-white rounded-lg shadow p-4
        flex flex-col md:flex-row gap-4">

        {{-- SEARCH --}}
        <div class="flex-1">

            <label
                class="text-sm font-medium text-gray-700">

                Cari Unit

            </label>

            <input
                type="text"
                x-model="search"
                @input="applyFilter"
                placeholder="Cari nomor unit atau nama gedung..."
                class="w-full mt-1 border rounded-lg px-3 py-2
                focus:ring focus:ring-blue-200">

        </div>

        {{-- FILTER STATUS --}}
        <div class="md:w-60">

            <label
                class="text-sm font-medium text-gray-700">

                Status

            </label>

            <select
                x-model="statusFilter"
                @change="applyFilter"
                class="w-full mt-1 border rounded-lg px-3 py-2
                bg-white focus:ring focus:ring-blue-200">

                <option value="">
                    Semua
                </option>

                <option value="Aktif">
                    Aktif
                </option>

                <option value="Nonaktif">
                    Nonaktif
                </option>

            </select>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div
    class="bg-white rounded-2xl shadow-sm
    border border-gray-100
    overflow-visible">

    <div class="overflow-x-auto overflow-y-visible">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                <th class="px-4 py-2 text-center">No</th>
                <th class="px-4 py-2 text-center">No Unit</th>
                <th class="px-4 py-2 text-center">Gedung</th>
                <th class="px-4 py-2 text-center">Lantai</th>
                <th class="px-4 py-2 text-center">No Kamar</th>
                <th class="px-4 py-2 text-center">Penghuni</th>
                <th class="px-4 py-2 text-center">Status</th>
                <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>

                {{-- DATA TIDAK ADA --}}
                <template x-if="!filteredUnits.length">
                    <tr>
                        <td
                            colspan="8"
                            class="px-4 py-4 text-center text-gray-400 italic">
                            Data unit tidak tersedia
                        </td>
                    </tr>
                </template>

                <template x-for="(unit, index) in filteredUnits" :key="unit.id">
                <tr class="text-center hover:bg-gray-50">
                    <!-- NO -->
                    <td class="px-4 py-2" x-text="index + 1"></td>

                    <!-- NO UNIT -->
                    <td class="px-4 py-2" x-text="unit.nomor_unit"></td>

                    <!-- GEDUNG -->
                    <td class="px-4 py-2" x-text="unit.gedung"></td>

                    <!-- LANTAI -->
                    <td class="px-4 py-2" x-text="unit.lantai"></td>

                    <!-- NOMOR KAMAR -->
                    <td class="px-4 py-2" x-text="unit.nomor_kamar"></td>

                    <!-- PENGHUNI -->
                    <td class="px-4 py-2" x-text="unit.penghuni_aktif?.penghuni?.nama || '-'"></td>

                    <!-- STATUS -->
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded"
                            :class="unit.status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                            x-text="unit.status"></span>
                    </td>

                    <!-- AKSI -->
                    <td class="px-4 py-2 relative">
                        
                            <div x-data="dropdownMenu(unit)" class="relative inline-block text-left">

                                <!-- BUTTON -->
                                <button @click="open = !open"
                                class="px-3 py-1.5 text-xs
                                    bg-gray-100 text-gray-700
                                    rounded-lg border border-gray-200
                                    hover:bg-gray-200
                                    transition flex items-center gap-1"
                                    >
                                    Aksi <span class="text-xs">▼</span>
                                </button>

                                <!-- DROPDOWN -->
                                <div
                                    x-show="open"
                                    x-cloak
                                    x-transition
                                    @click.outside="open = false"
                                    x-ref="menu"

                                    class="absolute right-0 top-full mt-2 w-56
                                        bg-white/95 backdrop-blur-sm
                                        border border-gray-100
                                        rounded-2xl shadow-2xl
                                        z-[99999]
                                        max-h-[320px] overflow-y-auto
                                        overflow-hidden">

                                        
                                    {{-- ================= MENU UTAMA ================= --}}
                                    <div class="py-1.5">

                                        {{-- EDIT --}}
                                        <button
                                            @click="editUnit()"
                                            class="w-full flex items-center gap-3
                                            px-3 py-2 text-sm text-gray-700
                                            hover:bg-blue-50 transition-all duration-150">

                                            @include('components.buttons.btn-edit')

                                            <span class="font-medium">
                                                Edit Unit
                                            </span>

                                        </button>

                                        <template x-if="status == 'Aktif'">

                                            <div>

                                                {{-- UPDATE PENGHUNI --}}
                                                <button
                                                    @click="gantiPenghuni()"
                                                    class="w-full flex items-center gap-3
                                                    px-3 py-2 text-sm text-gray-700
                                                    hover:bg-indigo-50 transition-all duration-150">

                                                    @include('components.buttons.btn-update-penghuni')

                                                    <span class="font-medium">
                                                        Perbarui Penghuni
                                                    </span>

                                                </button>

                                                {{-- RESET PASSWORD --}}
                                                <button
                                                    @click="resetPassword()"
                                                    class="w-full flex items-center gap-3
                                                    px-3 py-2 text-sm text-gray-700
                                                    hover:bg-amber-50 transition-all duration-150">

                                                    @include('components.buttons.btn-reset')

                                                    <span class="font-medium">
                                                        Reset Kata Sandi
                                                    </span>

                                                </button>

                                            </div>

                                        </template>

                                    </div>

                                    {{-- ================= STATUS ACTION ================= --}}
                                    <div class="border-t border-gray-100 py-1.5">

                                        <template x-if="unit.status == 'Aktif'">

                                            <button
                                                @click="toggleStatus('nonaktif')"
                                                class="w-full flex items-center gap-3
                                                px-3 py-2 text-sm text-orange-600
                                                hover:bg-orange-50 transition-all duration-150">

                                                @include('components.buttons.btn-nonactive')

                                                <span class="font-semibold">
                                                    Nonaktifkan Unit
                                                </span>

                                            </button>

                                        </template>

                                        <template x-if="unit.status == 'Nonaktif'">

                                            <button
                                                @click="toggleStatus('aktif')"
                                                class="w-full flex items-center gap-3
                                                px-3 py-2 text-sm text-green-600
                                                hover:bg-green-50 transition-all duration-150">

                                                @include('components.buttons.btn-active')

                                                <span class="font-semibold">
                                                    Aktifkan Unit
                                                </span>

                                            </button>

                                        </template>

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH UNIT ================= --}}
    <div x-show="openCreateUnit" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.self="openCreateUnit = false" class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 space-y-5">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Tambah Unit Baru</h2>
                <button @click="openCreateUnit = false" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>

            <form @submit.prevent="saveUnit" novalidate class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Unit</label>
                    <input type="text" x-model="newUnit.nomor_unit" placeholder="Contoh: A-101" class="w-full mt-1 border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gedung</label>
                    <input 
                        type="text" 
                        x-model="newUnit.gedung"
                        placeholder="Contoh: Tower A"
                        pattern="^Tower [A-Z]$"
                        title="Format harus: Tower A, Tower B, dst"
                        class="w-full mt-1 border rounded-lg px-3 py-2"
                        required
                        @input="newUnit.gedung = newUnit.gedung.replace(/[^A-Za-z\s]/g, '').replace(/^tower/i, 'Tower')"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Lantai
                    </label>

                    <input
                        type="text"
                        x-model="newUnit.lantai"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        placeholder="Masukkan lantai"
                        class="w-full mt-1 border rounded-lg px-3 py-2"
                        required>

                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700">
                        Nomor Kamar
                    </label>

                    <input
                        type="text"
                        x-model="newUnit.nomor_kamar"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        placeholder="Masukkan nomor kamar"
                        class="w-full mt-1 border rounded-lg px-3 py-2"
                        required>

                </div>

                <template x-if="createdUnit">
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                        <p class="font-semibold text-yellow-800">Akun Unit Berhasil Dibuat</p>
                        <p><strong>Username Login:</strong> <span x-text="createdUnit.nomor_unit"></span></p>
                        <p>Password Sementara</p>
                        <div class="bg-white border rounded px-3 py-2 font-mono text-center" x-text="createdUnit.password"></div>
                        <p class="text-xs text-gray-600">Berikan password ini kepada penghuni unit untuk login pertama.</p>
                    </div>
                </template>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <button type="button" @click="openCreateUnit = false" class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Unit</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL EDIT UNIT ================= --}}
    <div x-show="openEdit" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.self="openEdit = false" class="bg-white w-full max-w-md rounded-lg p-6 space-y-4">
            <h2 class="font-semibold text-lg">Edit Unit </h2>
            
            <div class="space-y-3">
                <div>
                    <label class="text-sm">No Unit</label>
                    <input 
                        type="text" 
                        x-model="editForm.nomor_unit"
                        class="w-full mt-1 border rounded-lg px-3 py-2"
                        placeholder="Contoh: A-101"
                        required
                    >
                </div>
                <div>
                    <label class="text-sm">Gedung</label>
                    <input 
                        type="text" 
                        x-model="editForm.gedung"
                        pattern="^Tower [A-Z]$"
                        title="Format harus: Tower A, Tower B, dst"
                        class="w-full mt-1 border rounded-lg px-3 py-2"
                        @input="editForm.gedung = editForm.gedung.replace(/[^A-Za-z\s]/g, '').replace(/^tower/i, 'Tower')"
                        required
                    >
                </div>
                <div>

                    <label class="text-sm">
                        Lantai
                    </label>

                    <input
                        type="text"
                        x-model="editForm.lantai"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        class="w-full mt-1 border rounded-lg px-3 py-2"
                        placeholder="Masukkan lantai"
                        required>

                </div>
                <div>
                    <label class="text-sm">
                        Nomor Kamar
                    </label>

                    <input
                        type="text"
                        x-model="editForm.nomor_kamar"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        class="w-full mt-1 border rounded-lg px-3 py-2"
                        placeholder="Masukkan nomor kamar"
                        required>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" @click="openEdit = false" class="px-4 py-2 border rounded-lg">Batal</button>
                {{-- GANTI: tidak pakai form submit, langsung @click --}}
                <button type="button" @click="updateUnit()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL GANTI PENGHUNI ================= --}}
    <div x-show="openEditPenghuni" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="openEditPenghuni = false" class="bg-white w-full max-w-lg rounded-xl shadow-lg max-h-[90vh] flex flex-col">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Pergantian Penghuni Unit <span class="text-blue-600" x-text="selectedUnit.nomor_unit"></span></h2>
            </div>
            <div class="px-6 py-4 space-y-5 overflow-visible">
                <div class="bg-gray-50 border rounded-lg p-3 text-sm space-y-1">
                    <p><strong>Gedung:</strong> <span x-text="selectedUnit.gedung"></span></p>
                    <p><strong>Lantai:</strong> <span x-text="selectedUnit.lantai"></span></p>
                    <p><strong>Nomor Kamar:</strong> <span x-text="selectedUnit.nomor_kamar"></span></p>
                </div>

                <div class="border-t pt-4 space-y-4" x-data="{ open: false, searchPenghuni: '' }">
    
                    <h3 class="text-sm font-semibold text-red-600">Pilih Penghuni Baru</h3>

                    <!-- INPUT SEARCH -->
                    <div class="relative">
                        <input 
                            type="text"
                            x-model="searchPenghuni"
                            @focus="open = true"
                            @click.outside="open = false"
                            placeholder="Cari penghuni..."
                            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                        >

                        <!-- DROPDOWN LIST -->
                        <div x-show="open"
                            x-transition
                            class="absolute z-50 w-full bg-white border rounded-lg shadow max-h-48 overflow-y-auto mt-1">

                            <template x-for="p in penghuniList.filter(p => 
                                p.nama.toLowerCase().includes(searchPenghuni.toLowerCase())
                            )" :key="p.id">

                                <div 
                                    @click="
                                        selectedPenghuniId = p.id;
                                        selectedPenghuniDetail = p;
                                        searchPenghuni = p.nama;
                                        open = false;
                                    "
                                    class="px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm"
                                    x-text="p.nama">
                                </div>

                            </template>

                            <!-- EMPTY STATE -->
                            <div x-show="penghuniList.filter(p => 
                                p.nama.toLowerCase().includes(searchPenghuni.toLowerCase())
                            ).length === 0"
                            class="px-3 py-2 text-sm text-gray-500">
                                Tidak ditemukan
                            </div>
                        </div>
                    </div>

                    <!-- DETAIL -->
                    <template x-if="selectedPenghuniDetail">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm space-y-1">
                            <p><strong>Nama:</strong> <span x-text="selectedPenghuniDetail.nama"></span></p>
                            <p><strong>No. HP:</strong> <span x-text="selectedPenghuniDetail.no_telepon"></span></p>
                            <p><strong>Email:</strong> <span x-text="selectedPenghuniDetail.email"></span></p>
                        </div>
                    </template>

                </div>

                <template x-if="passwordGenerated">
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                        <p class="font-semibold text-yellow-800">Password Sementara Penghuni Baru</p>
                        <div class="bg-white border rounded px-3 py-2 font-mono text-center" x-text="newPassword"></div>
                    </div>
                </template>
            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-2 bg-white">
                <button @click="openEditPenghuni = false" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Batal</button>
                <button @click="submitGantiPenghuni" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Pergantian</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL NONAKTIFKAN/AKTIFKAN ================= --}}
    <div x-show="openToggle" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openToggle = false" class="bg-white w-full max-w-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-red-600" x-text="toggleAction == 'nonaktif' ? 'Nonaktifkan Unit' : 'Aktifkan Unit'"></h2>
            <p class="text-sm text-gray-600 mt-2">Unit <strong x-text="selectedUnit.nomor_unit"></strong> akan <span x-text="toggleAction == 'nonaktif' ? 'dinonaktifkan' : 'diaktifkan'"></span>.</p>
            <div class="flex justify-end gap-2 mt-6">
                <button @click="openToggle = false" class="px-4 py-2 border rounded-lg">Batal</button>
                <button @click="submitToggle" class="px-4 py-2 text-white rounded-lg" :class="toggleAction == 'aktif' ? 'bg-green-600' : 'bg-red-600'">Konfirmasi</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL CREDENTIAL PASSWORD ================= --}}
<div
    x-show="openReset"
    x-cloak
    class="fixed inset-0 bg-black/50
    flex items-center justify-center z-50">

    <div
        @click.outside="
            openReset = false;
            resetPasswordGenerated = false;
            newPassword = '';
        "
        class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-5">

        {{-- HEADER --}}
        <div>

            <h2 class="text-lg font-semibold text-gray-800">

                <template x-if="!resetPasswordGenerated">
                    <span>Reset Password Unit</span>
                </template>

                <template x-if="resetPasswordGenerated">
                    <span>Password Berhasil Direset</span>
                </template>

            </h2>

            <p
                class="text-sm text-gray-600 mt-1">

                <template x-if="!resetPasswordGenerated">

                    <span>
                        Password login untuk unit
                        <strong x-text="selectedUnit.nomor_unit"></strong>
                        akan direset.
                    </span>

                </template>

                <template x-if="resetPasswordGenerated">

                    <span>
                        Berikan password berikut kepada penghuni unit
                        untuk login kembali.
                    </span>

                </template>

            </p>

        </div>

        {{-- WARNING --}}
        <template x-if="!resetPasswordGenerated">

            <div
                class="bg-yellow-50 border border-yellow-200
                rounded-lg p-3 text-sm text-yellow-800">

                Pengguna wajib mengganti password
                saat login berikutnya.

            </div>

        </template>

        {{-- PASSWORD RESULT --}}
        <template x-if="resetPasswordGenerated">

            <div
                class="bg-green-50 border border-green-200
                rounded-lg p-4 space-y-3">

                <div>

                    <p class="text-xs text-gray-500 mb-1">
                        Password Sementara Baru
                    </p>

                    <div
                        class="bg-white border rounded-lg
                        px-3 py-2 text-center font-mono text-lg"
                        x-text="newPassword">
                    </div>

                </div>

            </div>

        </template>

        {{-- FOOTER --}}
        <div class="flex justify-end gap-2 pt-2">

            {{-- BEFORE RESET --}}
            <template x-if="!resetPasswordGenerated">

                <div class="flex gap-2">

                    <button
                        @click="
                            openReset = false;
                            resetPasswordGenerated = false;
                            newPassword = '';
                        "
                        class="px-4 py-2 border rounded-lg
                        hover:bg-gray-100">

                        Batal

                    </button>

                    <button
                        @click="submitResetPassword"
                        class="px-4 py-2 bg-purple-600
                        text-white rounded-lg hover:bg-purple-700">

                        Reset Password

                    </button>

                </div>

            </template>

            {{-- AFTER RESET --}}
            <template x-if="resetPasswordGenerated">

                <button
                    @click="
                        openReset = false;
                        resetPasswordGenerated = false;
                        newPassword = '';
                    "
                    class="px-4 py-2 bg-blue-600
                    text-white rounded-lg hover:bg-blue-700">

                    Tutup

                </button>

            </template>

        </div>

    </div>

</div>

</div>

<script>
function unitManager() {
    return {
        openCreateUnit: false,
        openEdit: false,
        openEditPenghuni: false,
        openDelete: false,
        openToggle: false,
        openReset: false,
        openCredential: false,

        selectedUnit: { id: null, nomor_unit: '', gedung: '', lantai: '', currentPenghuni: '' },

        newUnit: { nomor_unit: '', gedung: '', lantai: '', nomor_kamar: '' },

        // 🔥 FIX: tambah nomor_unit
        editForm: { nomor_unit: '', gedung: '', lantai: '', nomor_kamar: '' },

        createdUnit: null,
        credentialData: {
            username: '',
            password: ''
        },
        credentialTitle: '',
        credentialDescription: '',
        search: '',
        statusFilter: '',

        unitsData: [],
        filteredUnits: [],

        penghuniList: [],
        selectedPenghuniId: '',
        selectedPenghuniDetail: null,

        passwordGenerated: false,
        newPassword: '',

        toggleAction: '',
        resetPasswordGenerated: false,

        init(initialUnits) {
            this.unitsData = initialUnits;
            this.filteredUnits = initialUnits;
            this.fetchPenghuniList();

            window.addEventListener('edit-unit', e => this.editUnit(e.detail.id, e.detail.gedung, e.detail.lantai, e.detail.nomor_kamar));
            window.addEventListener('ganti-penghuni', e => this.openGantiPenghuni(e.detail.id, e.detail.nomor_unit, e.detail.currentPenghuni));
            window.addEventListener('reset-password', e => this.openResetPassword(e.detail.id, e.detail.nomor_unit));
            window.addEventListener('toggle-status', e => this.toggleStatus(e.detail.id, e.detail.nomor_unit, e.detail.action));
            window.addEventListener('confirm-delete', e => this.confirmDelete(e.detail.id, e.detail.nomor_unit));
        },

        applyFilter() {
            this.filteredUnits = this.unitsData.filter(unit => {

                // ================= SEARCH =================

                const keyword =
                    this.search.toLowerCase();

                const matchSearch =

                    (unit.nomor_unit || '')
                        .toLowerCase()
                        .includes(keyword)

                    ||

                    (unit.gedung || '')
                        .toLowerCase()
                        .includes(keyword);

                // ================= STATUS =================

                const matchStatus =

                    !this.statusFilter ||

                    unit.status === this.statusFilter;

                return matchSearch && matchStatus;
            });
        },

        fetchPenghuniList() {
            fetch('/penghuni-available')
                .then(res => res.json())
                .then(data => this.penghuniList = data)
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Gagal mengambil data penghuni', 'error');
                });
        },

        fetchPenghuniDetail() {
            this.selectedPenghuniDetail = this.penghuniList.find(p => p.id == this.selectedPenghuniId) || null;
        },

        openGantiPenghuni(id, nomor_unit, currentPenghuni) {
            this.selectedUnit = this.unitsData.find(u => u.id === id) || {};
            this.selectedUnit.nomor_unit = nomor_unit;
            this.selectedUnit.currentPenghuni = currentPenghuni;
            this.openEditPenghuni = true;
        },

        openResetPassword(id, nomor_unit) {
            this.selectedUnit = this.unitsData.find(u => u.id === id) || {};
            this.selectedUnit.nomor_unit = nomor_unit;
            this.resetPasswordGenerated = false;
            this.newPassword = '';
            this.openReset = true;
        },

        toggleStatus(id, nomor_unit, action) {
            this.selectedUnit = this.unitsData.find(u => u.id === id) || {};
            this.selectedUnit.nomor_unit = nomor_unit;
            this.toggleAction = action;
            this.openToggle = true;
        },

        confirmDelete(id, nomor_unit) {
            this.selectedUnit = this.unitsData.find(u => u.id === id) || {};
            this.selectedUnit.nomor_unit = nomor_unit;
            this.openDelete = true;
        },

        // ================= CREATE =================
        saveUnit() {
            fetch('/units', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.newUnit)
            })
            .then(async res => {

                const data = await res.json();

                if (!res.ok) throw data;

                return data;
            })
            .then(data => {

                // update table
                this.unitsData.push(data.unit);

                this.applyFilter();

                // reset form
                this.newUnit = {
                    nomor_unit: '',
                    gedung: '',
                    lantai: '',
                    nomor_kamar: ''
                };

                // tutup modal
                this.openCreateUnit = false;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Unit berhasil ditambahkan'
                });
                })
            .catch(err => {

                console.error(err);

                let message = 'Gagal menambahkan unit';

                if (err.errors) {
                    message = Object.values(err.errors)
                        .flat()
                        .join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            });
        },
        // ================= EDIT =================
        editUnit(id, gedung, lantai, nomor_kamar) {
            this.selectedUnit.id = id;

            this.editForm = {
                nomor_unit: this.unitsData.find(u => u.id === id)?.nomor_unit || '',
                gedung,
                lantai,
                nomor_kamar
            };

            this.openEdit = true;
        },

        updateUnit() {
            fetch(`/units/${this.selectedUnit.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.editForm)
            })
            .then(async res => {

                const data = await res.json();

                if (!res.ok) throw data;

                return data;
            })
            .then(data => {

                this.unitsData = this.unitsData.map(u =>
                    u.id === this.selectedUnit.id
                        ? { ...u, ...this.editForm }
                        : u
                );

                this.applyFilter();

                this.openEdit = false;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Unit berhasil diperbarui'
                });
            })
            .catch(err => {

                console.error(err);

                let message = 'Gagal update';

                // 🔥 VALIDATION ERROR
                if (err.errors) {
                    message = Object.values(err.errors)
                        .flat()
                        .join('\n');
                }

                // 🔥 GENERAL ERROR
                else if (err.message) {
                    message = err.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            });
        },

        // ================= TOGGLE =================
        submitToggle() {
            fetch(`/units/${this.selectedUnit.id}/toggle`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ action: this.toggleAction })
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw data;
                return data;
            })
            .then(data => {
                this.unitsData = this.unitsData.map(u =>
                    u.id === this.selectedUnit.id
                        ? { ...u, status: data.status }
                        : u
                );

                this.applyFilter();
                this.openToggle = false;

                Swal.fire('Berhasil', 'Status diperbarui', 'success');
            })
            .catch(err => {
                console.error(err);

                this.openToggle = false; // 🔥 INI YANG KURANG

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message || 'Gagal update status'
                });
            });
        },

        // ================= RESET PASSWORD =================
        submitResetPassword() {

            fetch(`/units/${this.selectedUnit.id}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(async res => {

                const data = await res.json();

                if (!res.ok) throw data;

                return data;
            })
            .then(data => {

                this.resetPasswordGenerated = true;

                this.newPassword = data.new_password;
                })
            .catch(err => {

                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message || 'Gagal reset password'
                });
            });
            },

        // ================= GANTI PENGHUNI =================
        async submitGantiPenghuni() {

            if (!this.selectedPenghuniId) {

                Swal.fire(
                    'Error',
                    'Pilih penghuni terlebih dahulu',
                    'error'
                );

                return;
            }

            const penghuni =
                this.penghuniList.find(
                    p => p.id == this.selectedPenghuniId
                );

            const confirm =
                await Swal.fire({

                    title: 'Perbarui penghuni unit?',

                    html: `
                        Penghuni unit
                        <b>${this.selectedUnit.nomor_unit}</b>
                        akan diperbarui menjadi
                        <b>${penghuni?.nama || '-'}</b>
                    `,

                    icon: 'question',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Perbarui',

                    cancelButtonText: 'Batal',

                    confirmButtonColor: '#2563eb',

                    cancelButtonColor: '#6b7280',
                });

            if (!confirm.isConfirmed) {
                return;
            }

            fetch(`/units/${this.selectedUnit.id}/ganti-penghuni`, {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                },

                body: JSON.stringify({

                    penghuni_id:
                        this.selectedPenghuniId
                })
            })

            .then(async res => {

                const data = await res.json();

                if (!res.ok) throw data;

                return data;
            })

            .then(data => {

                // update UI
                this.unitsData = this.unitsData.map(u =>

                    u.id === this.selectedUnit.id

                        ? {
                            ...u,

                            current_penghuni:
                                penghuni?.nama || '-'
                        }

                        : u
                );

                this.applyFilter();

                this.selectedPenghuniId = '';

                this.selectedPenghuniDetail = null;

                this.openEditPenghuni = false;

                    // reset state modal
                    this.resetPasswordGenerated = false;

                    this.newPassword = '';

                    // buka modal credential
                    this.openReset = true;

                    // tampilkan password baru
                    this.$nextTick(() => {

                        this.resetPasswordGenerated = true;

                        this.newPassword = data.password;
                    });

                // tampil password baru
                this.openReset = true;

                this.resetPasswordGenerated = true;

                this.newPassword = data.password;
            })

            .catch(err => {

                console.error(err);

                let message =
                    'Gagal mengganti penghuni';

                if (err.message) {
                    message = err.message;
                }

                Swal.fire({

                    icon: 'error',

                    title: 'Error',

                    text: message
                });
            });
            }
    };
}


// ================= DROPDOWN =================
function dropdownMenu(data) {
    return {
        open: false,

        get status() {
            return data.status;
        },

        id: data.id,
        gedung: data.gedung,
        lantai: data.lantai,
        nomor_kamar: data.nomor_kamar,
        nomor_unit: data.nomor_unit,
        currentPenghuni: data.currentPenghuni,

        editUnit() {
            window.dispatchEvent(new CustomEvent('edit-unit', {
                detail: {
                    id: this.id,
                    gedung: this.gedung,
                    lantai: this.lantai,
                    nomor_kamar: this.nomor_kamar
                }
            }));
            this.open = false;
        },

        gantiPenghuni() {
            window.dispatchEvent(new CustomEvent('ganti-penghuni', {
                detail: {
                    id: this.id,
                    nomor_unit: this.nomor_unit,
                    currentPenghuni: data.current_penghuni,
                }
            }));
            this.open = false;
        },

        resetPassword() {
            window.dispatchEvent(new CustomEvent('reset-password', {
                detail: {
                    id: this.id,
                    nomor_unit: this.nomor_unit
                }
            }));
            this.open = false;
        },

        toggleStatus(action) {
            window.dispatchEvent(new CustomEvent('toggle-status', {
                detail: {
                    id: this.id,
                    nomor_unit: this.nomor_unit,
                    action
                }
            }));
            this.open = false;
        },

        confirmDelete() {
            window.dispatchEvent(new CustomEvent('confirm-delete', {
                detail: {
                    id: this.id,
                    nomor_unit: this.nomor_unit
                }
            }));
            this.open = false;
        }
    };
}
</script>
@endsection