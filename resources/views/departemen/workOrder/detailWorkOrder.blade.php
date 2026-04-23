@extends('layouts.app')

@section('title', 'Detail Work Order')

@section('content')
<div x-data="detailWOApp()" x-init="init()" class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Work Order</h1>
            <p class="text-sm text-gray-500">
                No WO: <span x-text="wo.no"></span>
            </p>
        </div>

        <a href="/daftar-work-order" class="text-sm text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

    {{-- ================= INFO UTAMA WO ================= --}}
    <div class="grid grid-cols-2 gap-4 text-sm bg-white p-6 rounded-xl shadow">
        <!-- <p><b>Nomor Tiket</b><br><span x-text="wo.tiket"></span></p> -->
        <p><b>Departemen</b><br><span x-text="wo.dept"></span></p>
        <p><b>TR Penanggung Jawab</b><br><span x-text="wo.tr"></span></p>
        <p><b>Tanggal WO</b><br><span x-text="wo.tanggal"></span></p>
        <p><b>Status WO</b><br>
        <span 
            class="inline-block text-xs px-2 py-1 rounded"
            :class="statusClass(wo.status)"
            x-text="formatStatus(wo.status)">
        </span>
        </p>
    </div>

    {{-- ================= INSTRUKSI PEKERJAAN ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-5">

        <!-- HEADER -->
        <h3 class="font-semibold">Instruksi Pekerjaan</h3>

        <!-- INSTRUKSI -->
        <div>
            <p class="text-sm font-medium mb-1">Instruksi</p>
            <div class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700"
                x-text="wo.instruksi || '-'">
            </div>
        </div>

        <!-- LOKASI -->
        <div>
            <p class="text-sm font-medium mb-1">Lokasi</p>
            <div class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700"
                x-text="wo.lokasi || '-'">
            </div>
        </div>

    </div>

    {{-- ================= RIWAYAT PENANGANAN ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">

        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">Riwayat Penanganan</h3>
        </div>

        <!-- DATA ADA -->
        <template x-if="wo.laporan && wo.laporan.length">
            <div class="relative">

                <!-- SCROLL AREA -->
                <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2">

                    <template x-for="(lapor, index) in wo.laporan" :key="index">
                        <div class="relative pl-6 py-3 rounded-md border-l-2"
                            :class="statusClassRiwayat(lapor.status)">

                            <!-- DOT -->
                            <span class="absolute -left-2 top-4 w-3 h-3 rounded-full"
                                :class="{
                                    'bg-blue-500': normalizeStatus(lapor.status) === 'open',
                                    'bg-yellow-500': normalizeStatus(lapor.status) === 'on_progress',
                                    'bg-orange-500': normalizeStatus(lapor.status) === 'waiting',
                                    'bg-green-500': normalizeStatus(lapor.status) === 'close'
                                }">
                            </span>

                            <!-- JUDUL -->
                            <p class="font-medium text-gray-800" x-text="lapor.judul"></p>

                            <!-- deskripsi -->
                            <p class="text-gray-600 mt-1" x-text="lapor.deskripsi"></p>

                            <!-- LAMPIRAN -->
                            <div class="flex flex-wrap gap-2 mt-2"
                                x-show="lapor.lampiran && lapor.lampiran.length">

                                <template x-for="(file, i) in lapor.lampiran" :key="i">
                                    <button
                                        @click="openPreviewFile(file)"
                                        class="px-3 py-1 text-xs rounded bg-blue-100 text-blue-700 hover:bg-blue-200">

                                        <span x-text="file.split('/').pop()"></span>
                                    </button>
                                </template>

                            </div>

                            <!-- WAKTU -->
                            <p class="text-xs text-gray-400 mt-2" x-text="lapor.waktu"></p>

                        </div>
                    </template>

                </div>

                <!-- FADE EFFECT (OPSIONAL BIAR KEREN) -->
                <div class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>

            </div>
        </template>

        <!-- DATA KOSONG -->
        <template x-if="!wo.laporan || !wo.laporan.length">
            <p class="text-sm text-gray-400 italic text-center py-4">
                Belum ada laporan pekerjaan dari departemen.
            </p>
        </template>

    </div>

    <!-- MODAL PREVIEW FILE -->
    <div x-show="openPreview" x-cloak
        class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">

        <div class="bg-white w-full max-w-4xl rounded-xl p-4 relative">

            <!-- CLOSE -->
            <button
                @click="openPreview = false"
                class="absolute top-3 right-3 text-xl text-gray-600 hover:text-black">
                ✕
            </button>

            <!-- TITLE -->
            <p class="text-sm font-semibold mb-3" x-text="previewFile"></p>

            <!-- IMAGE -->
            <template x-if="isImage(previewFile)">
                <img
                    :src="'/storage/' + previewFile"
                    class="max-h-[70vh] mx-auto rounded">
            </template>

            <!-- PDF -->
            <template x-if="isPDF(previewFile)">
                <iframe
                    :src="'/storage/' + previewFile"
                    class="w-full h-[70vh] rounded">
                </iframe>
            </template>

            <!-- OTHER -->
            <template x-if="!isImage(previewFile) && !isPDF(previewFile)">
                <div class="text-center text-gray-500 py-10">
                    Preview tidak tersedia
                </div>
            </template>

        </div>
    </div>


    {{-- STATUS workOrder --}}
    <div class="bg-white p-4 rounded-xl border space-y-2">
        <h3 class="font-semibold text-sm">Status Work Order</h3>

        <select 
            x-model="newStatus"
            @change="ubahStatus"
            :disabled="normalizeStatus(wo.status) === 'close'"
            class="w-full border rounded-lg px-3 py-2">
            
            <option value="Open">Open</option>
            <option value="On Progress">On Progress</option>
            <option value="Waiting">Waiting</option>
            <option value="Close">Close</option>
        </select>
    </div>

    {{-- ================= FORM Penanganan ================= --}}
    <template x-if="normalizeStatus(wo.status) !== 'close'">
        <div class="bg-white p-6 rounded-xl shadow space-y-4">

            <h3 class="font-semibold">Form Penanganan WO</h3>

            {{-- JUDUL Penanganan --}}
            <div>
                <label class="text-sm font-medium mb-1 block">
                    Judul Penanganan
                </label>
                <input
                    type="text"
                    x-model="penanganan.judul"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    placeholder="Masukkan judul penanganan"
                >
            </div>

            {{-- deskripsi Penanganan --}}
            <div>
                <label class="text-sm font-medium mb-1 block">
                    deskripsi Penanganan
                </label>
                <textarea
                    x-model="penanganan.deskripsi"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    rows="3"
                    placeholder="Masukkan deskripsi penanganan"
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
                    @change="handleUploadPenanganan($event)"
                    class="text-sm"
                >

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(file, index) in penanganan.lampiran" :key="index">
                        <div class="relative border rounded px-3 py-1 text-xs bg-gray-50">
                            <span x-text="file.name"></span>
                            <button
                                @click="hapusLampiranPenanganan(index)"
                                class="ml-2 text-red-500 hover:text-red-700">
                                ✕
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex gap-3 pt-2">
                <button
                    @click="simpanPenanganan"
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

        penanganan: {
            judul: '',
            deskripsi: '',
            lampiran: []
        },

        openPreview: false,
        previewFile: null,

        openPreviewFile(file){
            this.previewFile = file;
            this.openPreview = true;
        },

        isImage(file){
            return file && (
                file.endsWith('.jpg') ||
                file.endsWith('.jpeg') ||
                file.endsWith('.png')
            );
        },

        isPDF(file){
            return file && file.endsWith('.pdf');
        },

        newStatus: '',

        init() {
            this.wo.status = this.normalizeStatus(this.wo.status);
            this.newStatus = this.formatStatus(this.wo.status);
        },

        // ================= UPLOAD =================
        handleUploadPenanganan(event) {
            for (let i = 0; i < event.target.files.length; i++) {
                this.penanganan.lampiran.push(event.target.files[i]);
            }
        },

        hapusLampiranPenanganan(index) {
            this.penanganan.lampiran.splice(index, 1);
        },

        // ================= SIMPAN =================
        async simpanPenanganan() {

            // VALIDASI
            if (!this.penanganan.judul) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Judul penanganan tidak boleh kosong'
                });
                return;
            }

            if (!this.penanganan.deskripsi) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'deskripsi penanganan tidak boleh kosong'
                });
                return;
            }

            // 🔥 FIX STATUS (IMPORTANT)
            const status = this.newStatus || this.formatStatus(this.wo.status);

            // CONFIRM
            const confirm = await Swal.fire({
                title: 'Simpan Penanganan?',
                text: 'Data akan disimpan dan status akan diperbarui',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            });

            if (!confirm.isConfirmed) return;

            try {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const formData = new FormData();
                formData.append('judul', this.penanganan.judul);
                formData.append('deskripsi', this.penanganan.deskripsi);


                this.penanganan.lampiran.forEach(file => {
                    formData.append('lampiran[]', file);
                });
                
                const res = await fetch(`/work-order/${this.wo.id}/penanganan`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json' // 🔥 WAJIB
                    },
                    body: formData
                });

                const text = await res.text();

                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Response bukan JSON:', text);
                    throw new Error('Server error (bukan JSON)');
                }

                if (!res.ok) throw data;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Penanganan berhasil disimpan',
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => location.reload(), 1500);

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: err.message || 'Terjadi kesalahan'
                });
            }
        },

        // ================= STATUS =================
        async ubahStatus() {

            if (this.newStatus === this.formatStatus(this.wo.status)) return;

            const confirm = await Swal.fire({
                title: 'Ubah Status?',
                text: `Status akan diubah menjadi ${this.newStatus}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, ubah',
                cancelButtonText: 'Batal'
            });

            if (!confirm.isConfirmed) {
                this.newStatus = this.formatStatus(this.wo.status);
                return;
            }

            try {
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const res = await fetch(`/work-order/${this.wo.id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: this.newStatus
                    })
                });

                const data = await res.json();
                if (!res.ok) throw data;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 1200,
                    showConfirmButton: false
                });

                // 🔥 UPDATE UI
                this.wo.status = this.normalizeStatus(this.newStatus);

            } catch (err) {
                this.newStatus = this.formatStatus(this.wo.status);

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: err.message || 'Terjadi kesalahan'
                });
            }
        },

        // ================= HELPER =================
        normalizeStatus(status){
            return (status || '')
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '_');
        },

        formatStatus(status){
            const s = this.normalizeStatus(status);

            if(s === 'open') return 'Open';
            if(s === 'on_progress') return 'On Progress';
            if(s === 'waiting') return 'Waiting';
            if(s === 'close') return 'Close';

            return status;
        },

        statusClass(status){
            const s = this.normalizeStatus(status);

            return {
                'bg-blue-100 text-blue-700': s === 'open',
                'bg-yellow-100 text-yellow-700': s === 'on_progress',
                'bg-orange-100 text-orange-700': s === 'waiting',
                'bg-green-100 text-green-700': s === 'close',
                'bg-gray-100 text-gray-700': !['open','on_progress','waiting','close'].includes(s)
            }
        },

        statusClassRiwayat(status){
            const s = this.normalizeStatus(status);

            return {
                'border-l-4 border-blue-500 bg-blue-50/30': s === 'open',
                'border-l-4 border-yellow-500 bg-yellow-50/30': s === 'on_progress',
                'border-l-4 border-orange-500 bg-orange-50/30': s === 'waiting',
                'border-l-4 border-green-500 bg-green-50/30': s === 'close'
            }
        },

        openPreviewFile(file){
            if(!file) return;
            this.previewFile = file;
            this.openPreview = true;
        },
    }
}
</script>
@endsection