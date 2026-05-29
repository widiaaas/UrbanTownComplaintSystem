@extends('layouts.app')

@section('title', 'Detail Work Order')

@section('content')
<div x-data="detailWOApp()" x-init="init()" class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Work Order</h1>
            <p class="text-sm text-gray-500">No WO: <span x-text="wo.nomor_tiket"></span></p>
        </div>
    
        <a href="/daftar-work-order"
            class="inline-flex items-center gap-2
            text-sm font-medium text-blue-600
            hover:text-blue-700 transition">

            @include('components.icons.arrowLeft')

            <span>Kembali</span>

        </a>
    </div>

    <template x-if="readonly">
        <div
            class="bg-yellow-50 border border-yellow-200
            text-yellow-700 px-4 py-3 rounded-xl text-sm">
            Anda hanya dapat melihat detail work order
            karena bukan petugas penanggung jawab.
        </div>
    </template>

    {{-- ================= INFO UTAMA WO ================= --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm bg-white p-6 rounded-xl shadow">
        <p><b>Departemen</b><br><span x-text="wo.departemen?.nama_departemen"></span></p>
        <p><b>TR Penanggung Jawab</b><br><span x-text="wo.tr"></span></p>
        <p><b>Tanggal WO</b><br><span x-text="wo.tanggal"></span></p>
        <p><b>Status WO</b><br>
            <span class="inline-block text-xs px-2 py-1 rounded"
                :class="statusClass(wo.status)"
                x-text="formatStatus(wo.status)">
            </span>
        </p>
    <p>
        <b>No. Tiket Keluhan</b><br>
        <span x-text="wo.nomor_tiket_keluhan"></span>
    </p>
    </div>

    {{-- ================= PENGAJUAN PENGHUNI ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-5">

        <h3 class="font-semibold">
            Pengajuan Penghuni
        </h3>

        <div class="grid md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="font-medium mb-1">
                    Nomor Unit
                </p>

                <div
                    class="bg-gray-100 rounded-lg p-3 text-gray-700"
                    x-text="wo.unit || '-'">
                </div>
            </div>

            <div>
                <p class="font-medium mb-1">
                    Nama Penghuni
                </p>

                <div
                    class="bg-gray-100 rounded-lg p-3 text-gray-700"
                    x-text="wo.penghuni || '-'">
                </div>
            </div>

            <div>

                <p class="font-medium mb-1">
                    Nomor Telepon
                </p>

                <div
                    class="bg-gray-100 rounded-lg p-3 text-gray-700"
                    x-text="wo.no_telepon || '-'">
                </div>

            </div>

        </div>

        <div>
            <p class="text-sm font-medium mb-1">
                Judul Keluhan
            </p>

            <div
                class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700"
                x-text="wo.judul_keluhan || '-'">
            </div>
        </div>

        <div>
            <p class="text-sm font-medium mb-1">
                Deskripsi Keluhan
            </p>

            <div
                class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line"
                x-text="wo.deskripsi || '-'">
            </div>
        </div>

        {{-- LAMPIRAN --}}
        <div>

            <p class="text-sm font-medium mb-2">
                Lampiran Penghuni
            </p>

            <template
                x-if="
                    wo.lampiran_pengajuan &&
                    wo.lampiran_pengajuan.length
                ">

                <div class="flex flex-wrap gap-2">

                    <template
                        x-for="(file, i) in wo.lampiran_pengajuan"
                        :key="i">

                        <button
                            @click="openPreviewFile(file)"
                            title="Preview Lampiran"
                            class="inline-flex items-center gap-1.5
                            px-2 py-1 rounded-lg
                            bg-blue-50 text-blue-700
                            text-xs font-medium
                            hover:bg-blue-100 transition">

                            @include('components.buttons.btn-view')

                            <span
                                x-text="
                                    wo.lampiran_pengajuan.length > 1
                                    ? 'Lampiran ' + (i + 1)
                                    : 'Lampiran'
                                ">
                            </span>

                        </button>

                    </template>

                </div>

            </template>

            <template
                x-if="
                    !wo.lampiran_pengajuan ||
                    !wo.lampiran_pengajuan.length
                ">

                <p class="text-xs text-gray-400 italic">
                    Tidak ada lampiran
                </p>

            </template>

        </div>

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
        <div>
            <p class="text-sm font-medium mb-2">
                Lampiran Work Order
            </p>

            <template
                x-if="
                    wo.lampiran &&
                    wo.lampiran.length
                ">

                <div class="flex flex-wrap gap-2">

                    <template
                        x-for="(file, i) in wo.lampiran"
                        :key="i">

                        <button
                            @click="openPreviewFile(file)"
                            title="Preview Lampiran"
                            class="inline-flex items-center gap-1.5
                            px-2 py-1 rounded-lg
                            bg-blue-50 text-blue-700
                            text-xs font-medium
                            hover:bg-blue-100 transition">

                            @include('components.buttons.btn-view')

                            <span
                                x-text="
                                    wo.lampiran.length > 1
                                    ? 'Lampiran ' + (i + 1)
                                    : 'Lampiran'
                                ">
                            </span>

                        </button>

                    </template>

                </div>

            </template>

            <template
                x-if="
                    !wo.lampiran ||
                    !wo.lampiran.length
                ">

                <p class="text-xs text-gray-400 italic">
                    Tidak ada lampiran
                </p>

            </template>

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
                            <div
                                class="flex flex-wrap gap-2 mt-2"
                                x-show="lapor.lampiran && lapor.lampiran.length">

                                <template
                                    x-for="(file, i) in lapor.lampiran"
                                    :key="i">

                                    <button
                                        @click="openPreviewFile(file)"
                                        title="Preview Lampiran"
                                        class="inline-flex items-center gap-1.5
                                        px-2 py-1 rounded-lg
                                        bg-blue-50 text-blue-700
                                        text-xs font-medium
                                        hover:bg-blue-100 transition">

                                        @include('components.buttons.btn-view')

                                        <span
                                            x-text="
                                                lapor.lampiran.length > 1
                                                ? 'Lampiran ' + (i + 1)
                                                : 'Lampiran'
                                            ">
                                        </span>

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

            <template x-if="(typeof previewFile === 'object' && previewFile.type.startsWith('image/')) || isImage(previewFile)">
                <img 
                    :src="typeof previewFile === 'object' ? URL.createObjectURL(previewFile) : '/storage/' + previewFile"
                    class="max-h-[70vh] mx-auto rounded">
            </template>

            <!-- 🔥 PDF -->
            <template
                x-if="
                    (typeof previewFile === 'object' &&
                    previewFile.type === 'application/pdf')
                    ||
                    isPDF(previewFile)
                ">

                <div
                    class="flex items-center justify-between
                    bg-gray-50 border border-gray-200
                    rounded-xl px-4 py-3
                    w-full max-w-3xl">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3">

                        {{-- ICON --}}
                        <div
                            class="w-10 h-10 rounded-lg
                            bg-red-100 text-red-600
                            flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2">

                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>

                                <path d="M14 2v6h6"/>

                            </svg>

                        </div>

                        {{-- FILE NAME --}}
                        <div
                            class="text-sm font-medium
                            text-gray-700 truncate
                            max-w-[250px]"
                            x-text="
                                typeof previewFile === 'object'
                                ? previewFile.name
                                : previewFile.split('/').pop()
                            ">
                        </div>

                    </div>

                    {{-- ACTION --}}
                    <a
                        :href="
                            typeof previewFile === 'object'
                            ? URL.createObjectURL(previewFile)
                            : '/storage/' + previewFile
                        "
                        target="_blank"
                        class="text-sm font-medium
                        text-blue-600 hover:text-blue-700">

                        Buka PDF

                    </a>

                </div>

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
            :disabled="readonly || normalizeStatus(wo.status) === 'close'"
            class="w-full border rounded-lg px-3 py-2">
            <option value="Open">Open</option>
            <option value="On Progress">On Progress</option>
            <option value="Waiting">Waiting</option>
            <option value="Close">Close</option>
        </select>
    </div>

    {{-- ================= FORM PENANGANAN ================= --}}
    <template x-if=" !readonly && normalizeStatus(wo.status) !== 'close'">
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
                <div
                    class="flex items-center gap-2
                    bg-gray-50 border border-gray-200
                    rounded-xl px-3 py-2
                    shadow-sm">

                    {{-- FILE NAME --}}
                    <div
                        class="max-w-[180px]
                        truncate text-xs
                        font-medium text-gray-700"
                        x-text="file.name">
                    </div>

                    {{-- PREVIEW --}}
                    <button
                        @click="openPreviewFile(file)"
                        title="Preview Lampiran"
                        class="hover:scale-105 transition">

                        @include('components.buttons.btn-view')

                    </button>

                    {{-- DELETE --}}
                    <button
                        @click="hapusLampiranPenanganan(index)"
                        title="Hapus Lampiran"
                        class="hover:scale-105 transition">

                        @include('components.buttons.btn-delete')

                    </button>

                </div>
                </template>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button @click="simpanPenanganan"
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    Simpan Penanganan
                </button>
            </div>
        </div>
    </template>
</div>

<script>

function detailWOApp() {
    return {
        wo: @json($wo),

        penanganan: { judul: '', deskripsi: '', lampiran: [] },

        openPreview: false,
        previewFile: null,
        newStatus: '',
        readonly: @json($readonly ?? false),

        /* ===== INIT ===== */
        init() {
            this.wo.status = this.normalizeStatus(this.wo.status);
            this.newStatus = this.formatStatus(this.wo.status);
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

                let message =
                    err.error ||
                    err.message ||
                    'Terjadi kesalahan';

                // VALIDATION ERROR
                if (err.errors) {

                    message = Object.values(err.errors)
                        .flat()
                        .join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: message
                });
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
        }

    }
}
</script>
@endsection