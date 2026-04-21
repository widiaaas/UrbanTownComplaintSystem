@extends('layouts.app')

@section('title', 'Keluhan Masuk')

@section('content')
<div x-data="keluhanApp()" class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Keluhan Masuk</h1>
        <p class="text-sm text-gray-500">TR dapat mengambil keluhan untuk menjadi tanggung jawabnya</p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-5 py-3 text-center">No</th>
                    <th class="px-5 py-3 text-center">Unit</th>
                    <th class="px-5 py-3 text-center">Tanggal</th>
                    <th class="px-5 py-3 text-center">Penghuni</th>
                    <th class="px-5 py-3 text-center">Judul Keluhan</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(k, index) in dataKeluhanMasuk" :key="k.id">
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-5 py-3 text-center" x-text="index + 1"></td>
                        <td class="px-5 py-3 text-center font-medium" 
                            x-text="k.unit?.no_unit ?? '-'">
                        </td>

                        <td class="px-5 py-3 text-center" 
                            x-text="k.tanggal">
                        </td>

                        <td class="px-5 py-3 text-center" 
                            x-text="k.penghuni?.nama ?? '-'">
                        </td>
                        <td class="px-5 py-3 text-center" x-text="k.judul"></td>
                        <td class="px-5 py-3 text-center space-x-1">
                            
                            {{-- Ambil --}}
                            <button 
                                x-show="!k.penanggungJawab"
                                @click="ambilKeluhan(k)"
                                class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                Ambil
                            </button>

                            {{-- Detail --}}
                            <button 
                                @click="openModal(k)"
                                class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                                Detail
                            </button>
                        </td>
                    </tr>
                </template>

                <template x-if="dataKeluhanMasuk.length === 0">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500 italic">
                            Tidak ada keluhan baru
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- MODAL --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div @click.outside="showModal=false"
             class="bg-gray-100 w-full max-w-xl rounded-xl shadow-lg overflow-hidden">

            {{-- HEADER --}}
            <div class="px-6 py-4 bg-gray-200 border-b flex justify-between">
                <h2 class="text-lg font-semibold">Detail Keluhan</h2>
                <button @click="showModal=false">&times;</button>
            </div>
            {{-- BODY --}}
            <div class="p-6 space-y-3 text-sm bg-white">
                <p class="text-lg font-semibold text-gray-800" x-text="selected.judul"></p>
                <p><b>No. Unit:</b> <span x-text="selected.unit?.no_unit ?? '-'"></span></p>
                <p><b>No. Tiket:</b> <span x-text="selected.ticket"></span></p>
                <p><b>Nama:</b> <span x-text="selected.penghuni?.nama ?? '-'"></span></p>
                <p><b>Telepon:</b> <span x-text="selected.penghuni?.telepon ?? '-'"></span></p>
                <p><b>Tanggal:</b> <span x-text="selected.tanggal"></span></p>

                <div>
                    <p class="font-medium">Deskripsi:</p>
                    <div class="bg-gray-100 p-3 rounded" x-text="selected.deskripsi"></div>
                </div>

                {{-- LAMPIRAN --}}
                <div>
                    <p class="font-medium">Lampiran:</p>

                    <template x-if="selected.lampiran && selected.lampiran.length">
                        <div class="flex flex-wrap gap-2 mt-2">
                            <template x-for="(file, i) in selected.lampiran" :key="i">
                                <button @click="previewFile(file)"
                                        class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:underline">
                                    📎 Preview
                                </button>
                            </template>
                        </div>
                    </template>

                    <template x-if="!selected.lampiran || selected.lampiran.length === 0">
                        <p class="text-xs text-gray-400 italic">Tidak ada lampiran</p>
                    </template>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL PREVIEW FILE --}}
        <div x-show="showPreview" x-cloak
        class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
        @click.self="showPreview=false">

        <div class="bg-white w-full max-w-5xl rounded-lg overflow-hidden shadow-xl">

            {{-- HEADER --}}
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="font-semibold text-gray-700">Preview Lampiran</h2>
                <button @click="showPreview=false"
                        class="text-xl font-bold text-gray-500 hover:text-black">
                    ✕
                </button>
            </div>

            {{-- CONTENT --}}
            <div class="p-4 flex justify-center items-center bg-gray-100">

                {{-- IMAGE --}}
                <template x-if="isImage(previewUrl)">
                    <img :src="previewUrl"
                    class="max-h-[75vh] object-contain rounded shadow-lg cursor-zoom-in">
                </template>

                {{-- PDF --}}
                <template x-if="isPdf(previewUrl)">
                    <iframe :src="previewUrl"
                            class="w-full h-[75vh] border rounded"></iframe>
                </template>

                {{-- FILE LAIN --}}
                <template x-if="!isImage(previewUrl) && !isPdf(previewUrl)">
                    <a :href="previewUrl" target="_blank"
                    class="text-blue-600 underline text-sm">
                        Download File
                    </a>
                </template>

            </div>

        </div>
    </div>

</div>

<script>
function keluhanApp() {
    return {
        showModal: false,
        selected: {},
        loading: false,
        showPreview: false,
        previewUrl: '',

        previewFile(file){
            this.previewUrl = '/storage/' + file;
            this.showPreview = true;
        },

        isImage(url){
            return url.match(/\.(jpg|jpeg|png)$/i);
        },

        isPdf(url){
            return url.match(/\.pdf$/i);
        },

        // 🔥 DATA DARI BACKEND
        dataKeluhanMasuk: @json($keluhan),

        // ================= AMBIL KELUHAN =================
        ambilKeluhan(k){
            Swal.fire({
                title: 'Ambil Keluhan?',
                text: "Keluhan akan menjadi tanggung jawab kamu",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ambil!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.prosesAmbilKeluhan(k);
                }
            });
        },

        prosesAmbilKeluhan(k){
            this.loading = true;

            fetch(`/keluhan/${k.id}/ambil`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(res => {

                // 🔥 SUCCESS ALERT
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                // hapus dari list
                this.dataKeluhanMasuk = this.dataKeluhanMasuk.filter(x => x.id !== k.id);
            })
            .catch(() => {

                // 🔥 ERROR ALERT
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan'
                });

            })
            .finally(() => this.loading = false);
        },

        // ================= MODAL =================
        openModal(k){
            this.selected = JSON.parse(JSON.stringify(k));
            this.showModal = true;
        },

        // ================= STATUS =================
        statusClass(status){
            const s = (status || '').toLowerCase();

            return {
                'bg-gray-100 text-gray-700': s === 'unassign',
                'bg-blue-100 text-blue-700': s === 'open',
                'bg-yellow-100 text-yellow-700': s === 'on progress',
                'bg-green-100 text-green-700': s === 'close'
            }
        }
    }
}
</script>
@endsection