@extends('layouts.app')

@section('title', 'Detail Penanganan Keluhan')

@section('content')
<div x-data="detailKeluhanApp()" class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Penanganan Keluhan</h1>
            <p class="text-sm text-gray-500">
                No Tiket: <span x-text="keluhan.tiket"></span>
            </p>
        </div>

        <a href="/daftarPenanganan" class="text-sm text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

    {{-- ================= INFO ================= --}}
    <div class="grid grid-cols-2 gap-4 text-sm bg-white p-6 rounded-xl shadow">
        <p><b>No Unit</b><br><span x-text="keluhan.unit"></span></p>
        <p><b>Nama Penghuni</b><br><span x-text="keluhan.nama"></span></p>
        <p><b>No Telepon</b><br><span x-text="keluhan.telepon"></span></p>
        <p><b>Tanggal</b><br><span x-text="keluhan.tanggal"></span></p>
        <p><b>Status Keluhan</b><br>
            <span class="inline-block text-xs px-2 py-1 rounded"
                  :class="{
                      'bg-blue-100 text-blue-700': keluhan.status === 'Open',
                      'bg-yellow-100 text-yellow-700': keluhan.status === 'On Progress',
                      'bg-green-100 text-green-700': keluhan.status === 'Close'
                  }"
                  x-text="keluhan.status">
            </span>
        </p>
    </div>

    {{-- ================= DESKRIPSI ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-3">
        <div class="flex justify-between items-center">
            <p class="font-semibold">Deskripsi Keluhan</p>
            <button
                @click="openRiwayat = true"
                class="text-sm text-blue-600 hover:underline">
                Lihat Riwayat
            </button>
        </div>

        <div class="bg-gray-100 rounded-lg p-3 text-sm"
             x-text="keluhan.deskripsi"></div>

        <div>
            <p class="text-sm font-medium mb-1">Lampiran Keluhan</p>
            <div class="flex gap-2 flex-wrap">
                <template x-for="file in keluhan.lampiranKeluhan">
                    <span class="px-3 py-1 bg-gray-200 rounded text-xs" x-text="file"></span>
                </template>
            </div>
        </div>
    </div>

    {{-- ================= INFORMASI & KIRIM KONFIRMASI PENGHUNI ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <h3 class="font-semibold">Konfirmasi Penghuni</h3>
        
        {{-- Tampilkan informasi konfirmasi jika sudah ada --}}
        <template x-if="keluhan.konfirmasiPenghuni">
            <div class="space-y-3 mb-4">
                <div class="p-4 rounded-lg" 
                    :class="{
                        'bg-green-50 border border-green-200': keluhan.konfirmasiPenghuni.status === 'Puas',
                        'bg-red-50 border border-red-200': keluhan.konfirmasiPenghuni.status === 'Tidak Puas'
                    }">
                    
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-medium">Status Konfirmasi:</span>
                        <span class="text-sm px-2 py-1 rounded"
                            :class="{
                                'bg-green-200 text-green-800': keluhan.konfirmasiPenghuni.status === 'Puas',
                                'bg-red-200 text-red-800': keluhan.konfirmasiPenghuni.status === 'Tidak Puas'
                            }"
                            x-text="keluhan.konfirmasiPenghuni.status">
                        </span>
                    </div>
                    
                    <p class="text-sm mb-2">
                        <span class="font-medium">Catatan:</span> 
                        <span x-text="keluhan.konfirmasiPenghuni.catatan || '-'"></span>
                    </p>
                    
                    <p class="text-xs text-gray-500">
                        Dikonfirmasi pada: <span x-text="keluhan.konfirmasiPenghuni.waktu"></span>
                    </p>
                </div>
            </div>
        </template>

        {{-- Tampilkan pesan jika belum ada konfirmasi --}}
        <template x-if="!keluhan.konfirmasiPenghuni">
            <p class="text-sm text-gray-500 italic mb-4">
                Belum ada konfirmasi dari penghuni.
            </p>
        </template>

        {{-- Tombol Kirim Konfirmasi (hanya tampil jika status belum Close) --}}
        <div x-show="keluhan.status !== 'Close'" class="border-t pt-4">
            <p class="text-sm text-gray-600 mb-3">
                Kirim permintaan konfirmasi kepada penghuni untuk mengetahui apakah keluhan sudah selesai atau masih ada kendala.
            </p>
            
            <div class="flex gap-3">
                <button 
                    @click="kirimPermintaanKonfirmasi()" 
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    Kirim Permintaan Konfirmasi
                </button>
            </div>

            {{-- Riwayat pengiriman konfirmasi --}}
            <template x-if="riwayatKonfirmasi.length > 0">
                <div class="mt-4">
                    <p class="text-sm font-medium mb-2">Riwayat Pengiriman Konfirmasi:</p>
                    <div class="space-y-2">
                        <template x-for="(item, index) in riwayatKonfirmasi" :key="index">
                            <div class="text-xs text-gray-600 border-l-2 border-blue-300 pl-3 py-1">
                                <span x-text="item.waktu"></span> - 
                                <span x-text="item.keterangan"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ================= WORK ORDER ================= --}}
    <template x-if="workOrders.length">
        <div class="bg-white p-6 rounded-xl shadow space-y-5">
            <h3 class="font-semibold">Riwayat Work Order</h3>

            <template x-for="(wo, index) in workOrders" :key="wo.id">
                <div class="border rounded-lg p-4 space-y-2">
                    
                    <div class="flex justify-between items-center">
                        <p class="font-medium text-sm">
                            WO #<span x-text="index + 1"></span>
                            (<span x-text="wo.no"></span>)
                        </p>

                        <span
                            class="text-xs px-2 py-1 rounded"
                            :class="{
                                'bg-yellow-100 text-yellow-800': wo.status === 'On Progress',
                                'bg-green-100 text-green-800': wo.status === 'Selesai'
                            }"
                            x-text="wo.status">
                        </span>
                    </div>

                    <p class="text-sm">
                        <b>Departemen:</b>
                        <span x-text="wo.dept"></span>
                    </p>

                    <p class="text-sm">
                        <b>Tanggal Dibuat:</b>
                        <span x-text="wo.tanggal"></span>
                    </p>

                    <p class="text-sm text-gray-600">
                        <b>Instruksi:</b>
                        <span x-text="wo.instruksi"></span>
                    </p>

                    <div class="flex flex-wrap gap-2 mt-3">
                        <button
                            @click="bukaLaporanWO(wo)"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                            Lihat Laporan Penyelesaian
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </template>
                        
    {{-- ================= MODAL RIWAYAT PENANGANAN ================= --}}
    <div
        x-show="openRiwayat"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
        <div class="bg-white w-full max-w-xl rounded-xl shadow-lg overflow-hidden">

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    Riwayat Penanganan
                </h3>
                <button
                    @click="openRiwayat = false"
                    class="text-gray-500 hover:text-gray-700 text-xl leading-none"
                >
                    &times;
                </button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-4 space-y-4 text-sm max-h-[400px] overflow-y-auto">

                <template x-for="(r, index) in riwayat" :key="index">
                    <div class="relative pl-5 border-l-4 border-blue-500">
                        <p class="font-medium" x-text="r.judul"></p>
                        <p class="text-gray-600 mt-1" x-text="r.ket"></p>
                        <p class="text-xs text-gray-400 mt-1" x-text="r.waktu"></p>
                    </div>
                </template>

                {{-- jika riwayat kosong --}}
                <template x-if="riwayat.length === 0">
                    <p class="text-center text-gray-400 py-6">
                        Belum ada riwayat penanganan
                    </p>
                </template>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t flex justify-end">
                <button
                    @click="openRiwayat = false"
                    class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                >
                    Tutup
                </button>
            </div>

        </div>
    </div>

    {{-- ================= UPDATE PENANGANAN TR ================= --}}
{{-- Hanya tampil jika status belum Close --}}
<template x-if="keluhan.status !== 'Close'">
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <h3 class="font-semibold">Update Penanganan (TR)</h3>

        {{-- CATATAN --}}
        <div>
            <label class="text-sm font-medium mb-1 block">
                Catatan Penanganan
            </label>
            <textarea
                x-model="updateTR.catatan"
                class="w-full border rounded-lg px-3 py-2 text-sm"
                rows="3"
                placeholder="Tuliskan aktivitas, hasil pengecekan, atau tindak lanjut TR...">
            </textarea>
        </div>

        {{-- UPLOAD FOTO --}}
        <div>
            <label class="text-sm font-medium mb-1 block">
                Lampiran Dokumentasi (Opsional)
            </label>

            <input
                type="file"
                accept="image/*"
                multiple
                @change="handleUploadTR($event)"
                class="text-sm"
            />

            {{-- PREVIEW --}}
            <div class="flex gap-2 mt-2 flex-wrap">
                <template x-for="(img, index) in updateTR.lampiran">
                    <div class="relative">
                        <img :src="img" class="w-20 h-20 object-cover rounded border">
                        <button
                            @click="hapusLampiranTR(index)"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5">
                            ✕
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- ACTION --}}
        <div class="flex gap-3 pt-2">
            <button
                @click="simpanUpdateTR"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                Simpan Update
            </button>

            <button
                @click="openWO = true"
                class="bg-green-600 text-white px-4 py-2 rounded text-sm">
                Buat Work Order
            </button>
        </div>
    </div>
</template>

{{-- Tampilkan pesan jika status sudah Close --}}
<template x-if="keluhan.status === 'Close'">
    <div class="bg-white p-6 rounded-xl shadow">
        <div class="flex items-center gap-3 text-gray-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm">
                Keluhan telah ditutup. Tidak dapat melakukan update penanganan.
            </p>
        </div>
    </div>
</template>

    {{-- ================= MODAL LAPORAN WO ================= --}}
    <div x-show="openLaporan" x-cloak
         class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-xl rounded-xl p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Laporan Penyelesaian WO</h3>
                <button @click="openLaporan=false">✕</button>
            </div>

            <p class="text-sm"><b>Departemen:</b> <span x-text="selectedWO.dept"></span></p>
            <p class="text-sm"><b>Petugas:</b> <span x-text="selectedWO.petugas"></span></p>

            <div class="bg-gray-100 rounded-lg p-3 text-sm"
                 x-text="selectedWO.laporan"></div>

            <div>
                <p class="text-sm font-medium">Lampiran Pekerjaan</p>
                <div class="flex gap-2 flex-wrap mt-1">
                    <template x-for="file in selectedWO.lampiran">
                        <span class="px-3 py-1 border rounded text-xs" x-text="file"></span>
                    </template>
                </div>
            </div>

            <p class="text-xs text-gray-500">
                Diselesaikan: <span x-text="selectedWO.tanggal"></span>
            </p>
        </div>
    </div>

    {{-- ================= MODAL DETAIL LAPORAN WO ================= --}}
    <div x-show="openLaporan" x-cloak
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-4xl rounded-xl p-6 space-y-5 overflow-y-auto max-h-[90vh]">

            {{-- HEADER --}}
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <h3 class="text-lg font-semibold">Work Order Report</h3>
                    <p class="text-xs text-gray-500">
                        No WO: <span x-text="selectedWO.no"></span>
                    </p>
                </div>
                <button @click="openLaporan=false" class="text-xl">✕</button>
            </div>

            {{-- INFORMASI UTAMA --}}
            <div class="grid grid-cols-2 gap-4 text-sm">
                <p><b>Ticket</b><br><span x-text="keluhan.tiket"></span></p>
                <p><b>Tanggal WO</b><br><span x-text="selectedWO.tanggal"></span></p>
                <p><b>Requestor</b><br><span x-text="keluhan.nama"></span></p>
                <p><b>Departemen</b><br><span x-text="selectedWO.dept"></span></p>
                <p><b>Petugas</b><br><span x-text="selectedWO.petugas"></span></p>
                <p>
                    <b>Status</b><br>
                    <span
                        class="inline-block text-xs px-2 py-1 rounded"
                        :class="{
                            'bg-yellow-100 text-yellow-800': selectedWO.status === 'On Progress',
                            'bg-green-100 text-green-800': selectedWO.status === 'Selesai'
                        }"
                        x-text="selectedWO.status">
                    </span>
                </p>
            </div>

            {{-- LOKASI --}}
            <div class="border rounded-lg p-4 text-sm space-y-1">
                <p class="font-medium">Lokasi</p>
                <p>
                    Blok: <b>TB</b> |
                    Lantai: <b>10</b> |
                    Unit: <b x-text="keluhan.unit"></b>
                </p>
            </div>

            {{-- INSTRUKSI AWAL --}}
            <div class="space-y-1">
                <p class="font-medium text-sm">Instruksi Detail</p>
                <div class="bg-gray-100 rounded-lg p-3 text-sm"
                    x-text="selectedWO.instruksi">
                </div>
            </div>

            {{-- LAPORAN PEKERJAAN --}}
            <div class="space-y-1">
                <p class="font-medium text-sm"> Laporan Pekerjaan</p>
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm"
                    x-text="selectedWO.laporan || 'Belum ada laporan pekerjaan.'">
                </div>
            </div>

            {{-- LAMPIRAN --}}
            <div>
                <p class="font-medium text-sm mb-1">Lampiran Pekerjaan</p>
                <div class="flex flex-wrap gap-2">
                    <template x-if="selectedWO.lampiran && selectedWO.lampiran.length">
                        <template x-for="file in selectedWO.lampiran">
                            <span class="px-3 py-1 border rounded text-xs bg-gray-50"
                                x-text="file"></span>
                        </template>
                    </template>

                    <template x-if="!selectedWO.lampiran || !selectedWO.lampiran.length">
                        <span class="text-xs text-gray-400">Tidak ada lampiran</span>
                    </template>
                </div>
            </div>

        </div>
    </div>
                        
    {{-- ================= MODAL BUAT WORK ORDER ================= --}}
    <div
        x-show="openWO"
        x-cloak
        x-transition
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
    >
        <div class="bg-white w-full max-w-3xl rounded-xl p-6 space-y-5 overflow-y-auto max-h-[90vh]">

            <h3 class="text-lg font-semibold text-gray-800">
                Buat Work Order
            </h3>

            {{-- HEADER WO --}}
            <div class="grid grid-cols-2 gap-4 text-sm">

                <div>
                    <label class="font-medium">No WO</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        value="WO-2026-001"
                        disabled
                    >
                </div>

                <div>
                    <label class="font-medium">Tanggal WO</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        value="14 Feb 2026 10:30"
                        disabled
                    >
                </div>

                <div>
                    <label class="font-medium">Ticket</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        x-model="keluhan.tiket"
                        disabled
                    >
                </div>

                <div>
                    <label class="font-medium">Requestor</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        x-model="keluhan.nama"
                        disabled
                    >
                </div>

                <div>
                    <label class="font-medium">Department</label>
                    <select
                        class="w-full border rounded px-3 py-1"
                        x-model="woForm.dept"
                    >
                        <option value="">Pilih</option>
                        <option>Operational</option>
                        <option>Engineering</option>
                        <option>Finance</option>
                    </select>
                </div>

            </div>

            {{-- LOKASI --}}
            <div class="grid grid-cols-3 gap-4 text-sm">

                <div>
                    <label class="font-medium">Blok</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        value="TB"
                        disabled
                    >
                </div>

                <div>
                    <label class="font-medium">Lantai</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        value="10"
                        disabled
                    >
                </div>

                <div>
                    <label class="font-medium">Unit</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        x-model="keluhan.unit"
                        disabled
                    >
                </div>

            </div>

            {{-- NOTES --}}
            <div>
                <label class="font-medium text-sm">Notes</label>
                <textarea
                    x-model="woForm.notes"
                    class="w-full border rounded px-3 py-2 text-sm"
                    placeholder="Catatan awal / permintaan khusus..."
                ></textarea>
            </div>

            {{-- DETAIL INSTRUCTION --}}
            <div>
                <label class="font-medium text-sm">Detail Instruksi</label>
                <textarea
                    x-model="woForm.instruction"
                    class="w-full border rounded px-3 py-2 text-sm"
                    placeholder="Instruksi pekerjaan untuk departemen..."
                ></textarea>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-2 pt-3">
                <button
                    @click="openWO=false"
                    class="border px-4 py-2 rounded text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </button>
                <button
                    @click="kirimWO"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                >
                    Kirim Work Order
                </button>
            </div>

        </div>
    </div>

</div>

<script>
function detailKeluhanApp() {
    return {
        /* ================= MODAL STATE ================= */
        openWO: false,
        openRiwayat: false,
        openLaporan: false,

        selectedWO: {},

        /* ================= DATA KELUHAN ================= */
        keluhan: {
            id: 1,
            tiket: 'TCK-001',
            unit: 'A-101',
            nama: 'Budi Santoso',
            telepon: '08123456789',
            tanggal: '12 Feb 2026',
            deskripsi: 'AC ruang tamu tidak dingin.',
            lampiranKeluhan: ['foto_ac_1.jpg', 'foto_ac_2.jpg'],
            status: 'On Progress',
            konfirmasiPenghuni: null
        },

        /* ================= RIWAYAT KONFIRMASI ================= */
        riwayatKonfirmasi: [],

        /* ================= RIWAYAT ================= */
        riwayat: [
            {
                judul: 'Keluhan Masuk',
                ket: 'Keluhan diterima oleh sistem',
                waktu: '12 Feb 2026 09:00'
            },
            {
                judul: 'TR Mengambil Keluhan',
                ket: 'Keluhan di-assign ke tim TR',
                waktu: '12 Feb 2026 09:10'
            }
        ],

        /* ================= WORK ORDER ================= */
        workOrders: [
            {
                id: 1,
                no: 'WO-001',
                dept: 'Teknik',
                instruksi: 'Periksa AC dan lakukan perbaikan',
                status: 'Selesai',
                petugas: 'Ahmad Fauzi',
                laporan: 'Tambah freon, AC sudah dingin kembali.',
                lampiran: ['wo_before.jpg', 'wo_after.jpg'],
                tanggal: '13 Feb 2026 14:00'
            }
        ],

        /* ================= UPDATE TR ================= */
        updateTR: {
            status: 'On Progress',
            catatan: '',
            lampiran: []
        },

        /* ================= FORM WO ================= */
        woForm: {
            dept: '',
            notes: '',
            instruction: ''
        },

        /* ================= METHODS ================= */

        // Method untuk memuat data berdasarkan ID
        loadData(id) {
            // Data dummy untuk berbagai kasus
            const dataKeluhan = {
                1: { // On Progress - Belum ada konfirmasi
                    id: 1,
                    tiket: 'TCK-001',
                    unit: 'A-101',
                    nama: 'Budi Santoso',
                    telepon: '08123456789',
                    tanggal: '12 Feb 2026',
                    deskripsi: 'AC ruang tamu tidak dingin.',
                    lampiranKeluhan: ['foto_ac_1.jpg', 'foto_ac_2.jpg'],
                    status: 'On Progress',
                    konfirmasiPenghuni: null
                },
                2: { // On Progress - Dengan riwayat konfirmasi
                    id: 2,
                    tiket: 'TCK-002',
                    unit: 'B-205',
                    nama: 'Siti Aminah',
                    telepon: '08129876543',
                    tanggal: '13 Feb 2026',
                    deskripsi: 'Lampu di ruang tamu mati. Sudah diganti namun masih kedip-kedip.',
                    lampiranKeluhan: ['foto_lampu.jpg'],
                    status: 'On Progress',
                    konfirmasiPenghuni: null
                },
                3: { // Close - Dengan konfirmasi Puas
                    id: 3,
                    tiket: 'TCK-003',
                    unit: 'C-310',
                    nama: 'Ahmad Rizki',
                    telepon: '08121234567',
                    tanggal: '14 Feb 2026',
                    deskripsi: 'Kran wastafel kamar mandi bocor.',
                    lampiranKeluhan: ['foto_kran_1.jpg', 'foto_kran_2.jpg'],
                    status: 'Close',
                    konfirmasiPenghuni: {
                        status: 'Puas',
                        catatan: 'Kran sudah tidak bocor, terima kasih',
                        waktu: '15 Feb 2026 09:30',
                        metode: 'Via WhatsApp'
                    }
                }
            };

            if (dataKeluhan[id]) {
                this.keluhan = dataKeluhan[id];
                
                // Set riwayat berdasarkan data
                this.setRiwayat(id);
                
                // Set riwayat konfirmasi
                this.setRiwayatKonfirmasi(id);
            }
        },

        setRiwayat(id) {
            const riwayatData = {
                1: [
                    { judul: 'Keluhan Masuk', ket: 'Keluhan diterima oleh sistem', waktu: '12 Feb 2026 09:00' },
                    { judul: 'TR Mengambil Keluhan', ket: 'Keluhan di-assign ke tim TR', waktu: '12 Feb 2026 09:10' }
                ],
                2: [
                    { judul: 'Keluhan Masuk', ket: 'Keluhan diterima oleh sistem', waktu: '13 Feb 2026 08:30' },
                    { judul: 'TR Mengambil Keluhan', ket: 'Keluhan di-assign ke tim TR', waktu: '13 Feb 2026 08:45' },
                    { judul: 'Permintaan Konfirmasi', ket: 'Permintaan konfirmasi dikirim ke penghuni', waktu: '13 Feb 2026 10:30' }
                ],
                3: [
                    { judul: 'Keluhan Masuk', ket: 'Keluhan diterima oleh sistem', waktu: '14 Feb 2026 07:15' },
                    { judul: 'TR Mengambil Keluhan', ket: 'Keluhan di-assign ke tim TR', waktu: '14 Feb 2026 07:30' },
                    { judul: 'TR Melakukan Perbaikan', ket: 'Mengganti seal kran yang bocor', waktu: '14 Feb 2026 09:00' },
                    { judul: 'Permintaan Konfirmasi', ket: 'Permintaan konfirmasi dikirim ke penghuni', waktu: '14 Feb 2026 09:30' },
                    { judul: 'Konfirmasi Penghuni', ket: 'Penghuni mengkonfirmasi PUAS', waktu: '15 Feb 2026 09:30' }
                ]
            };
            
            this.riwayat = riwayatData[id] || this.riwayat;
        },

        setRiwayatKonfirmasi(id) {
            const konfirmasiData = {
                2: [
                    { waktu: '13 Feb 2026 10:30', keterangan: 'Permintaan konfirmasi telah dikirim ke penghuni' }
                ],
                3: [
                    { waktu: '14 Feb 2026 09:30', keterangan: 'Permintaan konfirmasi telah dikirim ke penghuni' }
                ]
            };
            
            this.riwayatKonfirmasi = konfirmasiData[id] || [];
        },

        kirimPermintaanKonfirmasi() {
            const waktu = this.now();
            
            this.riwayatKonfirmasi.push({
                waktu: waktu,
                keterangan: 'Permintaan konfirmasi telah dikirim ke penghuni'
            });

            this.riwayat.push({
                judul: 'Permintaan Konfirmasi',
                ket: 'Permintaan konfirmasi telah dikirim ke penghuni',
                waktu: waktu
            });

            alert('Permintaan konfirmasi telah dikirim ke penghuni');
        },

        handleUploadTR(event) {
            const files = event.target.files;
            for (let file of files) {
                this.updateTR.lampiran.push(URL.createObjectURL(file));
            }
        },

        hapusLampiranTR(index) {
            this.updateTR.lampiran.splice(index, 1);
        },

        simpanUpdateTR() {
            if (this.updateTR.catatan.trim() === '') {
                alert('Catatan TR wajib diisi');
                return;
            }

            this.keluhan.status = this.updateTR.status;

            if (this.updateTR.status === 'Close') {
                if (!confirm('Anda akan menutup keluhan ini. Lanjutkan?')) {
                    return;
                }
                
                this.riwayat.push({
                    judul: 'Keluhan Ditutup oleh TR',
                    ket: 'Keluhan ditutup oleh petugas TR: ' + this.updateTR.catatan,
                    waktu: this.now()
                });
            } else {
                this.riwayat.push({
                    judul: 'Update TR',
                    ket: this.updateTR.catatan,
                    waktu: this.now()
                });
            }

            this.updateTR.catatan = '';
            this.updateTR.lampiran = [];
            
            alert('Update berhasil disimpan');
        },

        bukaLaporanWO(wo) {
            this.selectedWO = wo;
            this.openLaporan = true;
        },

        kirimWO() {
            if (!this.woForm.dept || !this.woForm.instruction) {
                alert('Departemen dan instruksi wajib diisi');
                return;
            }

            const id = this.workOrders.length + 1;

            this.workOrders.push({
                id: id,
                no: 'WO-' + String(id).padStart(3, '0'),
                dept: this.woForm.dept,
                instruksi: this.woForm.instruction,
                status: 'On Progress',
                petugas: '-',
                laporan: '',
                lampiran: [],
                tanggal: this.now()
            });

            this.riwayat.push({
                judul: 'Work Order Dibuat',
                ket: `WO dikirim ke departemen ${this.woForm.dept}`,
                waktu: this.now()
            });

            this.woForm = { dept: '', notes: '', instruction: '' };
            this.openWO = false;
            
            alert('Work Order berhasil dibuat');
        },

        now() {
            const d = new Date();
            const hari = d.getDate();
            const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][d.getMonth()];
            const tahun = d.getFullYear();
            const jam = d.getHours().toString().padStart(2, '0');
            const menit = d.getMinutes().toString().padStart(2, '0');
            
            return `${hari} ${bulan} ${tahun} ${jam}:${menit}`;
        },

        // Inisialisasi
        init() {
            // Ambil ID dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');
            if (id) {
                this.loadData(parseInt(id));
            }
        }
    }
}
</script>

@endsection