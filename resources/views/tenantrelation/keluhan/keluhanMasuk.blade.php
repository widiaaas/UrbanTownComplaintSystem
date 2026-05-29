@extends('layouts.app')

@section('title', 'Daftar Keluhan Belum Ditangani')

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
                        <td class="px-5 py-3 text-center font-medium"  x-text="k.unit?.nomor_unit ?? '-'"></td>
                        <td class="px-5 py-3 text-center" x-text="k.tanggal"></td>
                        <td class="px-5 py-3 text-center"  x-text="k.penghuni?.nama ?? '-'"></td>
                        <td class="px-5 py-3 text-center" x-text="k.judul"></td>
                        <td class="px-5 py-2 text-center">

                        <div class="flex items-center justify-center gap-1.5">

                            {{-- AMBIL --}}
                            <template x-if="!k.penanggungJawab">

                                <button
                                    @click="ambilKeluhan(k)"
                                    title="Ambil Keluhan"
                                    class="p-0.5 rounded-md
                                    hover:bg-emerald-50 transition">

                                    @include('components.buttons.btn-ambil')

                                </button>

                            </template>

                            {{-- DETAIL --}}
                            <button
                                @click="openModal(k)"
                                title="Detail Keluhan"
                                class="p-0.5 rounded-md
                                hover:bg-sky-50 transition">

                                @include('components.buttons.btn-view')

                            </button>

                        </div>

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

    {{-- ================= MODAL DETAIL KELUHAN ================= --}}
    <div
        x-show="showModal"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 bg-black/50
        backdrop-blur-sm
        z-50 flex items-center
        justify-center p-4">

        <div
            @click.outside="showModal = false"
            x-transition.scale
            class="bg-white w-full max-w-2xl
                max-h-[85vh]
                rounded-2xl shadow-2xl
                overflow-hidden flex flex-col">

            {{-- HEADER --}}
            <div
                class="px-6 py-4 border-b border-gray-100
                flex items-center justify-between">

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Detail Keluhan
                    </h2>

                    <p class="text-sm text-gray-500">
                        Informasi lengkap data keluhan penghuni
                    </p>

                </div>

                {{-- CLOSE --}}
                <button
                    @click="showModal = false"
                    class="w-9 h-9 rounded-lg
                    flex items-center justify-center
                    hover:bg-gray-100 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-gray-500"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2">

                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>

                    </svg>

                </button>

            </div>

            {{-- BODY --}}
            <div class="p-5 space-y-5 overflow-y-auto">

                {{-- JUDUL --}}
                <div>

                    <label
                        class="text-xs font-semibold
                        text-gray-700">

                        Judul Keluhan

                    </label>

                    <div
                        class="mt-1 bg-blue-50
                        border border-blue-100
                        rounded-xl px-4 py-3">

                        <p
                            class="text-base font-semibold
                            text-blue-800"
                            x-text="selected.judul">
                        </p>

                    </div>

                </div>

                {{-- INFORMASI --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- NO UNIT --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Nomor Unit

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.unit?.nomor_unit ?? '-'">
                        </div>

                    </div>

                    {{-- NO TIKET --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Nomor Tiket

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.nomor_tiket || '-'">
                        </div>

                    </div>

                    {{-- NAMA --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Nama Penghuni

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.penghuni?.nama ?? '-'">
                        </div>

                    </div>

                    {{-- TELEPON --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Nomor Telepon

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.penghuni?.no_telepon ?? '-'">
                        </div>

                    </div>

                    {{-- TANGGAL --}}
                    <div class="md:col-span-2">

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Tanggal Keluhan

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.tanggal || '-'">
                        </div>

                    </div>

                </div>

                {{-- DESKRIPSI --}}
                <div>

                    <label
                        class="text-xs font-semibold
                        text-gray-700">

                        Deskripsi Keluhan

                    </label>

                    <div
                        class="mt-1 bg-gray-50
                        border border-gray-100
                        rounded-xl px-4 py-4
                        text-sm text-gray-800 leading-relaxed"
                        x-text="selected.deskripsi || '-'">
                    </div>

                </div>

                {{-- LAMPIRAN --}}
                <div>

                    <label
                        class="text-xs font-semibold
                        text-gray-700">

                        Lampiran

                    </label>

                    <template
                        x-if="selected.lampiran &&
                        selected.lampiran.length">

                        <div
                            class="flex flex-wrap gap-2 mt-2">

                            <template
                                x-for="(file, i) in selected.lampiran"
                                :key="i">

                                <button
                                    @click="previewFile(file)"
                                    class="hover:scale-105 transition">

                                    @include('components.buttons.btn-view')

                                </button>

                            </template>

                        </div>

                    </template>

                    <template
                        x-if="!selected.lampiran ||
                        selected.lampiran.length === 0">

                        <div
                            class="mt-2 bg-gray-50
                            border border-dashed border-gray-200
                            rounded-xl px-4 py-4
                            text-sm text-gray-400 italic">

                            Tidak ada lampiran

                        </div>

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

                    <div
                        class="flex items-center justify-between
                        bg-gray-50 border border-gray-200
                        rounded-2xl px-4 py-3">

                        {{-- LEFT --}}
                        <div class="flex items-center gap-3 min-w-0">

                            {{-- ICON --}}
                            <div
                                class="w-11 h-11 rounded-xl
                                bg-red-100 text-red-600
                                flex items-center justify-center
                                shrink-0 text-lg">

                                📄

                            </div>

                            {{-- INFO --}}
                            <div class="min-w-0">

                                <p
                                    class="text-sm font-semibold
                                    text-gray-800">

                                    File PDF

                                </p>

                                <p
                                    class="text-xs text-gray-400
                                    truncate max-w-[240px]"
                                    x-text="previewUrl.split('/').pop()">
                                </p>

                            </div>

                        </div>

                        {{-- ACTION --}}
                        <div
                            class="flex items-center gap-2
                            shrink-0">

                            {{-- VIEW --}}
                            <a
                                :href="previewUrl"
                                target="_blank"
                                title="Buka PDF"
                                class="hover:scale-105 transition">

                                @include('components.buttons.btn-view')

                            </a>

                        </div>

                    </div>

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