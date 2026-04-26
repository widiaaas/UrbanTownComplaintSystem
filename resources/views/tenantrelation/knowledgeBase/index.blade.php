@extends('layouts.app')

@section('title', 'Knowledge Base')

@section('content')
<div x-data="kbPage()" class="px-6 pb-6 w-full">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Knowledge Base</h1>
            <p class="text-sm text-gray-500">Referensi solusi untuk membantu penanganan keluhan</p>
        </div>
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

         
         
            <div class="px-6 py-4 space-y-4 overflow-y-auto flex-1">

                {{-- JUDUL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label>
                    <input x-model="kbForm.judul"
                        placeholder="Contoh: AC Tidak Dingin"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                </div>

                {{-- KATEGORI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <div class="flex gap-2">
                        <select x-model="kbForm.kategori"
                            class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                            <option value="">Pilih Kategori</option>
                            <template x-for="kat in kategoriList" :key="kat">
                                <option :value="kat" x-text="kat"></option>
                            </template>
                        </select>
                        <button
                            @click="showAddKategori = !showAddKategori"
                            class="px-3 py-2 border rounded-lg text-sm text-green-600 hover:bg-green-50">
                            + Baru
                        </button>
                    </div>
                    <template x-if="showAddKategori">
                        <div class="flex gap-2 mt-2">
                            <input x-model="newKategori"
                                placeholder="Nama kategori baru"
                                class="flex-1 border rounded-lg px-3 py-2 text-sm">
                            <button @click="tambahKategori()"
                                class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                                Tambah
                            </button>
                        </div>
                    </template>
                </div>

                {{-- DEPARTEMEN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departemen *</label>
                    <select x-model="kbForm.dept"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                        <option value="">Pilih Departemen</option>
                        <option>Engineering</option>
                        <option>Operational</option>
                        <option>Finance</option>
                    </select>
                </div>

            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-2 bg-gray-50">
                <button @click="openKBModal = false"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button @click="saveKB()"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
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

            

            <div class="px-6 py-4 space-y-4 overflow-y-auto flex-1">

                {{-- PENYEBAB --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penyebab *</label>
                    <textarea x-model="causeForm.penyebab"
                        placeholder="Contoh: Freon habis atau tekanan tidak stabil"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                        rows="2"></textarea>
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea x-model="causeForm.deskripsi"
                        placeholder="Deskripsi singkat (opsional)"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                        rows="2"></textarea>
                </div>

                {{-- LANGKAH PENYELESAIAN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Langkah Penyelesaian *</label>
                    <textarea x-model="causeForm.langkah_penyelesaian"
                        placeholder="1. Cek tekanan freon&#10;2. Isi ulang freon&#10;3. Pastikan tidak ada kebocoran"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                        rows="4"></textarea>
                </div>

                {{-- LAMPIRAN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran (opsional)</label>
                    <input type="file" multiple @change="handleUploadCause" class="w-full text-sm">
                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(file, index) in causeForm.lampiran" :key="index">
                            <div class="border px-2 py-1 text-xs rounded bg-gray-50 flex items-center gap-1">
                                <span x-text="file.name"></span>
                                <button @click="hapusLampiranCause(index)" class="text-red-500">✕</button>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-2 bg-gray-50">
                <button @click="openCauseModal = false"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button @click="saveCause()"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ================= SCRIPT ================= --}}
<script>
window.knowledgeBase = @json($knowledgeBase ?? []);
window.kategoriList  = @json($kategoriList  ?? []);

function kbPage() {
    return {
        /* ===== MODAL STATE ===== */
        openKBModal:    false,
        openCauseModal: false,
        editingId:      null,
        editingCause:   null,

        /* ===== UI STATE ===== */
        kbSearch:         '',
        searchDiagnosis:  '',
        selectedKategori: '',
        showAddKategori:  false,
        newKategori:      '',

        /* ===== DATA ===== */
        knowledgeBase: [],
        kategoriList:  [],
        selectedKB:        null,
        selectedDiagnosis: null,

        /* ===== FORM KB ===== */
        kbForm: {
            judul:    '',
            kategori: '',
            dept:     '',
        },

        /* ===== FORM PENYEBAB ===== */
        // field names sesuai konsep file 3 (detail keluhan)
        causeForm: {
            penyebab:             '',
            deskripsi:            '',
            langkah_penyelesaian: '',
            lampiran:             []
        },

        /* ===== INIT ===== */
        init() {
            this.knowledgeBase = window.knowledgeBase || [];
            this.kategoriList  = window.kategoriList  || [];

            // fallback: ambil kategori dari data jika belum ada di server
            if (!this.kategoriList.length) {
                this.kategoriList = [...new Set(this.knowledgeBase.map(k => k.kategori).filter(Boolean))];
            }
        },

        /* ===== COMPUTED ===== */
        get filteredKnowledgeBase() {
            let data = this.knowledgeBase;

            if (this.selectedKategori) {
                data = data.filter(item => item.kategori === this.selectedKategori);
            }

            if (this.kbSearch) {
                const keyword = this.kbSearch.toLowerCase();
                data = data.filter(item =>
                    item.judul.toLowerCase().includes(keyword) ||
                    (item.kategori && item.kategori.toLowerCase().includes(keyword))
                );
            }

            return data;
        },

        get filteredDiagnosis() {
            if (!this.selectedKB || !this.selectedKB.diagnosis) return [];
            if (!this.searchDiagnosis) return this.selectedKB.diagnosis;

            const keyword = this.searchDiagnosis.toLowerCase();
            return this.selectedKB.diagnosis.filter(d =>
                d.penyebab && d.penyebab.toLowerCase().includes(keyword)
            );
        },

        /* ===== SELECT ===== */
        selectKB(item) {
            this.selectedKB        = item;
            this.selectedDiagnosis = null;
            this.searchDiagnosis   = '';
        },

        selectDiagnosis(diag) {
            this.selectedDiagnosis = diag;
        },

        /* ===== SEARCH SERVER ===== */
        async searchKBFromServer() {
            if (!this.kbSearch) {
                this.knowledgeBase = window.knowledgeBase;
                return;
            }
            try {
                const res  = await fetch(`/knowledge-base/search?q=${encodeURIComponent(this.kbSearch)}&kategori=${encodeURIComponent(this.selectedKategori)}`);
                const data = await res.json();
                this.knowledgeBase = data;
            } catch (e) {
                console.error('Search KB error:', e);
            }
        },

        /* ===== KATEGORI ===== */
        tambahKategori() {
            const val = this.newKategori.trim();
            if (!val) return;
            if (!this.kategoriList.includes(val)) this.kategoriList.push(val);
            this.kbForm.kategori  = val;
            this.newKategori      = '';
            this.showAddKategori  = false;
        },

        /* ===== KB CRUD ===== */
        resetKBForm() {
            this.kbForm = { judul: '', kategori: '', dept: '' };
        },

        openCreateKBModal() {
            this.resetKBForm();
            this.editingId   = null;
            this.openKBModal = true;
        },

        openEditKBModal(item) {
            this.resetKBForm();
            this.editingId       = item.id;
            this.kbForm.judul    = item.judul;
            this.kbForm.kategori = item.kategori;
            this.kbForm.dept     = item.dept || '';
            this.openKBModal     = true;
        },

        async saveKB() {
            if (!this.kbForm.judul || !this.kbForm.kategori || !this.kbForm.dept) {
                Swal.fire('Oops!', 'Lengkapi Judul, Kategori, dan Departemen', 'warning');
                return;
            }

            const isEdit  = !!this.editingId;
            const url     = isEdit ? `/knowledge-base/${this.editingId}` : '/knowledge-base';
            const method  = isEdit ? 'PUT' : 'POST';

            try {
                const res  = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(this.kbForm)
                });

                const text = await res.text();
                let   data;
                try { data = JSON.parse(text); } catch {
                    Swal.fire('Error', 'Response bukan JSON', 'error'); return;
                }

                if (!res.ok) {
                    const msg = data.errors
                        ? Object.values(data.errors).flat().join('\n')
                        : (data.message || 'Gagal menyimpan');
                    Swal.fire('Gagal!', msg, 'error'); return;
                }

                if (isEdit) {
                    const idx = this.knowledgeBase.findIndex(k => k.id === this.editingId);
                    if (idx !== -1) {
                        this.knowledgeBase[idx] = data.data;
                        if (this.selectedKB?.id === this.editingId) this.selectedKB = data.data;
                    }
                } else {
                    this.knowledgeBase.push(data.data);
                    // tambah kategori baru jika belum ada
                    if (!this.kategoriList.includes(data.data.kategori)) {
                        this.kategoriList.push(data.data.kategori);
                    }
                }

                Swal.fire('Berhasil!', data.message, 'success');
                this.openKBModal = false;
                this.editingId   = null;
                this.resetKBForm();

            } catch (err) {
                Swal.fire('Error!', 'Tidak bisa terhubung ke server', 'error');
            }
        },

        async deleteKB(id) {
            const confirm = await Swal.fire({
                title: 'Hapus Knowledge Base?',
                text:  'Semua penyebab di dalamnya juga akan terhapus.',
                icon:  'warning',
                showCancelButton:  true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText:  'Batal'
            });
            if (!confirm.isConfirmed) return;

            try {
                const res = await fetch(`/knowledge-base/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });

                if (!res.ok) { Swal.fire('Gagal!', 'Gagal menghapus', 'error'); return; }

                const idx = this.knowledgeBase.findIndex(k => k.id === id);
                if (idx !== -1) this.knowledgeBase.splice(idx, 1);

                if (this.selectedKB?.id === id) {
                    this.selectedKB        = null;
                    this.selectedDiagnosis = null;
                }

                Swal.fire('Dihapus!', 'Knowledge Base berhasil dihapus', 'success');

            } catch {
                Swal.fire('Error!', 'Tidak bisa terhubung ke server', 'error');
            }
        },

        /* ===== PENYEBAB CRUD ===== */
        resetCauseForm() {
            this.causeForm = {
                penyebab:             '',
                deskripsi:            '',
                langkah_penyelesaian: '',
                lampiran:             []
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
                Swal.fire('Oops!', 'Pilih Knowledge Base terlebih dahulu', 'warning');
                return;
            }
            this.resetCauseForm();
            this.editingCause  = null;
            this.openCauseModal = true;
        },

        openEditCauseModal(cause) {
            this.resetCauseForm();
            this.editingCause                     = cause;
            this.causeForm.penyebab               = cause.penyebab;
            this.causeForm.deskripsi              = cause.deskripsi || '';
            this.causeForm.langkah_penyelesaian   = cause.langkah_penyelesaian || '';
            // lampiran dari server berupa array nama file → ubah ke {name}
            this.causeForm.lampiran = (cause.lampiran || []).map(name => ({ name }));
            this.openCauseModal = true;
        },

        async saveCause() {
            if (!this.causeForm.penyebab || !this.causeForm.langkah_penyelesaian) {
                Swal.fire('Oops!', 'Penyebab dan Langkah Penyelesaian wajib diisi', 'warning');
                return;
            }

            const isEdit = !!this.editingCause;
            const url    = isEdit
                ? `/knowledge-base/${this.selectedKB.id}/diagnosis/${this.editingCause.id}`
                : `/knowledge-base/${this.selectedKB.id}/diagnosis`;
            const method = isEdit ? 'PUT' : 'POST';

            // kirim sebagai FormData agar lampiran bisa ikut
            const formData = new FormData();
            formData.append('penyebab',             this.causeForm.penyebab);
            formData.append('deskripsi',            this.causeForm.deskripsi);
            formData.append('langkah_penyelesaian', this.causeForm.langkah_penyelesaian);
            this.causeForm.lampiran.forEach(f => {
                // hanya kirim File object (bukan {name} dari data lama)
                if (f instanceof File) formData.append('lampiran[]', f);
            });

            // untuk PUT, Laravel butuh _method spoofing
            if (isEdit) formData.append('_method', 'PUT');

            try {
                const res  = await fetch(url, {
                    method: isEdit ? 'POST' : 'POST', // pakai POST + _method untuk PUT
                    headers: {
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: formData
                });

                const text = await res.text();
                let   data;
                try { data = JSON.parse(text); } catch {
                    Swal.fire('Error', 'Response bukan JSON', 'error'); return;
                }

                if (!res.ok) {
                    const msg = data.errors
                        ? Object.values(data.errors).flat().join('\n')
                        : (data.message || 'Gagal menyimpan');
                    Swal.fire('Gagal!', msg, 'error'); return;
                }

                // update data lokal
                if (!this.selectedKB.diagnosis) this.selectedKB.diagnosis = [];

                if (isEdit) {
                    const idx = this.selectedKB.diagnosis.findIndex(d => d.id === this.editingCause.id);
                    if (idx !== -1) {
                        this.selectedKB.diagnosis[idx] = data.data;
                        if (this.selectedDiagnosis?.id === this.editingCause.id) {
                            this.selectedDiagnosis = data.data;
                        }
                    }
                } else {
                    this.selectedKB.diagnosis.push(data.data);
                }

                Swal.fire('Berhasil!', data.message, 'success');
                this.openCauseModal = false;
                this.editingCause   = null;
                this.resetCauseForm();

            } catch {
                Swal.fire('Error!', 'Tidak bisa terhubung ke server', 'error');
            }
        },

        async deleteCause(causeId) {
            if (!this.selectedKB) return;

            const confirm = await Swal.fire({
                title: 'Hapus Penyebab?',
                icon:  'warning',
                showCancelButton:  true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText:  'Batal'
            });
            if (!confirm.isConfirmed) return;

            try {
                const res = await fetch(`/knowledge-base/${this.selectedKB.id}/diagnosis/${causeId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });

                if (!res.ok) { Swal.fire('Gagal!', 'Gagal menghapus', 'error'); return; }

                const idx = this.selectedKB.diagnosis.findIndex(d => d.id === causeId);
                if (idx !== -1) this.selectedKB.diagnosis.splice(idx, 1);

                if (this.selectedDiagnosis?.id === causeId) this.selectedDiagnosis = null;

                Swal.fire('Dihapus!', 'Penyebab berhasil dihapus', 'success');

            } catch {
                Swal.fire('Error!', 'Tidak bisa terhubung ke server', 'error');
            }
        }
    }
}
</script>
@endsection