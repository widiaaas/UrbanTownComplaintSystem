@extends('layouts.app')

@section('title', 'Detail Work Order')

@section('content')
<div x-data="detailWOApp()" x-init="init()" class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Work Order</h1>
            <p class="text-sm text-gray-500">No WO: <span x-text="wo.no"></span></p>
        </div>
        <a href="/daftar-work-order" class="text-sm text-blue-600 hover:underline">← Kembali</a>
    </div>

    {{-- ================= INFO UTAMA WO ================= --}}
    <div class="grid grid-cols-2 gap-4 text-sm bg-white p-6 rounded-xl shadow">
        <p><b>Departemen</b><br><span x-text="wo.dept"></span></p>
        <p><b>TR Penanggung Jawab</b><br><span x-text="wo.tr"></span></p>
        <p><b>Tanggal WO</b><br><span x-text="wo.tanggal"></span></p>
        <p><b>Status WO</b><br>
            <span class="inline-block text-xs px-2 py-1 rounded"
                :class="statusClass(wo.status)"
                x-text="formatStatus(wo.status)">
            </span>
        </p>
    </div>

    {{-- ================= INSTRUKSI PEKERJAAN ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-5">
        <h3 class="font-semibold">Instruksi Pekerjaan</h3>
        <div>
            <p class="text-sm font-medium mb-1">Instruksi</p>
            <div class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700" x-text="wo.instruksi || '-'"></div>
        </div>
        <div>
            <p class="text-sm font-medium mb-1">Lokasi</p>
            <div class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700" x-text="wo.lokasi || '-'"></div>
        </div>
    </div>

    {{-- ================= RIWAYAT PENANGANAN ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">Riwayat Penanganan</h3>
        </div>
        <template x-if="wo.laporan && wo.laporan.length">
            <div class="relative">
                <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2">
                    <template x-for="(lapor, index) in wo.laporan" :key="index">
                        <div class="relative pl-6 py-3 rounded-md border-l-2"
                            :class="statusClassRiwayat(lapor.status)">
                            <span class="absolute -left-2 top-4 w-3 h-3 rounded-full"
                                :class="{
                                    'bg-blue-500': normalizeStatus(lapor.status) === 'open',
                                    'bg-yellow-500': normalizeStatus(lapor.status) === 'on_progress',
                                    'bg-orange-500': normalizeStatus(lapor.status) === 'waiting',
                                    'bg-green-500': normalizeStatus(lapor.status) === 'close'
                                }"></span>
                            <p class="font-medium text-gray-800" x-text="lapor.judul"></p>
                            <p class="text-gray-600 mt-1" x-text="lapor.deskripsi"></p>
                            <div class="flex flex-wrap gap-2 mt-2" x-show="lapor.lampiran && lapor.lampiran.length">
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
                <div class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
            </div>
        </template>
        <template x-if="!wo.laporan || !wo.laporan.length">
            <p class="text-sm text-gray-400 italic text-center py-4">
                Belum ada laporan pekerjaan dari departemen.
            </p>
        </template>
    </div>

    {{-- ================= MODAL PREVIEW FILE ================= --}}
    <div x-show="openPreview" x-cloak
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-4xl rounded-xl p-4 relative">

            <button @click="openPreview = false"
                class="absolute top-3 right-3 text-xl">✕</button>

            <p class="text-sm font-semibold mb-3"
            x-text="typeof previewFile === 'object' ? previewFile.name : previewFile">
            </p>

            <!-- 🔥 IMAGE -->
            <template x-if="(typeof previewFile === 'object' && previewFile.type.startsWith('image/')) || isImage(previewFile)">
                <img 
                    :src="typeof previewFile === 'object' ? URL.createObjectURL(previewFile) : '/storage/' + previewFile"
                    class="max-h-[70vh] mx-auto rounded">
            </template>

            <!-- 🔥 PDF -->
            <template x-if="(typeof previewFile === 'object' && previewFile.type === 'application/pdf') || isPDF(previewFile)">
                <iframe 
                    :src="typeof previewFile === 'object' ? URL.createObjectURL(previewFile) : '/storage/' + previewFile"
                    class="w-full h-[70vh] rounded">
                </iframe>
            </template>

            <!-- 🔥 OTHER -->
            <template x-if="true">
                <div x-show="!( (typeof previewFile === 'object' && (previewFile.type.startsWith('image/') || previewFile.type === 'application/pdf')) || isImage(previewFile) || isPDF(previewFile) )"
                    class="text-center text-gray-500 py-10">
                    Preview tidak tersedia
                </div>
            </template>

        </div>
</div>
    {{-- ================= STATUS WO ================= --}}
    <div class="bg-white p-4 rounded-xl border space-y-2">
        <h3 class="font-semibold text-sm">Status Work Order</h3>
        <select x-model="newStatus" @change="ubahStatus"
            :disabled="normalizeStatus(wo.status) === 'close'"
            class="w-full border rounded-lg px-3 py-2">
            <option value="Open">Open</option>
            <option value="On Progress">On Progress</option>
            <option value="Waiting">Waiting</option>
            <option value="Close">Close</option>
        </select>
    </div>

    {{-- ================= FORM PENANGANAN ================= --}}
    <template x-if="normalizeStatus(wo.status) !== 'close'">
        <div class="bg-white p-6 rounded-xl shadow space-y-4">
            <h3 class="font-semibold">Form Penanganan WO</h3>
            <div>
                <label class="text-sm font-medium mb-1 block">Judul Penanganan</label>
                <input type="text" x-model="penanganan.judul"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    placeholder="Masukkan judul penanganan">
            </div>
            <div>
                <label class="text-sm font-medium mb-1 block">Deskripsi Penanganan</label>
                <textarea x-model="penanganan.deskripsi"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    rows="3" placeholder="Masukkan deskripsi penanganan"></textarea>
            </div>
            <div>
                <label class="text-sm font-medium mb-1 block">Lampiran Dokumentasi</label>
                <input type="file" multiple @change="handleUploadPenanganan($event)" class="text-sm">
                <div class="flex flex-wrap gap-2 mt-2">
                <template x-for="(file, index) in penanganan.lampiran" :key="index">
                    <div class="relative border rounded px-3 py-1 text-xs bg-gray-50 flex items-center gap-2">

                        <span x-text="file.name"></span>

                        <!-- 🔥 PREVIEW -->
                        <button 
                            @click="openPreviewFile(file)"
                            class="text-blue-600 hover:underline">
                            Preview
                        </button>

                        <!-- DELETE -->
                        <button 
                            @click="hapusLampiranPenanganan(index)"
                            class="text-red-500 hover:text-red-700">
                            ✕
                        </button>

                    </div>
                </template>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button @click="openKnowledgeBase = true"
                    class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                    Lihat Knowledge Base
                </button>

                <button @click="simpanPenanganan"
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    Simpan Penanganan
                </button>
            </div>
        </div>
    </template>

    <template x-if="normalizeStatus(wo.status) === 'close'">

        <div class="bg-white p-6 rounded-xl shadow">

            <div class="flex justify-between items-center">

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Knowledge Base
                    </h3>

                    <p class="text-sm text-gray-500">
                        Simpan solusi final ke knowledge base
                    </p>
                </div>

                <button
                    @click="isiKnowledgeBase()"
                    class="bg-emerald-600 text-white px-4 py-2 rounded text-sm hover:bg-emerald-700">

                    Simpan ke Knowledge Base

                </button>

            </div>

        </div>

    </template>

    {{-- ================= MODAL KNOWLEDGE BASE ================= --}}
    <div x-show="openKnowledgeBase" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white w-full max-w-5xl rounded-xl shadow-lg overflow-hidden">

            <div class="flex justify-between items-center px-5 py-3 border-b">
                <div>
                    <h3 class="text-base font-semibold">Knowledge Base</h3>
                    <p class="text-xs text-gray-500">Pencarian solusi</p>
                </div>
                <button @click="openKnowledgeBase = false" class="text-lg">✕</button>
            </div>

            <div class="p-4 grid grid-cols-12 gap-3 h-[75vh]">

                <!-- LEFT -->
                <div class="col-span-4 border rounded-lg p-3 space-y-3 overflow-y-auto">

                    <select x-model="selectedKategori"
                        @change="onKategoriChange"
                        class="w-full border rounded px-2 py-2 text-sm">
                        <option value="">Pilih kategori</option>
                        <template x-for="kat in kategoriList" :key="kat">
                            <option :value="kat" x-text="kat"></option>
                        </template>
                    </select>

                    <input type="text"
                        x-model="searchKB"
                        @input.debounce.400ms="searchKBFromServer"
                        placeholder="Cari masalah..."
                        class="w-full border px-2 py-2 rounded text-sm"
                        :disabled="!selectedKategori">

                    <template x-if="!selectedKategori">
                        <p class="text-xs text-gray-400 text-center">Pilih kategori dulu</p>
                    </template>

                    <template x-if="loadingKB">
                        <p class="text-xs text-gray-400 text-center italic">Memuat...</p>
                    </template>

                    <div class="space-y-2">
                        <template x-for="item in filteredKnowledgeBase" :key="item.id">
                            <button @click="selectKB(item)"
                                class="w-full text-left p-2 rounded-lg border text-sm transition"
                                :class="selectedKB?.id === item.id
                                    ? 'bg-green-50 border-green-400'
                                    : 'bg-white hover:bg-green-50'">
                                <p class="font-medium" x-text="item.judul"></p>
                                <p class="text-[11px] text-gray-400" x-text="item.kategori"></p>
                            </button>
                        </template>
                        <template x-if="selectedKategori && filteredKnowledgeBase.length === 0 && !loadingKB">
                            <p class="text-xs text-gray-400 text-center italic py-4">Tidak ada data KB</p>
                        </template>
                    </div>
                </div>

                <!-- MIDDLE -->
                <div class="col-span-3 border rounded-lg p-3 overflow-y-auto">
                    <template x-if="selectedKB">
                        <div class="space-y-2">
                            <input type="text" x-model="searchDiagnosis"
                                placeholder="Cari penyebab..."
                                class="w-full border px-2 py-2 rounded text-sm">
                            <template x-for="diag in filteredDiagnosis" :key="diag.id">
                                <button @click="selectDiagnosis(diag)"
                                    class="w-full text-left p-2 rounded border text-sm transition"
                                    :class="selectedDiagnosis?.id === diag.id
                                        ? 'bg-green-100 border-green-500'
                                        : 'hover:bg-green-50'">
                                    <p x-html="highlightText(diag.penyebab)"></p>
                                </button>
                            </template>
                            <template x-if="filteredDiagnosis.length === 0">
                                <p class="text-xs text-gray-400 text-center italic py-4">Tidak ada penyebab</p>
                            </template>
                        </div>
                    </template>
                    <template x-if="!selectedKB">
                        <p class="text-xs text-gray-400 text-center mt-10">Pilih knowledge dulu</p>
                    </template>
                </div>

                <!-- RIGHT -->
                <div class="col-span-5 bg-gray-50 rounded-lg p-4 overflow-y-auto">
                    <template x-if="selectedDiagnosis">
                        <div class="bg-white p-3 rounded-lg border space-y-3 text-sm">
                            <h3 class="font-semibold">Detail Solusi</h3>
                            <div>
                                <p class="text-xs text-gray-500">Penyebab</p>
                                <p x-text="selectedDiagnosis.penyebab"></p>
                            </div>
                            <div x-show="selectedDiagnosis.deskripsi">
                                <p class="text-xs text-gray-500">Deskripsi</p>
                                <p class="text-gray-600" x-text="selectedDiagnosis.deskripsi"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Langkah</p>
                                <p class="whitespace-pre-line" x-text="selectedDiagnosis.langkah_penyelesaian"></p>
                            </div>
                            <p class="text-xs text-gray-400 mt-3">
                                Gunakan sebagai referensi dalam menentukan penanganan
                            </p>
                        </div>
                    </template>
                    <template x-if="!selectedDiagnosis">
                        <p class="text-gray-400 text-center mt-10 text-sm">
                            Pilih penyebab untuk melihat detail
                        </p>
                    </template>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= MODAL SIMPAN KB ================= --}}
    <div x-show="openSimpanKB" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg flex flex-col max-h-[90vh]">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="font-semibold text-lg">Simpan Knowledge</h3>
                <button @click="openSimpanKB = false" class="text-xl">&times;</button>
            </div>

            {{-- CONTENT --}}
            <div class="p-6 space-y-6 overflow-y-auto">

                {{-- INFORMASI UMUM --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-gray-600">Informasi Umum</h4>

                    <input x-model="kbForm.judul"
                         @input.debounce.300ms="checkDuplicateKB()"
                        placeholder="Judul masalah"
                        class="w-full border px-3 py-2 rounded">
                        <template x-if="kbDuplicates.length">

                            <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-3 space-y-2">

                                <p class="text-xs font-medium text-yellow-700">
                                    Knowledge serupa ditemukan
                                </p>

                                <template x-for="item in kbDuplicates" :key="item.id">

                                    <div class="flex items-center justify-between bg-white border rounded px-3 py-2">

                                        <div>
                                            <p class="text-sm font-medium" x-text="item.judul"></p>

                                            <p class="text-xs text-gray-500"
                                                x-text="item.kategori">
                                            </p>
                                        </div>

                                        <button
                                            type="button"
                                            @click="useExistingKB(item)"
                                            class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">

                                            Gunakan

                                        </button>

                                    </div>

                                </template>

                            </div>

                            </template>

                    <input x-model="kbForm.variasi"
                        placeholder="Variasi kata (opsional)"
                        class="w-full border px-3 py-2 rounded">

                        <div class="relative">

                            <!-- INPUT -->
                            <input
                                type="text"
                                x-model="kategoriFormSearch"
                                @focus="openKategoriForm = true"
                                placeholder="Cari / tambah kategori..."
                                class="w-full border px-3 py-2 rounded">

                            <!-- DROPDOWN -->
                            <div x-show="openKategoriForm"
                                @mousedown.away="openKategoriForm = false"
                                class="absolute z-50 bg-white border rounded-lg shadow w-full mt-1 max-h-52 overflow-y-auto"
                                @click.stop>

                                <!-- LIST -->
                                <template x-for="kat in filteredKategoriForm" :key="kat">

                                    <button
                                        type="button"
                                        @click="
                                            kbForm.kategori = kat;
                                            kategoriFormSearch = kat;
                                            openKategoriForm = false;
                                        "
                                        class="w-full text-left px-3 py-2 hover:bg-green-50 text-sm">

                                        <span x-text="kat"></span>

                                    </button>

                                </template>

                                <!-- TAMBAH BARU -->
                                <template x-if="
                                    kategoriFormSearch && !kategoriList.some(k => k.toLowerCase() === kategoriFormSearch.toLowerCase())">

                                    <button
                                        type="button"
                                        @click="
                                                kategoriList.push(kategoriFormSearch);

                                                kbForm.kategori = kategoriFormSearch;

                                                kategoriFormSearch = kategoriFormSearch;

                                                openKategoriForm = false;
                                            "
                                        class="w-full text-left px-3 py-2 bg-green-50 text-green-700 border-t text-sm">

                                        + Tambah kategori "<span x-text="kategoriFormSearch"></span>"

                                    </button>

                                </template>

                            </div>

                            <!-- SELECTED -->
                            <template x-if="kbForm.kategori">

                                <div class="flex items-center justify-between text-xs mt-1">

                                    <p class="text-green-600">
                                        Kategori dipilih:
                                        <span class="font-medium" x-text="kbForm.kategori"></span>
                                    </p>

                                    <button
                                        type="button"
                                        @click="
                                            kbForm.kategori = '';
                                            kategoriFormSearch = '';
                                        "
                                        class="text-red-500 hover:underline">

                                        Reset

                                    </button>

                                </div>

                            </template>

                        </div>
                </div>

                {{-- DETAIL --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-gray-600">Detail Penanganan</h4>

                    <textarea x-model="kbForm.penyebab"
                        placeholder="Penyebab"
                        @input.debounce.300ms="checkDuplicateDiagnosis()"
                        class="w-full border px-3 py-2 rounded"></textarea>
                        <template x-if="duplicateDiagnosis.length">

                            <div class="border border-orange-200 bg-orange-50 rounded-lg p-3 space-y-3">

                                <!-- HEADER -->
                                <div>

                                    <p class="text-xs font-semibold text-orange-700">
                                        Penyebab serupa ditemukan
                                    </p>

                                    <p class="text-xs text-orange-600 mt-1">
                                        Knowledge dengan penyebab yang mirip sudah tersedia.
                                        Sebaiknya gunakan knowledge yang sudah ada agar data tidak duplikat.
                                    </p>

                                </div>

                                <!-- LIST -->
                                <template x-for="item in duplicateDiagnosis" :key="item.kb_id">

                                    <div class="bg-white border rounded-lg p-3">

                                        <p class="font-medium text-sm"
                                            x-text="item.judul">
                                        </p>

                                        <p class="text-xs text-gray-500"
                                            x-text="item.kategori">
                                        </p>

                                        <p class="text-sm mt-2"
                                            x-text="item.diagnosis.penyebab">
                                        </p>

                                    </div>

                                </template>

                            </div>

                        </template>
                        
                    <textarea x-model="kbForm.deskripsi"
                        placeholder="Deskripsi"
                        class="w-full border px-3 py-2 rounded"></textarea>

                    <textarea x-model="kbForm.langkah"
                        placeholder="Penanganan"
                        class="w-full border px-3 py-2 rounded"></textarea>
                </div>


            </div>

            {{-- FOOTER --}}
            <div class="p-4 border-t flex justify-end gap-2 bg-gray-50">
                <button @click="openSimpanKB = false"
                    class="bg-gray-200 px-4 py-2 rounded">Batal</button>

                <button @click="simpanKeKnowledgeBase"
                    class="bg-green-600 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </div>

        </div>
    </div>

</div>

<script>
window.knowledgeBase = @json($knowledgeBase);

function detailWOApp() {
    return {
        wo: @json($wo),

        penanganan: { judul: '', deskripsi: '', lampiran: [] },

        openKnowledgeBase: false,
        openPreview: false,
        previewFile: null,
        newStatus: '',
        loadingKB: false,
        openSimpanKB: false,
        openKategoriForm: false,
        kategoriFormSearch: '',

        knowledgeBase: [],
        kategoriList: [],
        selectedKategori: '',
        searchKB: '',
        selectedKB: null,
        searchDiagnosis: '',
        selectedDiagnosis: null,
        duplicateDiagnosis: [],

        kbForm: {
            judul: '',
            kategori: '',
            penyebab: '',
            deskripsi: '',
            langkah: ''
        },

        /* ===== INIT ===== */
        init() {
            this.wo.status = this.normalizeStatus(this.wo.status);
            this.newStatus = this.formatStatus(this.wo.status);
            this.knowledgeBase = window.knowledgeBase || [];
            this.kategoriList  = [...new Set(this.knowledgeBase.map(k => k.kategori).filter(Boolean))];
        },

        /* ===== COMPUTED ===== */

        get filteredKnowledgeBase() {
            if (!this.selectedKategori) return this.knowledgeBase;
            return this.knowledgeBase.filter(item => item.kategori === this.selectedKategori);
        },

        get filteredDiagnosis() {
            if (!this.selectedKB || !this.selectedKB.diagnosis) return [];
            if (!this.searchDiagnosis) return this.selectedKB.diagnosis;
            const keyword = this.searchDiagnosis.toLowerCase();
            return this.selectedKB.diagnosis.filter(d =>
                d.penyebab && d.penyebab.toLowerCase().includes(keyword)
            );
        },

        /* ===== KB ACTIONS ===== */
        isiKnowledgeBase() {

            this.kbForm = {
                judul: this.penanganan.judul || '',
                kategori: '',
                penyebab: this.penanganan.judul || '',
                deskripsi: this.penanganan.deskripsi || '',
                langkah: this.penanganan.deskripsi || '',
                variasi: ''
            };

            this.kategoriFormSearch = '';
            this.openSimpanKB = true;
        },

        onKategoriChange() {
            this.searchKB          = '';
            this.selectedKB        = null;
            this.selectedDiagnosis = null;
            this.knowledgeBase     = window.knowledgeBase || [];
        },

         
        async searchKBFromServer() {
            if (!this.searchKB.trim()) {
                // Kosongkan search → kembalikan ke data asli (filter kategori tetap jalan)
                this.knowledgeBase = window.knowledgeBase || [];
                return;
            }
            this.loadingKB = true;
            try {
                const res  = await fetch(
                    `/knowledge-base/search?q=${encodeURIComponent(this.searchKB)}&kategori=${encodeURIComponent(this.selectedKategori)}`
                );
                const data = await res.json();
                // Ganti this.knowledgeBase → filteredKnowledgeBase tinggal filter kategori
                this.knowledgeBase = data;
            } catch (e) {
                console.error('Search KB error:', e);
            } finally {
                this.loadingKB = false;
            }
        },

        selectKB(item) {
            this.selectedKB        = item;
            this.selectedDiagnosis = null;
            this.searchDiagnosis   = '';
        },

        selectDiagnosis(diag) { this.selectedDiagnosis = diag; },

        highlightText(text) {
            if (!this.searchDiagnosis || !text) return text;
            const keyword = this.searchDiagnosis.toLowerCase();
            return text.replace(new RegExp(keyword, 'gi'),
                match => `<span class="bg-yellow-200">${match}</span>`
            );
        },

        /* ===== UPLOAD ===== */
        handleUploadPenanganan(event) {
            for (let i = 0; i < event.target.files.length; i++) {
                this.penanganan.lampiran.push(event.target.files[i]);
            }
        },
        hapusLampiranPenanganan(index) { this.penanganan.lampiran.splice(index, 1); },

        /* ===== SIMPAN PENANGANAN ===== */
        async simpanPenanganan() {
            if (!this.penanganan.judul) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Judul penanganan tidak boleh kosong' }); return;
            }
            if (!this.penanganan.deskripsi) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Deskripsi penanganan tidak boleh kosong' }); return;
            }
            const confirm = await Swal.fire({
                title: 'Simpan Penanganan?', text: 'Data akan disimpan',
                icon: 'question', showCancelButton: true,
                confirmButtonColor: '#2563eb', cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, simpan!', cancelButtonText: 'Batal'
            });
            if (!confirm.isConfirmed) return;

            try {
                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const formData = new FormData();
                formData.append('judul', this.penanganan.judul);
                formData.append('deskripsi', this.penanganan.deskripsi);
                this.penanganan.lampiran.forEach(file => formData.append('lampiran[]', file));
                const res = await fetch(`/work-order/${this.wo.id}/penanganan`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: formData
                });
                const text = await res.text();
                let data;
                try { data = JSON.parse(text); } catch { throw new Error('Server error (bukan JSON)'); }
                if (!res.ok) throw data;
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message || 'Penanganan berhasil disimpan', timer: 1500, showConfirmButton: false });
                setTimeout(() => location.reload(), 1500);
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: err.message || 'Terjadi kesalahan' });
            }
        },

        /* ===== STATUS ===== */
        async ubahStatus() {
            if (this.newStatus === this.formatStatus(this.wo.status)) return;
            const confirm = await Swal.fire({
                title: 'Ubah Status?', text: `Status akan diubah menjadi ${this.newStatus}`,
                icon: 'question', showCancelButton: true,
                confirmButtonText: 'Ya, ubah', cancelButtonText: 'Batal'
            });
            if (!confirm.isConfirmed) { this.newStatus = this.formatStatus(this.wo.status); return; }
            try {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const res = await fetch(`/work-order/${this.wo.id}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ status: this.newStatus })
                });
                const data = await res.json();
                if (!res.ok) throw data;
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 1200, showConfirmButton: false });
                this.wo.status = this.normalizeStatus(this.newStatus);
            } catch (err) {
                this.newStatus = this.formatStatus(this.wo.status);
                Swal.fire({ icon: 'error', title: 'Gagal!', text: err.message || 'Terjadi kesalahan' });
            }
        },

        /* ===== PREVIEW FILE ===== */
        openPreviewFile(file) { if (!file) return; this.previewFile = file; this.openPreview = true; },
        isImage(file) { return file && /\.(jpg|jpeg|png|gif)$/i.test(file); },
        isPDF(file)   { return file && /\.pdf$/i.test(file); },

        /* ===== HELPERS ===== */
        normalizeStatus(status) { return (status || '').toLowerCase().trim().replace(/\s+/g, '_'); },
        formatStatus(status) {
            const s = this.normalizeStatus(status);
            if (s === 'open')        return 'Open';
            if (s === 'on_progress') return 'On Progress';
            if (s === 'waiting')     return 'Waiting';
            if (s === 'close')       return 'Close';
            return status;
        },
        statusClass(status) {
            const s = this.normalizeStatus(status);
            return {
                'bg-blue-100 text-blue-700':    s === 'open',
                'bg-yellow-100 text-yellow-700': s === 'on_progress',
                'bg-orange-100 text-orange-700': s === 'waiting',
                'bg-green-100 text-green-700':  s === 'close',
                'bg-gray-100 text-gray-700':    !['open','on_progress','waiting','close'].includes(s)
            };
        },
        statusClassRiwayat(status) {
            const s = this.normalizeStatus(status);
            return {
                'border-l-4 border-blue-500 bg-blue-50/30':    s === 'open',
                'border-l-4 border-yellow-500 bg-yellow-50/30': s === 'on_progress',
                'border-l-4 border-orange-500 bg-orange-50/30': s === 'waiting',
                'border-l-4 border-green-500 bg-green-50/30':  s === 'close'
            };
        },

        selectKategori(item) {
            this.kbForm.kategori = item;
            this.openKategori = false;
        },

        tambahKategoriBaru() {
            if (this.kategoriSearch && !this.kategoriList.includes(this.kategoriSearch)) {
                this.kategoriList.push(this.kategoriSearch);
                this.kbForm.kategori = this.kategoriSearch;
                this.kategoriSearch = '';
                this.openKategori = false;
            }
        },

        checkDuplicateKB() {
            if (!this.kbForm.judul) { this.kbDuplicates = []; return; }
            let j = this.kbForm.judul.toLowerCase();

                this.kbDuplicates = this.knowledgeBase.filter(k =>
                    k.judul.toLowerCase().includes(j)
                    || (this.kbForm.variasi && this.kbForm.variasi.includes(k.judul))
                );
        },
        checkDuplicateDiagnosis() {

            if (!this.kbForm.penyebab) {
                this.duplicateDiagnosis = [];
                return;
            }

            const keyword = this.kbForm.penyebab.toLowerCase();

            let results = [];

            this.knowledgeBase.forEach(kb => {

                (kb.diagnosis || []).forEach(diag => {

                    if (
                        diag.penyebab &&
                        diag.penyebab.toLowerCase().includes(keyword)
                    ) {

                        results.push({
                            kb_id: kb.id,
                            judul: kb.judul,
                            kategori: kb.kategori,
                            diagnosis: diag
                        });
                    }
                });
            });

            this.duplicateDiagnosis = results;
        },

        useExistingKB(item) {

            this.kbForm.judul = item.judul;

            this.kbForm.kategori = item.kategori || '';

            this.kategoriFormSearch = item.kategori || '';

            this.kbDuplicates = [];
        },

        async simpanKeKnowledgeBase() {

            // 🔥 VALIDASI FRONTEND
            if (!this.kbForm.judul || !this.kbForm.kategori || !this.kbForm.penyebab || !this.kbForm.langkah) {
                Swal.fire('Oops!', 'Lengkapi field wajib', 'warning');
                return;
            }

            try {

                let res = await fetch('/knowledge-base', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json' // 🔥 penting
                    },
                    body: JSON.stringify({
                        judul: this.kbForm.judul,
                        kategori: this.kbForm.kategori,
                        penyebab: this.kbForm.penyebab,
                        deskripsi: this.kbForm.deskripsi,
                        langkah: this.kbForm.langkah,
                        variasi: this.kbForm.variasi,
                      
                    })
                });

                // 🔥 AMBIL TEXT DULU (BIAR AMAN)
                let text = await res.text();

                let data;

                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('❌ BUKAN JSON:', text);

                    Swal.fire(
                        'Server Error',
                        'Response bukan JSON (cek backend / auth / DB)',
                        'error'
                    );
                    return;
                }

                // 🔥 HANDLE ERROR RESPONSE
                if (!res.ok) {

                    let message = data.message || 'Gagal menyimpan';

                    if (data.errors) {
                        message = Object.values(data.errors).flat().join('\n');
                    }

                    Swal.fire('Gagal!', message, 'error');
                    return;
                }

                // 🔥 SUCCESS
                Swal.fire('Berhasil!', data.message, 'success');

                // update data lokal
                this.knowledgeBase.push(data.data);

                // update kategori
                this.kategoriList = [...new Set(this.knowledgeBase.map(k => k.kategori))];

                // reset form
                this.kbForm = {
                    judul: '',
                    kategori: '',
                    penyebab: '',
                    deskripsi: '',
                    langkah: '',
                    variasi: '',
                   
                };

                this.openSimpanKB = false;

            } catch (err) {

                console.error('❌ FETCH ERROR:', err);

                Swal.fire(
                    'Error!',
                    'Tidak bisa terhubung ke server',
                    'error'
                );
            }
            },
        tambahKategori() {

            if (!this.newKategori) return;

            // Tambah ke list
            this.kategoriList.push(this.newKategori);

            // Auto pilih
            this.kbForm.kategori = this.newKategori;

            // Reset
            this.newKategori = '';
            this.showAddKategori = false;
            },
        

            get filteredKategoriForm() {
                if (!this.kategoriFormSearch) {
                    return this.kategoriList;
                }

                return this.kategoriList.filter(k =>
                    k.toLowerCase().includes(
                        this.kategoriFormSearch.toLowerCase()
                    )
                );
            },


    }
}
</script>
@endsection