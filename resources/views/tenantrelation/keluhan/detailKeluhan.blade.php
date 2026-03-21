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
    <div class="bg-white p-6 rounded-xl shadow space-y-4">

        {{-- JUDUL SECTION --}}
        <div class="flex justify-between items-center">
            <p class="font-semibold">Keluhan</p>
        </div>

        <div class="pl-4 space-y-4">

            {{-- JUDUL KELUHAN --}}
            <div>
                <p class="text-sm font-medium mb-1">
                    Judul Keluhan
                </p>
                <p class="text-gray-600"
                x-text="keluhan.judul">
                </p>
            </div>

            {{-- DESKRIPSI --}}
            <div>
                <p class="text-sm font-medium mb-1">
                    Deskripsi Keluhan
                </p>
                <p
                    class="text-gray-600"
                    x-text="keluhan.deskripsi">
                </p>
            </div>

            {{-- LAMPIRAN --}}
            <div>
                <p class="text-sm font-medium mb-1">
                    Lampiran Keluhan
                </p>
                <div class="flex gap-2 flex-wrap">
                    <template x-for="file in keluhan.lampiranKeluhan" :key="file">
                        <span
                            class="px-3 py-1 bg-gray-200 rounded text-xs text-gray-700"
                            x-text="file">
                        </span>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS KELUHAN --}}
    <div class="bg-white p-4 rounded-xl border space-y-2">
        <h3 class="font-semibold text-sm">Status Keluhan</h3>

        <select x-model="keluhan.status"
            class="w-full border rounded-lg px-3 py-2">
            <option>Open</option>
            <option>On Progress</option>
            <option>Close</option>
        </select>

        <p class="text-xs text-gray-500">
            Status ini akan langsung terlihat oleh penghuni
        </p>
    </div>

    {{-- ================= WORK ORDER ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-5">

        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <h3 class="font-semibold">Work Order</h3>

            {{-- Tombol Buat WO --}}
            <button
                x-show="keluhan.status !== 'Close' && workOrdersByTiket.length === 0"
                @click="openWO = true"
                class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                + Buat Work Order
            </button>
        </div>

        {{-- LIST WO --}}
        <template x-if="workOrdersByTiket.length">
            <template x-for="(wo, index) in workOrdersByTiket" :key="wo.id">
                <div class="border rounded-lg p-4 space-y-2">
                    
                    <div class="flex justify-between items-center">
                        <p class="font-medium text-sm">
                            WO #<span x-text="index + 1"></span>
                            (<span x-text="wo.no"></span>)
                        </p>

                        <span
                            class="text-xs px-2 py-1 rounded"
                            :class="{
                                'bg-blue-100 text-blue-800': wo.status === 'Open',
                                'bg-yellow-100 text-yellow-800': wo.status === 'On Progress',
                                'bg-orange-100 text-orange-800': wo.status === 'Waiting',
                                'bg-green-100 text-green-800': wo.status === 'Close'
                            }"
                            x-text="wo.status">
                        </span>
                    </div>

                    <p class="text-sm">
                        <b>Departemen:</b> <span x-text="wo.dept"></span>
                    </p>

                    <p class="text-sm">
                        <b>Tanggal:</b> <span x-text="wo.tanggal"></span>
                    </p>

                    <p class="text-sm text-gray-600">
                        <b>Instruksi:</b> <span x-text="wo.instruksi"></span>
                    </p>

                    <button
                        @click="bukaLaporanWO(wo)"
                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Lihat Laporan
                    </button>
                </div>
            </template>
        </template>

        {{-- JIKA BELUM ADA WO --}}
        <template x-if="!workOrdersByTiket.length">
            <p class="text-sm text-gray-500 italic">
                Belum ada Work Order untuk tiket ini.
            </p>
        </template>

    </div>

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
                    <div
                        class="relative pl-5 py-3 rounded-md"
                        :class="{
                            'border-l-4 border-blue-500 bg-blue-50/30': r.tipe === 'tr',
                            'border-l-4 border-green-500 bg-green-50/30': r.tipe === 'penghuni'
                        }"
                    >
                        <p class="font-medium text-gray-800" x-text="r.judul"></p>
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
                    class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">
                    Tutup
                </button>
            </div>

        </div>
    </div>
    {{-- ================= KEPUTUSAN PENANGANAN TR ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">Keputusan Penanganan</h3>

            <div class="flex gap-4 items-center">
                {{-- LINK KNOWLEDGE BASE --}}
                <button
                    @click="openKnowledgeBase = true"
                    class="text-sm text-green-600 hover:underline">
                    Lihat Knowledge Base
                </button>

                {{-- RIWAYAT --}}
                <button
                    @click="openRiwayat = true"
                    class="text-sm text-blue-600 hover:underline">
                    Lihat Riwayat
                </button>
            </div>
        </div>

        {{-- INFO JIKA SUDAH CLOSE --}}
        <template x-if="keluhan.status === 'Close'">
            <div class="space-y-3">
                <div class="text-sm text-gray-500 italic bg-gray-50 border rounded-lg p-3">
                    Keluhan sudah ditutup. Riwayat keputusan tetap dapat dilihat,
                    namun tidak dapat menambahkan keputusan baru.
                </div>

                {{-- SIMPAN KE KNOWLEDGE BASE --}}
                <div class="flex justify-end">
                    <button
                        @click="
                            kbForm.judul = keluhan.judul;
                            kbForm.deskripsi = keluhan.deskripsi;
                            kbForm.langkah = keputusan.catatan;
                            openSimpanKB = true
                        "
                        class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                        Simpan Solusi ke Knowledge Base
                    </button>
                </div>
            </div>
        </template>

        {{-- FORM UPDATE PENANGANAN (HANYA JIKA BELUM CLOSE) --}}
        <template x-if="keluhan.status !== 'Close'">
            <div class="pl-4 space-y-4">

                {{-- JUDUL Penanganan --}}
                <div>
                    <label class="text-sm font-medium mb-1 block">
                        Judul Penanganan
                    </label>
                    <input
                        type="text"
                        x-model="keputusan.judul"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Masukkan judul keputusan"
                    >
                </div>

                {{-- CATATAN KEPUTUSAN --}}
                <div>
                    <label class="text-sm font-medium mb-1 block">
                        Catatan Penanganan
                    </label>
                    <textarea
                        x-model="keputusan.catatan"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        rows="3"
                        placeholder="Masukkan catatan keputusan"
                    ></textarea>
                </div>

                {{-- LAMPIRAN Penanganan --}}
                <div>
                    <label class="text-sm font-medium mb-1 block">
                        Lampiran Dokumentasi
                    </label>

                    <input
                        type="file"
                        multiple
                        @change="handleUploadKeputusan($event)"
                        class="text-sm"
                    >

                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(file, index) in keputusan.lampiran" :key="index">
                            <div class="relative border rounded px-3 py-1 text-xs bg-gray-50">
                                <span x-text="file.name"></span>
                                <button
                                    @click="hapusLampiranKeputusan(index)"
                                    class="ml-2 text-red-500 hover:text-red-700">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- {{-- UBAH STATUS --}}
                <div>
                    <label class="text-sm font-medium mb-1 block">
                        Ubah Status Keluhan
                    </label>
                    <select
                        x-model="keputusan.status"
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="Open">Open</option>
                        <option value="On Progress">On Progress</option>
                        <option value="Close">Close</option>
                    </select>
                </div> -->

                {{-- ACTION --}}
                <div class="flex gap-3 pt-2">
                    <button
                        @click="simpanKeputusan"
                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Update Penanganan
                    </button>
                </div>

            </div>
        </template>
    </div>    

    {{-- ================= KEPUTUSAN UNTUK PENGHUNI ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">

        <h3 class="font-semibold">Keputusan / Solusi untuk Penghuni</h3>

        <div class="space-y-3">
            <input type="text"
                x-model="keputusanAkhir.judul"
                placeholder="Judul keputusan"
                class="w-full border rounded-lg px-3 py-2 text-sm">

            <textarea
                x-model="keputusanAkhir.solusi"
                placeholder="Solusi / hasil akhir penanganan"
                class="w-full border rounded-lg px-3 py-2 text-sm"
                rows="3"></textarea>

            <input type="file" multiple>

            <div class="flex justify-between">
                <button
                    @click="openSimpanKB = true"
                    class="text-green-600 text-sm hover:underline">
                    Simpan ke Knowledge Base
                </button>

                <button
                    class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                    Simpan Keputusan
                </button>
            </div>
        </div>
    </div>

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
                            'bg-blue-100 text-blue-800': selectedWO.status === 'Open',
                            'bg-yellow-100 text-yellow-800': selectedWO.status === 'On Progress',
                            'bg-orange-100 text-orange-800': selectedWO.status === 'Waiting',
                            'bg-green-100 text-green-800': selectedWO.status === 'Close'
                        }"
                        x-text="selectedWO.status">
                    </span>
                </p>
            </div>

            {{-- LOKASI --}}
            <div class="border rounded-lg p-4 text-sm space-y-1">
                <p class="font-medium">Lokasi</p>
                <p>
                    Tower: <b>A</b> |
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

            {{-- RIWAYAT PENANGANAN PEKERJAAN (WO) --}}
            <div class="space-y-3">
                <p class="font-medium text-sm">Riwayat Penanganan Pekerjaan</p>

                <template x-if="selectedWO.laporan && selectedWO.laporan.length">
                    <div class="space-y-3">

                        <template x-for="(lapor, index) in selectedWO.laporan" :key="index">
                            <div
                                class="relative pl-5 py-3 rounded-md"
                                :class="{
                                    'border-l-4 border-blue-500 bg-blue-50/30': lapor.status === 'On Progress',
                                    'border-l-4 border-orange-500 bg-orange-50/30': lapor.status === 'Waiting',
                                    'border-l-4 border-green-500 bg-green-50/30': lapor.status === 'Close'
                                }"
                            >
                                <p class="font-medium text-gray-800" x-text="lapor.judul"></p>
                                <p class="text-gray-600 mt-1" x-text="lapor.ket"></p>
                                <p class="text-xs text-gray-400 mt-1" x-text="lapor.waktu"></p>
                            </div>
                        </template>

                    </div>
                </template>

                <template x-if="!selectedWO.laporan || !selectedWO.laporan.length">
                    <p class="text-sm text-gray-400 italic">
                        Belum ada laporan pekerjaan dari departemen.
                    </p>
                </template>
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
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
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

                <!-- <div>
                    <label class="font-medium">Tanggal WO</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        value="14 Feb 2026 10:30"
                        disabled
                    >
                </div> -->

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
                    <label class="font-medium">Nomor Telepon</label>
                    <input
                        class="w-full border rounded px-3 py-1 bg-gray-100 text-gray-500 cursor-not-allowed"
                        x-model="keluhan.telepon"
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
            <div class="space-y-3">

            {{-- JUDUL --}}
            <h4 class="text-sm font-semibold tracking-wide">
                Lokasi
            </h4>
            <div class="grid grid-cols-3 gap-4 text-sm pl-4">
                <div>
                    <label class="font-medium">Tower</label>
                    <input
                        class="w-full border rounded px-3 py-1"
                        value=" "
                        
                    >
                </div>

                <div>
                    <label class="font-medium">Lantai</label>
                    <input
                        class="w-full border rounded px-3 py-1"
                        value=" "
                        
                    >
                </div>

                <div>
                    <label class="font-medium">No</label>
                    <input
                        class="w-full border rounded px-3 py-1 "
                        x-model="keluhan.no"
                        
                    >
                </div>

            </div>

            {{-- NOTES --}}
            <div>
                <label class="font-medium text-sm">Instruksi</label>
                <textarea
                    x-model="woForm.instruction"
                    class="w-full border rounded px-3 py-2 text-sm"
                    placeholder="Instruksi pekerjaan untuk departemen"
                ></textarea>
            </div>

            <!-- {{-- DETAIL INSTRUCTION --}}
            <div>
                <label class="font-medium text-sm">Detail Instruksi</label>
                <textarea
                    x-model="woForm.instruction"
                    class="w-full border rounded px-3 py-2 text-sm"
                    placeholder="Instruksi pekerjaan untuk departemen..."
                ></textarea>
            </div> -->

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

    {{-- ================= MODAL KNOWLEDGE BASE ================= --}}
    <div
        x-show="openKnowledgeBase"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
        <div class="bg-white w-full max-w-5xl rounded-xl shadow-lg overflow-hidden">

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Knowledge Base Solusi Keluhan
                    </h3>
                    <p class="text-xs text-gray-500">
                        Referensi solusi untuk membantu penanganan keluhan
                    </p>
                </div>
                <button @click="openKnowledgeBase = false">✕</button>
            </div>

            {{-- SEARCH --}}
            <div class="px-6 py-3 border-b">
                <input
                    type="text"
                    x-model="kbSearch"
                    placeholder="Cari solusi..."
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                >
            </div>

            {{-- REUSE UI --}}
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                @include('components.knowledgeBase')
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t flex justify-end">
                <button
                    @click="openKnowledgeBase = false"
                    class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">
                    Tutup
                </button>
            </div>

        </div>
    </div>
    {{-- ================= MODAL TAMBAH KNOWLEDGE BASE ================= --}}
    <div
        x-show="openSimpanKB"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg overflow-hidden">

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    Simpan ke Knowledge Base
                </h3>
                <button
                    @click="openSimpanKB = false"
                    class="text-gray-500 hover:text-gray-700 text-xl">
                    ✕
                </button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-4 space-y-4 text-sm">

                {{-- JUDUL --}}
                <div>
                    <label class="font-medium block mb-1">Judul Solusi</label>
                    <input
                        type="text"
                        x-model="kbForm.judul"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                </div>

                {{-- DEPARTEMEN --}}
                <div>
                    <label class="font-medium block mb-1">Departemen Terkait</label>
                    <select
                        x-model="kbForm.dept"
                        class="w-full border rounded-lg px-3 py-2"
                    >
                        <option value="">Pilih Departemen</option>
                        <option>Engineering</option>
                        <option>Operational</option>
                        <option>Finance</option>
                    </select>
                </div>
                
                {{-- KATEGORI (SEARCHABLE + ADD NEW) --}}
                    <div class="relative">
                        <p class="text-xs text-gray-500 mb-1">Kategori</p>

                        <div class="border rounded px-3 py-2 cursor-pointer bg-white"
                            @click="openKategori = !openKategori">
                            <span x-text="kbForm.kategori || 'Pilih kategori...'"></span>
                        </div>

                        <div x-show="openKategori"
                            x-transition
                            @click.outside="openKategori = false"
                            class="absolute z-50 w-full bg-white border rounded shadow mt-1">

                            <input
                                x-model="kategoriSearch"
                                placeholder="Cari atau tambah kategori..."
                                class="w-full border-b px-3 py-2 text-sm">

                            <div class="max-h-40 overflow-y-auto">

                                <template x-for="item in filteredKategori" :key="item">
                                    <div
                                        @click="selectKategori(item)"
                                        class="px-3 py-2 hover:bg-green-100 cursor-pointer text-sm"
                                        x-text="item">
                                    </div>
                                </template>

                                {{-- ADD NEW --}}
                                <template x-if="kategoriSearch && !kategoriList.includes(kategoriSearch)">
                                    <div
                                        @click="tambahKategoriBaru"
                                        class="px-3 py-2 text-green-600 cursor-pointer border-t text-sm">
                                        + Tambah "<span x-text="kategoriSearch"></span>"
                                    </div>
                                </template>

                            </div>
                        </div>
                    </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label class="font-medium block mb-1">Deskripsi</label>
                    <textarea
                        x-model="kbForm.deskripsi"
                        rows="2"
                        class="w-full border rounded-lg px-3 py-2"
                    ></textarea>
                </div>

                {{-- LANGKAH --}}
                <div>
                    <label class="font-medium block mb-1">Langkah Penyelesaian</label>
                    <textarea
                        x-model="kbForm.langkah"
                        rows="4"
                        class="w-full border rounded-lg px-3 py-2"
                    ></textarea>
                </div>

                {{-- LAMPIRAN --}}
                <div>
                    <label class="font-medium block mb-1">Lampiran Dokumentasi</label>

                    <input
                        type="file"
                        multiple
                        @change="handleUploadKB($event)"
                        class="text-sm"
                    >

                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(file, index) in kbForm.lampiran" :key="index">
                            <div class="border rounded px-3 py-1 text-xs bg-gray-50">
                                <span x-text="file.name"></span>
                                <button
                                    @click="hapusLampiranKB(index)"
                                    class="ml-2 text-red-500">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t flex justify-end gap-2">
                <button
                    @click="openSimpanKB = false"
                    class="px-4 py-2 rounded-lg bg-gray-100">
                    Batal
                </button>

                <button
                    @click="simpanKeKnowledgeBase"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white">
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>

</div>

<script>
function detailKeluhanApp() {
    return {
        /* ================= STATE ================= */
        openWO: false,
        openRiwayat: false,
        openLaporan: false,
        openKnowledgeBase: false,
        openSimpanKB: false,
        kbSearch: '',
        selectedKB: null,
        selectedWO: {},
        keputusanAkhir: null,
        workOrders: [],

        /* ================= DATA SOURCE ================= */
        detailKeluhan: [
            {
                keluhan: {
                    id: 1,
                    tiket: 'TCK-001',
                    unit: 'A-101',
                    nama: 'Budi Santoso',
                    telepon: '08123456789',
                    tanggal: '12 Feb 2026',
                    judul: 'AC Tidak Dingin',
                    deskripsi: 'AC ruang tamu tidak dingin sejak pagi.',
                    lampiranKeluhan: ['foto_ac_1.jpg', 'foto_ac_2.jpg'],
                    status: 'Open'
                },
                riwayat: [
                    {
                        tipe: 'system',
                        judul: 'Keluhan Masuk',
                        ket: 'Keluhan diterima oleh sistem',
                        waktu: '12 Feb 2026 09:00'
                    }
                ]
            },
            {
                keluhan: {
                    id: 2,
                    tiket: 'TCK-002',
                    unit: 'B-205',
                    nama: 'Siti Aminah',
                    telepon: '08129876543',
                    tanggal: '13 Feb 2026',
                    judul: 'Kran bocor',
                    deskripsi: 'Kran wasatafel kamar mandi bocor.',
                    lampiranKeluhan: ['lampu_mati.jpg'],
                    status: 'On Progress'
                },
                riwayat: [
                    {
                        tipe: 'system',
                        judul: 'Keluhan Masuk',
                        ket: 'Keluhan diterima oleh sistem',
                        waktu: '13 Feb 2026 08:30'
                    },
                    {
                        tipe: 'tr',
                        judul: 'TR Mengambil Keluhan',
                        ket: 'Keluhan di-assign ke tim TR',
                        waktu: '13 Feb 2026 08:45'
                    },
                    {
                        tipe: 'departemen',
                        judul: 'Menunggu laporan Work Order departemen',
                        ket: 'Work order sudah diberikan ke departemen teknik',
                        waktu: '13 Feb 2026 09:30'
                    },
                ]
            },
            {
                keluhan: {
                    id: 3,
                    tiket: 'TCK-003',
                    unit: 'C-310',
                    nama: 'Ahmad Rizki',
                    telepon: '08121234567',
                    tanggal: '14 Feb 2026',
                    judul: 'Lampu mati',
                    deskripsi: 'Lampu ruang tamu mati.',
                    lampiranKeluhan: ['lampu_mati.jpg'],
                    status: 'Close'
                },
                riwayat: [
                    {
                        tipe: 'system',
                        judul: 'Keluhan Masuk',
                        ket: 'Keluhan diterima oleh sistem',
                        waktu: '14 Feb 2026 07:15'
                    },
                    {
                        tipe: 'tr',
                        judul: 'TR Mengambil Keluhan',
                        ket: 'Keluhan di-assign ke tim TR',
                        waktu: '14 Feb 2026 07:30'
                    },
                    {
                        tipe: 'departemen',
                        judul: 'Menunggu laporan Work Order departemen',
                        ket: 'Work order sudah diberikan ke departemen teknik',
                        waktu: '14 Feb 2026 07:30'
                    },
                    {
                        tipe: 'tr',
                        judul: 'TR memberi keputusan',
                        ket: 'keputusan sudah dikirim ke penghuni ',
                        waktu: '15 Feb 2026 07:30'
                    },
                    {
                        tipe: 'tr',
                        judul: 'Keluhan Ditutup',
                        ket: 'Keluhan ditutup oleh TR',
                        waktu: '15 Feb 2026 09:00'
                    }
                ]
            }
        ],

        /* ================= DATA AKTIF ================= */
        keluhan: {},
        riwayat: [],

        // KB
        knowledgeBase: [
        {
            id: 1,
            judul: 'AC Tidak Dingin',
            deskripsi: 'Periksa filter AC, bersihkan evaporator, dan cek freon.',
            dept: 'Engineering',
            lampiran: ['ac_before.jpg'],
            relatedKeluhan: [
                {
                    id: 1,
                    tiket: 'TCK-001',
                    unit: 'A-101',
                    status: 'Close',
                    tanggal: '12 Feb 2026'
                },
                {
                    id: 4,
                    tiket: 'TCK-014',
                    unit: 'B-210',
                    status: 'Close',
                    tanggal: '20 Jan 2026'
                }
            ]
        },
        {
            id: 2,
            judul: 'Lampu Mati',
            deskripsi: 'Cek MCB, ganti lampu, dan periksa fitting.',
            dept: 'Engineering',
            lampiran: [],
            relatedKeluhan: []
        }
    ],
        get workOrdersByTiket() {
            return this.workOrders.filter(
                wo => wo.tiket === this.keluhan.tiket
            );
        },

        /* ================= KEPUTUSAN TR ================= */
        keputusan: {
            status: 'On Progress',
            catatan: '',
            lampiran: []
        },

        /* ================= FORM WO ================= */
        woForm: {
            dept: '',
            instruction: ''
        },
        kbForm: {
            judul: '',
            dept: '',
            deskripsi: '',
            lampiran: []
        },
        keputusanAkhir: {
            judul: '',
            keputusan: '',
            lampiran: [],
            tanggal: '',
            dibuatOleh: ''
        },

        workOrders: [
            {
                id: 1,
                tiket: 'TCK-002',
                no: 'WO-001',
                dept: 'Engineering',
                instruksi: 'Cek kran wastafel bocor',
                status: 'On Progress',
                petugas: 'Andi',
                laporan: [],
                lampiran: [],
                tanggal: '13 Feb 2026 09:30'
            }
        ],

        /* ================= METHODS ================= */
        handleUploadKB(event) {
            const files = Array.from(event.target.files);
            this.kbForm.lampiran.push(...files);
        },
        simpanKeputusan() {
            if (!this.keputusan.catatan.trim()) {
                alert('Catatan keputusan wajib diisi');
                return;
            }

            // SIMPAN RIWAYAT INTERNAL
            this.riwayat.push({
                tipe: 'tr',
                judul: 'Keputusan TR',
                ket: this.keputusan.catatan,
                waktu: this.now()
            });

            // JIKA STATUS CLOSE → SIMPAN KEPUTUSAN AKHIR
            if (this.keputusan.status === 'Close') {
                this.keputusanAkhir = {
                    judul: this.keputusan.judul,
                    solusi: this.keputusan.catatan,
                    lampiran: this.keputusan.lampiran.map(f => f.name),
                    tanggal: this.now(),
                    dibuatOleh: 'Tenant Relation'
                };
            }

            this.keluhan.status = this.keputusan.status;

            this.keputusan.catatan = '';
            this.keputusan.lampiran = [];
        },

        hapusLampiranKB(index) {
            this.kbForm.lampiran.splice(index, 1);
        },

        simpanKeKnowledgeBase() {
            if (!this.kbForm.judul || !this.kbForm.kategori || !this.kbForm.gejala || !this.kbForm.langkah) {
                alert('Lengkapi semua data Knowledge Base');
                return;
            }

            // Simpan ke list KB (UI)
            this.knowledgeBase.push({
            id: this.knowledgeBase.length + 1,
            judul: this.kbForm.judul,
            kategori: this.kbForm.kategori,
            gejala: this.kbForm.gejala,
            langkah: this.kbForm.langkah,
            dept: this.kbForm.dept,
            lampiran: this.kbForm.lampiran.map(f => f.name),
            relatedKeluhan: []
        });

            alert('Solusi berhasil disimpan ke Knowledge Base');

            // Reset form
            this.kbForm = {
                judul: '',
                dept: '',
                deskripsi: '',
                lampiran: []
            };

            this.openSimpanKB = false;
        },
        get filteredKnowledgeBase() {
            return this.knowledgeBase.filter(kb =>
                kb.judul.toLowerCase().includes(this.kbSearch.toLowerCase())
            );
        },

        selectKB(item) {
            this.selectedKB = item;
        },
        simpanKeputusan() {
            if (!this.keputusan.catatan.trim()) {
                alert('Catatan keputusan wajib diisi');
                return;
            }

            this.riwayat.push({
                tipe: 'tr',
                judul: 'Keputusan TR',
                ket: this.keputusan.catatan,
                waktu: this.now()
            });

            this.keputusan.catatan = '';
            this.keputusan.lampiran = [];
        },

        kirimWO() {
            if (!this.woForm.dept || !this.woForm.instruction) {
                alert('Lengkapi data WO');
                return;
            }

            const id = this.workOrders.length + 1;

            this.workOrders.push({
                id,
                tiket: this.keluhan.tiket, // 🔥 LINK KE KELUHAN
                no: `WO-${String(id).padStart(3, '0')}`,
                dept: this.woForm.dept,
                instruksi: this.woForm.instruction,
                status: 'On Progress',
                petugas: '-',
                laporan: '',
                lampiran: [],
                tanggal: this.now()
            });

            this.riwayat.push({
                tipe: 'tr',
                judul: 'Work Order Dibuat',
                ket: `WO dikirim ke departemen ${this.woForm.dept}`,
                waktu: this.now()
            });

            this.woForm = { dept: '', instruction: '' };
            this.openWO = false;
        },

        bukaLaporanWO(wo) {
            this.selectedWO = wo;
            this.openLaporan = true;
        },

        now() {
            const d = new Date();
            return d.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        /* ================= INIT ================= */
        init() {
            // Ambil parameter ?id= dari URL
            const params = new URLSearchParams(window.location.search);
            const id = parseInt(params.get('id'));

            // Cari data sesuai id
            const data = this.detailKeluhan.find(d => d.keluhan.id === id);

            if (data) {
                this.keluhan = data.keluhan;
                this.riwayat = data.riwayat;
            } else {
                alert('Data keluhan tidak ditemukan');
            }
        }
    }
}
</script>
@endsection