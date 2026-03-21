@extends('layouts.app')

@section('title', 'Knowledge Base')

@section('content')
<div x-data="kbPage()" class="p-6 max-w-6xl mx-auto">

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
            @click="
                resetKBForm();
                openCreateKB = true
            "
            class="bg-green-600 text-white px-4 py-2 rounded"
        >
            + Tambah Knowledge Base
        </button>
    </div>

    {{-- ================= SEARCH ================= --}}
    <div class="mb-4">
        <input
            type="text"
            x-model="kbSearch"
            placeholder="Cari solusi (contoh: AC, lampu, kran)..."
            class="w-full border rounded-lg px-3 py-2 text-sm"
        >
    </div>

    {{-- ================= LIST & DETAIL ================= --}}
    @include('components.knowledgeBase')

    {{-- ================= MODAL CREATE KB ================= --}}
    <div
        x-show="openCreateKB"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg overflow-hidden">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="font-semibold">Tambah Knowledge Base</h3>
                <button @click="openCreateKB = false">✕</button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-4 space-y-4">

                {{-- JUDUL --}}
                <input x-model="kbForm.judul"
                    placeholder="Judul"
                    class="w-full border rounded px-3 py-2 text-sm">

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


                    {{-- DEPARTEMEN (DROPDOWN FIX) --}}
                    <div class="relative">
                        <p class="text-xs text-gray-500 mb-1">Departemen</p>

                        <div class="border rounded px-3 py-2 cursor-pointer bg-white"
                            @click="openDept = !openDept">
                            <span x-text="kbForm.dept || 'Pilih departemen...'"></span>
                        </div>

                        <div x-show="openDept"
                            x-transition
                            @click.outside="openDept = false"
                            class="absolute z-50 w-full bg-white border rounded shadow mt-1">

                            <template x-for="item in deptList" :key="item">
                                <div
                                    @click="selectDept(item)"
                                    class="px-3 py-2 hover:bg-green-100 cursor-pointer text-sm"
                                    x-text="item">
                                </div>
                            </template>

                        </div>
                    </div>

                {{-- DESKRIPSI --}}
                <textarea x-model="kbForm.deskripsi"
                    placeholder="Deskripsi"
                    class="w-full border rounded px-3 py-2 text-sm"></textarea>

                {{-- LANGKAH --}}
                <textarea x-model="kbForm.langkah"
                    placeholder="Langkah Penyelesaian"
                    class="w-full border rounded px-3 py-2 text-sm"></textarea>

                {{-- LAMPIRAN --}}
                <input type="file" multiple @change="handleUploadKB">

                <div class="flex flex-wrap gap-2">
                    <template x-for="(file,index) in kbForm.lampiran">
                        <div class="border px-2 py-1 text-xs rounded bg-gray-50 flex items-center gap-1">
                            <span x-text="file.name"></span>
                            <button @click="hapusLampiranKB(index)">✕</button>
                        </div>
                    </template>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t flex justify-end gap-2">
                <button @click="openCreateKB=false"
                    class="bg-gray-100 px-3 py-1 rounded">
                    Batal
                </button>

                <button @click="simpanKB"
                    class="bg-green-600 text-white px-4 py-2 rounded">
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
        kbSearch: '',
        selectedKB: null,
        openCreateKB: false,

        openKategori: false,
        openDept: false,
        deptList: ['Engineering','Housekeeping','IT Support','Security'],
        kategoriSearch: '',
        kategoriList: ['AC','Listrik','Plumbing','Internet','Furniture'],

        // knowledgeBase: [],

        kbForm: {
            judul: '',
            kategori: '',
            deskripsi: '',
            langkah: '',
            dept: '',
            created_by: '',
            lampiran: [],
            relatedKeluhan: []
        },

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
                 
                },
                {
                    id: 4,
                    tiket: 'TCK-014',
                    unit: 'B-210',
                    
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

        get filteredKnowledgeBase() {
            return this.knowledgeBase.filter(kb =>
                kb.judul.toLowerCase().includes(this.kbSearch.toLowerCase())
            );
        },

        get filteredKategori() {
            return this.kategoriList.filter(k =>
                k.toLowerCase().includes(this.kategoriSearch.toLowerCase())
            );
        },

        selectKB(item) {
            this.selectedKB = item;
        },

        selectKategori(item) {
            this.kbForm.kategori = item;
            this.openKategori = false;
            this.kategoriSearch = '';
        },

        tambahKategoriBaru() {
            const newKategori = this.kategoriSearch.trim();

            if (!this.kategoriList.includes(newKategori)) {
                this.kategoriList.push(newKategori);
            }

            this.kbForm.kategori = newKategori;

            this.kategoriSearch = '';
            this.openKategori = false;
        },

        handleUploadKB(e) {
            this.kbForm.lampiran.push(...Array.from(e.target.files));
        },

        hapusLampiranKB(index) {
            this.kbForm.lampiran.splice(index, 1);
        },

        resetKBForm() {
            this.kbForm = {
                judul: '',
                kategori: '',
                deskripsi: '',
                langkah: '',
                dept: '',
                created_by: '',
                lampiran: [],
                relatedKeluhan: []
            };
        },
        selectDept(item) {
            this.kbForm.dept = item;
            this.openDept = false;
        },
        simpanKB() {
            if (
                !this.kbForm.judul ||
                !this.kbForm.kategori ||
                !this.kbForm.deskripsi ||
                !this.kbForm.langkah ||
                !this.kbForm.dept ||
                !this.kbForm.created_by
            ) {
                alert('Lengkapi semua data');
                return;
            }

            const now = new Date();
            const formattedDate = now.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            this.knowledgeBase.push({
                id: this.knowledgeBase.length + 1,
                judul: this.kbForm.judul,
                kategori: this.kbForm.kategori,
                deskripsi: this.kbForm.deskripsi,
                langkah: this.kbForm.langkah,
                dept: this.kbForm.dept,
                created_by: this.kbForm.created_by,
                created_at: formattedDate,
                lampiran: this.kbForm.lampiran.map(f => f.name),
                relatedKeluhan: []
            });

            alert('Berhasil ditambahkan');

            this.resetKBForm();
            this.openCreateKB = false;
        }
    }
}
</script>
@endsection