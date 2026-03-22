@extends('layouts.app')

@section('title', 'Knowledge Base')

@section('content')
<div x-data="kbPage()" class="px-6 pb-6 w-full">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Knowledge Base Solusi Keluhan
            </h1>
            <p class="text-sm text-gray-500">
                Referensi solusi untuk membantu penanganan keluhan
            </p>
        </div>

        <button
            @click="openCreateKBModal()"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
            + Tambah Knowledge Base
        </button>
    </div>

    {{-- ================= SEARCH & LIST & DETAIL ================= --}}
    @include('components.knowledgeBase')

    {{-- ================= MODAL CREATE/EDIT KB ================= --}}
    <div
        x-show="openKBModal"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg overflow-hidden max-h-[90vh] flex flex-col">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="font-semibold text-lg" x-text="editingId ? 'Edit Knowledge Base' : 'Tambah Knowledge Base'"></h3>
                <button @click="openKBModal = false" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-4 space-y-4 overflow-y-auto flex-1">

                {{-- JUDUL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul*</label>
                    <input x-model="kbForm.judul"
                        placeholder="Contoh: AC Tidak Dingin"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                {{-- KATEGORI --}}
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <div class="border rounded-lg px-3 py-2 cursor-pointer bg-white hover:border-gray-400 transition"
                        @click="openKategori = !openKategori">
                        <span x-text="kbForm.kategori || 'Pilih kategori...'" :class="{'text-gray-400': !kbForm.kategori}"></span>
                    </div>

                    <div x-show="openKategori"
                        x-transition
                        @click.outside="openKategori = false"
                        class="absolute z-50 w-full bg-white border rounded-lg shadow-lg mt-1">

                        <input
                            x-model="kategoriSearch"
                            placeholder="Cari atau tambah kategori..."
                            class="w-full border-b px-3 py-2 text-sm focus:outline-none">

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
                                    class="px-3 py-2 text-green-600 cursor-pointer border-t text-sm hover:bg-green-50">
                                    + Tambah "<span x-text="kategoriSearch"></span>"
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- DEPARTEMEN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen Terkait *</label>
                    <select x-model="kbForm.dept"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Pilih Departemen</option>
                        <option>Engineering</option>
                        <option>Operational</option>
                        <option>Finance</option>
                    </select>
                </div>
                
                {{-- LAMPIRAN (untuk KB level) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran (opsional)</label>
                    <input type="file" multiple @change="handleUploadKB" class="w-full text-sm">
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(file, index) in kbForm.lampiran">
                            <div class="border px-2 py-1 text-xs rounded bg-gray-50 flex items-center gap-1">
                                <span x-text="file.name"></span>
                                <button @click="hapusLampiranKB(index)" class="text-red-500">✕</button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t flex justify-end gap-2 bg-gray-50">
                <button @click="openKBModal = false"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button @click="saveKB"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL CREATE/EDIT PENYEBAB ================= --}}
    <div
        x-show="openCauseModal"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg overflow-hidden max-h-[90vh] flex flex-col">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="font-semibold text-lg" x-text="editingCause ? 'Edit Penyebab' : 'Tambah Penyebab'"></h3>
                <button @click="openCauseModal = false" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-4 space-y-4 overflow-y-auto flex-1">

                {{-- PENYEBAB --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penyebab *</label>
                    <textarea x-model="causeForm.penyebab"
                        placeholder="Contoh: Freon habis atau tekanan tidak stabil"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        rows="2"></textarea>
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea x-model="causeForm.deskripsi"
                        placeholder="Deskripsi singkat (opsional)"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        rows="2"></textarea>
                </div>

                {{-- LANGKAH PENYELESAIAN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Langkah Penyelesaian *</label>
                    <textarea x-model="causeForm.langkah"
                        placeholder="1. Cek tekanan freon&#10;2. Isi ulang freon&#10;3. Pastikan tidak ada kebocoran"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        rows="4"></textarea>
                </div>

                {{-- LAMPIRAN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran (opsional)</label>
                    <input type="file" multiple @change="handleUploadCause" class="w-full text-sm">
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(file, index) in causeForm.lampiran">
                            <div class="border px-2 py-1 text-xs rounded bg-gray-50 flex items-center gap-1">
                                <span x-text="file.name"></span>
                                <button @click="hapusLampiranCause(index)" class="text-red-500">✕</button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t flex justify-end gap-2 bg-gray-50">
                <button @click="openCauseModal = false"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button @click="saveCause"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
function kbPage() {
    return {
        // Modal KB
        openKBModal: false,
        editingId: null,
        openKategori: false,

        // Modal Penyebab
        openCauseModal: false,
        editingCause: null, // object cause yang sedang diedit

        // Search
        kbSearch: '',
        searchDiagnosis: '',
        kategoriSearch: '',

        // Form KB
        kbForm: {
            judul: '',
            kategori: '',
            dept: '',
            lampiran: []  // lampiran untuk KB level (bisa kosong)
        },

        // Form Penyebab
        causeForm: {
            penyebab: '',
            deskripsi: '',
            langkah: '',
            lampiran: []
        },

        // Lists
        kategoriList: ['AC', 'Listrik', 'Plumbing', 'Internet', 'Furniture'],
        knowledgeBase: [
            {
                id: 1,
                judul: "AC Tidak Dingin",
                kategori: "AC",
                dept: "Engineering",
                diagnosis_list: [
                    {
                        id: 1,
                        Penyebab: "Freon habis atau tekanan tidak stabil",
                        deskripsi: "Tekanan freon rendah menyebabkan AC tidak mendingin optimal.",
                        langkah: "1. Cek tekanan freon menggunakan manifold gauge\n2. Isi ulang freon jika tekanan rendah\n3. Periksa kebocoran pada sambungan pipa\n4. Pastikan tidak ada kebocoran pada evaporator",
                        lampiran: ["freon-check.pdf"]
                    },
                    {
                        id: 2,
                        Penyebab: "Saluran drainase tersumbat",
                        deskripsi: "Air tidak bisa mengalir keluar sehingga AC bocor atau tidak dingin.",
                        langkah: "1. Bersihkan selang pembuangan dengan air bertekanan\n2. Pastikan selang tidak tertekuk\n3. Cek posisi kemiringan AC indoor\n4. Bersihkan bak penampung air",
                        lampiran: []
                    }
                ]
            },
            {
                id: 2,
                judul: "AC Bocor Air",
                kategori: "AC",
                dept: "Engineering",
                diagnosis_list: [
                    {
                        id: 3,
                        Penyebab: "Filter AC kotor",
                        deskripsi: "Filter kotor menghambat aliran udara dan menimbulkan kebocoran air.",
                        langkah: "1. Lepas filter AC\n2. Bersihkan dengan air mengalir\n3. Keringkan dan pasang kembali\n4. Lakukan pembersihan rutin setiap bulan",
                        lampiran: ["filter-cleaning-guide.pdf"]
                    }
                ]
            }
        ],

        selectedKB: null,
        selectedDiagnosis: null,

        // Computed
        get filteredKnowledgeBase() {
            return this.knowledgeBase.filter(kb =>
                kb.judul.toLowerCase().includes(this.kbSearch.toLowerCase()) ||
                kb.kategori.toLowerCase().includes(this.kbSearch.toLowerCase())
            );
        },

        get filteredDiagnosis() {
            if (!this.selectedKB) return [];
            return this.selectedKB.diagnosis_list.filter(d =>
                d.Penyebab.toLowerCase().includes(this.searchDiagnosis.toLowerCase())
            );
        },

        get filteredKategori() {
            return this.kategoriList.filter(k =>
                k.toLowerCase().includes(this.kategoriSearch.toLowerCase())
            );
        },

        // ========== METHODS KB ==========
        selectKB(item) {
            this.selectedKB = item;
            this.selectedDiagnosis = null;
            this.searchDiagnosis = '';
        },

        selectDiagnosis(diag) {
            this.selectedDiagnosis = diag;
        },

        selectKategori(item) {
            this.kbForm.kategori = item;
            this.openKategori = false;
            this.kategoriSearch = '';
        },

        tambahKategoriBaru() {
            const newKategori = this.kategoriSearch.trim();
            if (!this.kategoriList.includes(newKategori)) this.kategoriList.push(newKategori);
            this.kbForm.kategori = newKategori;
            this.kategoriSearch = '';
            this.openKategori = false;
        },

        handleUploadKB(e) {
            this.kbForm.lampiran.push(...Array.from(e.target.files));
            e.target.value = '';
        },

        hapusLampiranKB(index) {
            this.kbForm.lampiran.splice(index, 1);
        },

        resetKBForm() {
            this.kbForm = {
                judul: '',
                kategori: '',
                dept: '',
                lampiran: []
            };
        },

        openCreateKBModal() {
            this.resetKBForm();
            this.editingId = null;
            this.openKBModal = true;
        },

        openEditKBModal(item) {
            this.resetKBForm();
            this.editingId = item.id;
            this.kbForm.judul = item.judul;
            this.kbForm.kategori = item.kategori;
            this.kbForm.dept = item.dept;
            // Lampiran KB (opsional) – tidak diisi dari data karena tidak disimpan di level KB
            this.openKBModal = true;
        },

        deleteKB(id) {
            if (confirm('Hapus Knowledge Base ini? Data tidak dapat dikembalikan.')) {
                const index = this.knowledgeBase.findIndex(kb => kb.id === id);
                if (index !== -1) {
                    this.knowledgeBase.splice(index, 1);
                    if (this.selectedKB && this.selectedKB.id === id) {
                        this.selectedKB = null;
                        this.selectedDiagnosis = null;
                    }
                }
            }
        },

        saveKB() {
            if (!this.kbForm.judul || !this.kbForm.kategori || !this.kbForm.dept) {
                alert('Lengkapi semua data (Judul, Kategori, Departemen)');
                return;
            }

            if (this.editingId) {
                const index = this.knowledgeBase.findIndex(kb => kb.id === this.editingId);
                if (index !== -1) {
                    this.knowledgeBase[index] = {
                        ...this.knowledgeBase[index],
                        judul: this.kbForm.judul,
                        kategori: this.kbForm.kategori,
                        dept: this.kbForm.dept
                    };
                    if (this.selectedKB && this.selectedKB.id === this.editingId) {
                        this.selectedKB = this.knowledgeBase[index];
                    }
                }
                alert('Berhasil diupdate');
            } else {
                const newKB = {
                    id: this.knowledgeBase.length + 1,
                    judul: this.kbForm.judul,
                    kategori: this.kbForm.kategori,
                    dept: this.kbForm.dept,
                    diagnosis_list: [] // baru, tanpa penyebab
                };
                this.knowledgeBase.push(newKB);
                alert('Berhasil ditambahkan');
            }

            this.resetKBForm();
            this.openKBModal = false;
            this.editingId = null;
        },

        // ========== METHODS PENYEBAB ==========
        resetCauseForm() {
            this.causeForm = {
                penyebab: '',
                deskripsi: '',
                langkah: '',
                lampiran: []
            };
        },

        handleUploadCause(e) {
            this.causeForm.lampiran.push(...Array.from(e.target.files));
            e.target.value = '';
        },

        hapusLampiranCause(index) {
            this.causeForm.lampiran.splice(index, 1);
        },

        openAddCauseModal() {
            if (!this.selectedKB) {
                alert('Pilih Knowledge Base terlebih dahulu');
                return;
            }
            this.resetCauseForm();
            this.editingCause = null;
            this.openCauseModal = true;
        },

        openEditCauseModal(cause) {
            this.resetCauseForm();
            this.editingCause = cause;
            this.causeForm.penyebab = cause.Penyebab;
            this.causeForm.deskripsi = cause.deskripsi || '';
            this.causeForm.langkah = cause.langkah;
            // Lampiran: ubah array nama file menjadi array object { name }
            this.causeForm.lampiran = (cause.lampiran || []).map(name => ({ name }));
            this.openCauseModal = true;
        },

        saveCause() {
            if (!this.causeForm.penyebab || !this.causeForm.langkah) {
                alert('Lengkapi data (Penyebab dan Langkah Penyelesaian)');
                return;
            }

            const newCause = {
                id: this.editingCause ? this.editingCause.id : Date.now(),
                Penyebab: this.causeForm.penyebab,
                deskripsi: this.causeForm.deskripsi,
                langkah: this.causeForm.langkah,
                lampiran: this.causeForm.lampiran.map(f => f.name)
            };

            if (this.editingCause) {
                // Update existing cause
                const index = this.selectedKB.diagnosis_list.findIndex(d => d.id === this.editingCause.id);
                if (index !== -1) {
                    this.selectedKB.diagnosis_list[index] = newCause;
                    // Jika penyebab yang diedit sedang dipilih, update selectedDiagnosis
                    if (this.selectedDiagnosis && this.selectedDiagnosis.id === this.editingCause.id) {
                        this.selectedDiagnosis = newCause;
                    }
                }
                alert('Penyebab berhasil diupdate');
            } else {
                // Add new cause
                this.selectedKB.diagnosis_list.push(newCause);
                alert('Penyebab berhasil ditambahkan');
            }

            this.resetCauseForm();
            this.openCauseModal = false;
            this.editingCause = null;
        },

        deleteCause(causeId) {
            if (!this.selectedKB) return;
            if (confirm('Hapus penyebab ini?')) {
                const index = this.selectedKB.diagnosis_list.findIndex(d => d.id === causeId);
                if (index !== -1) {
                    this.selectedKB.diagnosis_list.splice(index, 1);
                    if (this.selectedDiagnosis && this.selectedDiagnosis.id === causeId) {
                        this.selectedDiagnosis = null;
                    }
                }
            }
        }
    }
}
</script>
@endsection