@extends('layouts.app')

@section('title', 'Detail Penanganan Keluhan')

@section('content')
<div x-data="detailKeluhanApp()" class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800" x-text="keluhan.judul"></h1>
            <p class="text-sm text-gray-500">
                No Tiket: <span x-text="keluhan.tiket"></span>
            </p>
        </div>
        <a href="/daftar-penanganan" class="text-sm text-blue-600 hover:underline">← Kembali</a>
    </div>

    {{-- ================= INFO ================= --}}
    <div class="grid grid-cols-2 gap-4 text-sm bg-white p-6 rounded-xl shadow">
        <p><b>No Unit</b><br><span x-text="keluhan.unit"></span></p>
        <p><b>Nama Penghuni</b><br><span x-text="keluhan.nama"></span></p>
        <p><b>No Telepon</b><br><span x-text="keluhan.telepon"></span></p>
        <p><b>Waktu</b><br><span x-text="keluhan.waktu"></span></p>
        <p><b>Status Keluhan</b><br>
            <span class="inline-block text-xs px-2 py-1 rounded"
                :class="statusClass(keluhan.status)"
                x-text="formatStatus(keluhan.status)">
            </span>
        </p>
    </div>

    {{-- ================= DESKRIPSI ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <div class="flex justify-between items-center">
            <p class="font-semibold">Keluhan</p>
        </div>
        <div class="pl-4 space-y-4">
            <div>
                <p class="text-sm font-medium mb-1">Judul Keluhan</p>
                <p class="text-gray-600" x-text="keluhan.judul"></p>
            </div>
            <div>
                <p class="text-sm font-medium mb-1">Deskripsi Keluhan</p>
                <p class="text-gray-600" x-text="keluhan.deskripsi"></p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <template x-for="file in keluhan.lampiranKeluhan" :key="file">
                    <button
                        @click="previewFile = file; openPreview = true" 
                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:underline">
                        📎 <span x-text="file.split('/').pop()"></span>
                    </button>
                </template>
                <template x-if="!keluhan.lampiranKeluhan || keluhan.lampiranKeluhan.length === 0">
                    <p class="text-xs text-gray-400 italic">Tidak ada lampiran</p>
                </template>
            </div>
        </div>
    </div>

    {{-- ================= STATUS KELUHAN ================= --}}
    <div class="bg-white p-4 rounded-xl border space-y-3">
        <h3 class="font-semibold text-sm">Status Keluhan</h3>
        <select
            x-model="keputusan.status"
            @change="confirmUpdateStatus"
            :disabled="normalizeStatus(keluhan.status) === 'close'"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            <option value="open">Open</option>
            <option value="on_progress">On Progress</option>
        </select>
        <p class="text-xs text-gray-500">Status ini akan langsung terlihat oleh penghuni</p>
        <p class="text-xs text-gray-400 italic">Status <b>Close</b> hanya bisa dilakukan melalui keputusan akhir</p>
        <template x-if="normalizeStatus(keluhan.status) === 'close'">
            <div class="text-xs text-green-600 font-medium">
                Keluhan sudah ditutup dan tidak dapat diubah
            </div>
        </template>
    </div>

    {{-- ================= WORK ORDER ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-5">
        <div class="flex justify-between items-center">
            <h3 class="font-semibold">Work Order</h3>
            <button
                x-show="normalizeStatus(keluhan.status) !== 'close' && !sudahAdaWO"
                @click="openWO = true"
                class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                + Buat Work Order
            </button>
        </div>
        <template x-if="workOrdersByTiket.length">
            <template x-for="(wo, index) in workOrdersByTiket" :key="wo.id">
                <div class="border rounded-lg p-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-medium text-gray-700"><span x-text="wo.no"></span></p>
                        <span class="text-xs px-2 py-1 rounded capitalize"
                            :class="statusClass(wo.status)"
                            x-text="formatStatus(wo.status)">
                        </span>
                    </div>
                    <div class="text-sm space-y-1">
                        <p><b>Departemen:</b> <span x-text="wo.dept"></span></p>
                        <p><b>Tanggal:</b> <span x-text="wo.tanggal"></span></p>
                        <p><b>Lokasi:</b> <span x-text="wo.lokasi"></span></p>
                        <p class="text-gray-600"><b>Instruksi:</b> <span x-text="wo.instruksi"></span></p>
                    </div>
                    <div class="pt-2">
                        <button
                            @click="bukaLaporanWO(wo)"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                            Lihat Laporan
                        </button>
                    </div>
                </div>
            </template>
        </template>
        <template x-if="!workOrdersByTiket.length">
            <p class="text-sm text-gray-500 italic">Belum ada Work Order untuk tiket ini.</p>
        </template>
    </div>

    {{-- ================= KEPUTUSAN PENANGANAN ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">Keputusan Penanganan</h3>
            <div class="flex gap-4 items-center">
                
                {{-- RIWAYAT --}}
                <button
                    @click="openRiwayat = true"
                    class="text-sm text-blue-600 hover:underline">
                    Lihat Riwayat
                </button>
            </div>
        </div>

        <template x-if="normalizeStatus(keluhan.status) === 'close'">
            <div class="space-y-3">
                <div class="text-sm text-gray-500 italic bg-gray-50 border rounded-lg p-3">
                    Keluhan sudah ditutup. Riwayat keputusan tetap dapat dilihat,
                    namun tidak dapat menambahkan keputusan baru.
                </div>
            </div>
        </template>

        <template x-if="normalizeStatus(keluhan.status) !== 'close'">
            <div class="pl-4 space-y-4">
                <div>
                    <label class="text-sm font-medium mb-1 block">Judul Penanganan</label>
                    <input type="text" x-model="keputusan.judul"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        placeholder="Masukkan judul keputusan">
                </div>
                <div>
                    <label class="text-sm font-medium mb-1 block">Catatan Penanganan</label>
                    <textarea x-model="keputusan.deskripsi"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        rows="3" placeholder="Masukkan deskripsi keputusan"></textarea>
                </div>
                <div>
                    <label class="text-sm font-medium mb-1 block">Lampiran Dokumentasi</label>
                    <input type="file" multiple @change="handleUploadKeputusan($event)" class="text-sm">
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(file, index) in keputusan.lampiran" :key="index">
                            <div class="relative border rounded px-3 py-1 text-xs bg-gray-50 flex items-center gap-2">

                                <span x-text="file.name"></span>

                                <!-- 🔥 PREVIEW BUTTON -->
                                <button 
                                    @click="openPreviewFile(file)"
                                    class="text-blue-600 hover:underline">
                                    Preview
                                </button>

                                <!-- DELETE -->
                                <button 
                                    @click="hapusLampiranKeputusan(index)"
                                    class="text-red-500 hover:text-red-700">
                                    ✕
                                </button>

                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button @click="simpanKeputusan"
                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                        Update Penanganan
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- ================= KEPUTUSAN UNTUK PENGHUNI ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <h3 class="font-semibold text-gray-800">Keputusan / Solusi untuk Penghuni</h3>

        <template x-if="normalizeStatus(keluhan.status) !== 'close'">
            <div class="pl-4 space-y-4">
                <div>
                    <label class="text-sm font-medium mb-1 block">Deskripsi Keputusan</label>
                    <textarea x-model="keputusanAkhir.keputusan"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        rows="3" placeholder="Masukkan keputusan"></textarea>
                </div>
                <div>
                    <label class="text-sm font-medium mb-1 block">Lampiran Dokumentasi</label>
                    <input type="file" multiple x-ref="fileKeputusan"
                        @change="previewFiles = Array.from($event.target.files)"
                        class="text-sm">
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(file, index) in previewFiles" :key="index">
                            <div class="relative border rounded px-3 py-1 text-xs bg-gray-50 flex items-center gap-2">
                                <span x-text="file.name"></span>
                                <button @click="openPreviewFile(file)" class="text-blue-600 hover:underline">Preview</button>
                                <button @click="previewFiles.splice(index,1)" class="text-red-500 hover:text-red-700">✕</button>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-2">

                    <button @click="simpanKeputusanAkhir"
                        class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                        Simpan Keputusan
                    </button>
                </div>
            </div>
        </template>

        <template x-if="normalizeStatus(keluhan.status) === 'close'">

            <div class="space-y-4">

                {{-- INFO --}}
                <div class="text-sm text-gray-500 italic bg-gray-50 border rounded-lg p-3">
                    Keluhan sudah selesai. Form keputusan tidak dapat diubah.
                </div>

                {{-- KEPUTUSAN --}}
                <div>

                    <div class="pl-4 space-y-2">

                        <p class="text-sm text-gray-700 whitespace-pre-line"
                            x-text="keluhan.keputusan || '-'">
                        </p>

                        <p class="text-xs text-gray-400"
                            x-text="keluhan.tanggalKeputusan">
                        </p>

                    </div>
                </div>

                {{-- LAMPIRAN --}}
                <div>

                    <template x-if="keluhan.lampiranKeputusan.length">

                        <div class="flex flex-wrap gap-2">

                            <template
                                x-for="(file, i) in keluhan.lampiranKeputusan"
                                :key="i">

                                <button
                                    @click="openPreviewFile(file)"
                                    class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:underline">
                                    
                                    📎 Lampiran Keputusan
                                    <span x-text="i + 1"></span>

                                </button>

                            </template>

                        </div>

                    </template>

                    <template x-if="!keluhan.lampiranKeputusan.length">

                        <p class="text-xs text-gray-400 italic">
                            Tidak ada lampiran keputusan
                        </p>

                    </template>

                </div>

            </div>

        </template>
    </div>

    {{-- ================= MODAL PREVIEW FILE (untuk URL string) ================= --}}
    <div x-show="openPreview && typeof previewFile === 'string'" x-cloak
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-[9999]">
        <div class="bg-white w-full max-w-4xl rounded-xl p-4 relative">
            <button @click="openPreview = false"
                class="absolute top-3 right-3 text-xl text-gray-600 hover:text-black">✕</button>
            <p class="text-sm font-semibold mb-3" x-text="previewFile"></p>
            <template x-if="typeof previewFile === 'string' && isImage(previewFile)">
                <img :src="'/storage/' + previewFile" class="max-h-[70vh] mx-auto rounded">
            </template>
            <template x-if="typeof previewFile === 'string' && isPDF(previewFile)">
                <iframe :src="'/storage/' + previewFile" class="w-full h-[70vh] rounded"></iframe>
            </template>
            <template x-if="typeof previewFile === 'string' && !isImage(previewFile) && !isPDF(previewFile)">
                <div class="text-center text-gray-500 py-10">Preview tidak tersedia</div>
            </template>
        </div>
    </div>

    {{-- ================= MODAL PREVIEW FILE (untuk File object) ================= --}}
    <div x-show="openPreview && previewFile && typeof previewFile === 'object'" x-cloak
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-[9999]">
        <div class="bg-white w-full max-w-4xl rounded-xl p-4 relative">
            <button @click="openPreview = false"
                class="absolute top-3 right-3 text-xl text-gray-600 hover:text-black">✕</button>
            <p class="text-sm font-semibold mb-3" x-text="previewFile?.name"></p>
            <template x-if="previewFile && typeof previewFile === 'object' && previewFile.type && previewFile.type.startsWith('image/')">
                <img :src="URL.createObjectURL(previewFile)" class="max-h-[70vh] mx-auto rounded">
            </template>
            <template x-if="previewFile && typeof previewFile === 'object' && previewFile.type === 'application/pdf'">
                <iframe :src="URL.createObjectURL(previewFile)" class="w-full h-[70vh] rounded"></iframe>
            </template>
            <template x-if="previewFile && typeof previewFile === 'object' && previewFile.type && !previewFile.type.startsWith('image/') && previewFile.type !== 'application/pdf'">
                <div class="text-center text-gray-500 py-10">Preview tidak tersedia untuk file ini</div>
            </template>
        </div>
    </div>

    {{-- ================= MODAL RIWAYAT PENANGANAN ================= --}}
    <div x-show="openRiwayat" x-cloak x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white w-full max-w-xl rounded-xl shadow-lg overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Riwayat Penanganan</h3>
                <button @click="openRiwayat = false"
                    class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
            </div>
            <div class="px-6 py-4 space-y-4 text-sm max-h-[400px] overflow-y-auto">
                <template x-for="(r, index) in riwayat" :key="index">
                    <div class="relative pl-6 py-3 rounded-md border-l-2"
                        :class="statusClassRiwayat(r.status)">
                        <span class="absolute -left-2 top-4 w-3 h-3 rounded-full"
                            :class="{
                                'bg-blue-500': normalizeStatus(r.status) === 'open',
                                'bg-yellow-500': normalizeStatus(r.status) === 'on_progress',
                                'bg-orange-500': normalizeStatus(r.status) === 'waiting',
                                'bg-green-500': normalizeStatus(r.status) === 'close'
                            }">
                        </span>
                        <p class="font-medium text-gray-800" x-text="r.judul"></p>
                        <p class="text-gray-600 mt-1" x-text="r.deskripsi"></p>
                        <div class="flex flex-wrap gap-2 mt-2" x-show="r.lampiran && r.lampiran.length">
                            <template x-for="file in r.lampiran" :key="file">
                                <button @click="openPreviewFile(file)"
                                    class="px-3 py-1 text-xs rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                                    <span x-text="file.split('/').pop()"></span>
                                </button>
                            </template>
                        </div>
                        <p class="text-xs text-gray-400 mt-2" x-text="r.waktu"></p>
                    </div>
                </template>
                <template x-if="riwayat.length === 0">
                    <p class="text-center text-gray-400 py-6">Belum ada riwayat penanganan</p>
                </template>
            </div>
            <div class="px-6 py-4 border-t flex justify-end">
                <button @click="openRiwayat = false"
                    class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL DETAIL LAPORAN WO ================= --}}
    <div x-show="openLaporan" x-cloak
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-4xl rounded-xl p-6 space-y-5 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <h3 class="text-lg font-semibold">Work Order Report</h3>
                    <p class="text-xs text-gray-500">No WO: <span x-text="selectedWO.no"></span></p>
                </div>
                <button @click="openLaporan=false" class="text-xl">✕</button>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <p><b>Ticket Keluhan</b><br><span x-text="keluhan.tiket"></span></p>
                <p><b>Tanggal WO</b><br><span x-text="selectedWO.tanggal"></span></p>
                <p><b>Requestor</b><br><span x-text="keluhan.nama"></span></p>
                <p><b>Departemen</b><br><span x-text="selectedWO.dept"></span></p>
                <p><b>Petugas</b><br><span x-text="selectedWO.petugas"></span></p>
                <p><b>Status</b><br>
                    <span :class="statusClass(selectedWO.status)"
                        x-text="formatStatus(selectedWO.status)">
                    </span>
                </p>
            </div>
            <div class="border rounded-lg p-4 text-sm space-y-1">
                <p class="font-medium">Lokasi</p>
                <p x-text="selectedWO.lokasi"></p>
            </div>
            <div class="space-y-1">
                <p class="font-medium text-sm">Instruksi Detail</p>
                <div class="bg-gray-100 rounded-lg p-3 text-sm" x-text="selectedWO.instruksi"></div>
            </div>
            <div class="bg-white rounded-xl p-4 space-y-4">
                <p class="font-medium text-sm">Riwayat Penanganan Pekerjaan</p>
                <template x-if="selectedWO.laporan && selectedWO.laporan.length">
                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                        <template x-for="(lapor, index) in selectedWO.laporan" :key="index">
                            <div class="relative pl-5 py-3 rounded-md" :class="statusClassRiwayat(lapor.status)">
                                <p class="font-medium text-gray-800" x-text="lapor.judul"></p>
                                <p class="text-gray-600 mt-1" x-text="lapor.deskripsi"></p>
                                <div class="flex flex-wrap gap-2 mt-2"
                                    x-show="lapor.lampiran && lapor.lampiran.length">
                                    <template x-for="(file, i) in lapor.lampiran" :key="i">
                                        <button @click="openPreviewFile(file)"
                                            class="px-3 py-1 text-xs rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                                            <span x-text="file.split('/').pop()"></span>
                                        </button>
                                    </template>
                                </div>
                                <p class="text-xs text-gray-400 mt-2" x-text="lapor.waktu"></p>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="!selectedWO.laporan || !selectedWO.laporan.length">
                    <p class="text-sm text-gray-400 italic text-center py-4">
                        Belum ada laporan pekerjaan dari departemen.
                    </p>
                </template>
            </div>
        </div>
    </div>

    {{-- ================= MODAL BUAT WORK ORDER ================= --}}
    <div x-show="openWO" x-cloak x-transition
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-3xl rounded-xl p-6 space-y-5 overflow-y-auto max-h-[90vh]">
            <h3 class="text-lg font-semibold text-gray-800">Buat Work Order</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <label class="font-medium">Ticket Keluhan</label>
                    <input class="w-full border rounded px-3 py-1 bg-gray-100" x-model="keluhan.tiket" disabled>
                </div>
                <div>
                    <label class="font-medium">Requestor</label>
                    <input class="w-full border rounded px-3 py-1 bg-gray-100" x-model="keluhan.nama" disabled>
                </div>
                <div>
                    <label class="font-medium">Nomor Telepon</label>
                    <input class="w-full border rounded px-3 py-1 bg-gray-100" x-model="keluhan.telepon" disabled>
                </div>
                <div class="col-span-2">
                    <label class="font-medium">Department</label>
                    <select class="w-full border rounded px-3 py-1" x-model="woForm.dept">
                        <option value="">Pilih</option>
                        <template x-for="d in departemenList" :key="d">
                            <option
                                :value="d.id"
                                x-text="d.nama_departemen">
                            </option>
                        </template>
                    </select>
                </div>
            </div>
            <div>
                <label class="font-medium text-sm">Lokasi</label>
                <input type="text" x-model="woForm.lokasi"
                    class="w-full border rounded px-3 py-2 text-sm"
                    placeholder="Contoh: Tower A Lt 10 Unit A-1001 / Lobby / Parkiran">
            </div>
            <div>
                <label class="font-medium text-sm">Instruksi</label>
                <textarea x-model="woForm.instruction"
                    class="w-full border rounded px-3 py-2 text-sm"
                    placeholder="Instruksi pekerjaan untuk departemen"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-3">
                <button @click="openWO=false"
                    class="border px-4 py-2 rounded text-gray-700 hover:bg-gray-50">Batal</button>
                <button @click="kirimWO"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Kirim Work Order</button>
            </div>
        </div>
    </div>
</div>
<script>
window.detailKeluhan = @json($data);
window.departemen = @json($departemen);
window.knowledgeBase = @json($knowledgeBase ?? []);

function detailKeluhanApp() {
    return {

        /* ================= STATE ================= */
        openWO: false,
        openRiwayat: false,
        openLaporan: false,
        previewFile: null,
        openPreview: false,
        openKategori: false,
        openKategoriForm: false,
        kategoriSearch: '',
        kategoriFormSearch: '',
        showAddKategori: false,
        newKategori: '',
      
        selectedKategori: '',
        kategoriList: [],
        keluhan: {},
        riwayat: [],
        selectedWO: {},
        departemenList: [],
        previewFiles: [],
        kbDuplicates: [],
        knowledgeBase: [],


        workOrders: [],
        woForm: {
            dept: '',
            instruction: '',
            lokasi: ''
        },

        keputusan: {
            judul: '',
            status: 'on_progress',
            deskripsi: '',
            lampiran: []
        },

        keputusanAkhir: {
            keputusan: ''
        },

        /* ================= INIT ================= */
        init() {
            const data = window.detailKeluhan;
            this.keluhan = {
                id: data.id,
                tiket: data.nomor_tiket,
                unit: data.unit,
                tower: data.tower ?? '-',
                lantai: data.lantai ?? '-',
                nama: data.penghuni,
                telepon: data.telepon,
                waktu: data.waktu,
                judul: data.pengajuan.judul,
                deskripsi: data.pengajuan.deskripsi,
                keputusan: data.keputusan_akhir || '',
                tanggalKeputusan:
                    data.tanggal_keputusan_format || '-',
                lampiranKeputusan:
                    data.lampiran_keputusan || [],
                lampiranKeluhan:
                    data.pengajuan.lampiran || [],
                status: this.normalizeStatus(data.status)
            };
            this.departemenList = window.departemen || [];
            this.keputusan.status = this.normalizeStatus(data.status);
            this.riwayat = data.riwayat_penanganan || [];
            this.workOrders = data.work_orders || [];
            this.kategoriList = [...new Set(this.knowledgeBase.map(k => k.kategori).filter(Boolean))];
            
        },

        /* ================= COMPUTED ================= */
        get workOrdersByTiket() {
            return this.workOrders;
        },

        get sudahAdaWO() {
            return this.workOrders.length > 0;
        },
       
        /* ================= HELPERS ================= */
        isImage(file) {
            return /\.(jpg|jpeg|png|gif)$/i.test(file);
        },

        isPDF(file) {
            return /\.pdf$/i.test(file);
        },

        normalizeStatus(status) {
            return (status || '').toLowerCase().trim().replace(/\s+/g, '_');
        },

        formatStatus(status) {
            const s = this.normalizeStatus(status);
            if (s === 'unassigned') return 'Unassigned';
            if (s === 'open') return 'Open';
            if (s === 'on_progress') return 'On Progress';
            if (s === 'waiting') return 'Waiting';
            if (s === 'close') return 'Close';
            return status;
        },

        statusClass(status) {
            const s = this.normalizeStatus(status);
            return {
                'bg-blue-100 text-blue-700': s === 'open',
                'bg-yellow-100 text-yellow-700': s === 'on_progress',
                'bg-orange-100 text-orange-700': s === 'waiting',
                'bg-green-100 text-green-700': s === 'close',
                'bg-gray-100 text-gray-700': !['open','on_progress','waiting','close'].includes(s)
            };
        },

        statusClassRiwayat(status) {
            const s = this.normalizeStatus(status);
            return {
                'border-l-4 border-blue-500 bg-blue-50/30': s === 'open',
                'border-l-4 border-yellow-500 bg-yellow-50/30': s === 'on_progress',
                'border-l-4 border-orange-500 bg-orange-50/30': s === 'waiting',
                'border-l-4 border-green-500 bg-green-50/30': s === 'close'
            };
        },

        now() {
            const d = new Date();
            return d.toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        },

        highlightText(text) {
            if (!this.searchDiagnosis || !text) return text;

            let keyword = this.searchDiagnosis.toLowerCase();

            return text.replace(new RegExp(keyword, 'gi'),
                match => `<span class="bg-yellow-200">${match}</span>`
            );
        },

        openPreviewFile(file) {
            this.previewFile = file;
            this.openPreview = true;
        },

  
        /* ================= STATUS ================= */
        confirmUpdateStatus() {
            const oldStatus = this.keluhan.status;
            const newStatus = this.normalizeStatus(this.keputusan.status);
            if (oldStatus === newStatus) return;
            Swal.fire({
                title: 'Ubah Status?',
                text: 'Status akan diubah menjadi ' + this.formatStatus(newStatus),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.updateStatusLangsung();
                } else {
                    this.keputusan.status = oldStatus;
                }
            });
        },

        updateStatusLangsung() {
            if (this.normalizeStatus(this.keluhan.status) === 'close') return;
            fetch(`/keluhan/${this.keluhan.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    status: this.normalizeStatus(this.keputusan.status),
                    deskripsi: null
                })
            })
            .then(res => res.json())
            .then(res => {
                const newStatus = this.normalizeStatus(res.status);
                this.keluhan.status = newStatus;
                this.riwayat.push({
                    judul: 'Update Status',
                    deskripsi: 'Status diubah menjadi ' + this.formatStatus(newStatus),
                    waktu: this.now(),
                    status: newStatus,
                    lampiran: []
                });
                Swal.fire({ icon: 'success', title: 'Status diperbarui', timer: 1000, showConfirmButton: false });
            })
            .catch(() => {
                Swal.fire('Error!', 'Gagal update status', 'error');
            });
        },

        /* ================= KEPUTUSAN PENANGANAN ================= */
        simpanKeputusan() {
            if (!this.keputusan.judul.trim()) {
                Swal.fire('Oops!', 'Judul wajib diisi', 'warning');
                return;
            }
            if (!this.keputusan.deskripsi.trim()) {
                Swal.fire('Oops!', 'Deskripsi wajib diisi', 'warning');
                return;
            }
            Swal.fire({
                title: 'Simpan Penanganan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan'
            }).then((result) => {
                if (result.isConfirmed) this.prosesSimpan();
            });
        },

        prosesSimpan() {
            let formData = new FormData();
            formData.append('judul', this.keputusan.judul);
            formData.append('deskripsi', this.keputusan.deskripsi);
            this.keputusan.lampiran.forEach(file => formData.append('lampiran[]', file));
            fetch(`/keluhan/${this.keluhan.id}/penanganan`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            })
            .then(async res => { const data = await res.json(); if (!res.ok) throw data; return data; })
            .then(res => {
                Swal.fire('Berhasil!', res.message, 'success');
                this.riwayat.push({
                    judul: res.data.judul,
                    deskripsi: res.data.deskripsi,
                    waktu: res.data.waktu,
                    status: res.data.status,
                    lampiran: res.data.lampiran
                });
                this.keputusan = { judul: '', status: 'on_progress', deskripsi: '', lampiran: [] };
            })
            .catch(err => {
                Swal.fire('Error!', err?.error || err?.message || 'Terjadi kesalahan', 'error');
            });
        },

        handleUploadKeputusan(e) {
            const files = Array.from(e.target.files);
            this.keputusan.lampiran.push(...files);
        },

        hapusLampiranKeputusan(index) {
            this.keputusan.lampiran.splice(index, 1);
        },

        /* ================= KEPUTUSAN AKHIR ================= */
        simpanKeputusanAkhir() {
        // VALIDASI
        if (!this.keputusanAkhir.keputusan.trim()) {

            Swal.fire(
                'Oops!',
                'Lengkapi data keputusan',
                'warning'
            );

            return;
        }

        // KONFIRMASI
        Swal.fire({

            title: 'Kirim Keputusan?',

            text:
                'Keputusan akan dikirim ke penghuni dan keluhan akan ditutup',

            icon: 'question',

            showCancelButton: true,

            confirmButtonText: 'Ya, kirim',

            cancelButtonText: 'Batal',

            confirmButtonColor: '#16a34a'

        }).then((result) => {

            if (!result.isConfirmed) return;

            let formData = new FormData();

            formData.append(
                'keputusan',
                this.keputusanAkhir.keputusan
            );

            let files = this.$refs.fileKeputusan.files;

            for (let i = 0; i < files.length; i++) {

                formData.append(
                    'lampiran[]',
                    files[i]
                );
            }

            fetch(
                `/keluhan/${this.keluhan.id}/keputusan-akhir`,
                {

                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },

                    body: formData
                }
            )

            .then(async res => {

                const data = await res.json();

                if (!res.ok) throw data;

                return data;
            })

            .then(res => {

                Swal.fire(
                    'Berhasil!',
                    res.message,
                    'success'
                );

                this.keluhan.status = 'close';

                this.keluhan.keputusan =
                    this.keputusanAkhir.keputusan;

                this.keluhan.tanggalKeputusan =
                    res.data.tanggal_keputusan;

                this.keluhan.lampiranKeputusan =
                    res.data.lampiran_keputusan;

                this.riwayat.push({

                    deskripsi:
                        this.keputusanAkhir.keputusan,

                    waktu: this.now(),

                    status: 'close',

                    lampiran: []
                });

                this.keputusanAkhir = {
                    keputusan: ''
                };

                this.previewFiles = [];

                this.$refs.fileKeputusan.value = null;
            })

            .catch(err => {

                Swal.fire(

                    'Error!',

                    err?.message ||
                    'Gagal menyimpan keputusan',

                    'error'
                );
            });
        });
        },

        /* ================= WORK ORDER ================= */
        kirimWO() {
            if (this.sudahAdaWO) {
                Swal.fire('Info', 'Work Order sudah dibuat untuk keluhan ini', 'info');
                return;
            }
            if (!this.woForm.dept || !this.woForm.instruction) {
                Swal.fire('Oops!', 'Lengkapi data WO', 'warning');
                return;
            }
            if (!this.woForm.lokasi.trim()) {
                Swal.fire('Oops!', 'Lokasi wajib diisi', 'warning');
                return;
            }
            fetch(`/keluhan/${this.keluhan.id}/work-order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    departemen: this.woForm.dept,
                    instruksi: this.woForm.instruction,
                    lokasi: this.woForm.lokasi
                })
            })
            .then(async res => { const data = await res.json(); if (!res.ok) throw data; return data; })
            .then(res => {
                this.workOrders.push(res.data);
                this.woForm = { dept: '', instruction: '', lokasi: '' };
                this.openWO = false;
                Swal.fire('Berhasil!', res.message, 'success');
            })
            .catch(err => {
                let msg =err?.message ||'Gagal membuat WO';
                if (err?.errors) {
                    msg = Object.values(err.errors)
                        .flat()
                        .join('\n');
                }
                Swal.fire('Error!', msg,'error');
                });
        },

        bukaLaporanWO(wo) {
            const laporan = (wo.laporan || []).map(item => ({
                judul: item.judul || 'Update Penanganan',
                deskripsi: item.deskripsi || '',
                waktu: item.waktu,
                status: item.status,
                lampiran: item.lampiran || []
            }));
            this.selectedWO = { ...wo, laporan };
            this.openLaporan = true;
        }
    }
}
</script>
@endsection