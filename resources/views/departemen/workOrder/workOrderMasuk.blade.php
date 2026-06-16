@extends('layouts.app')

@section('title', 'Work Order Masuk')

@section('content')
<div x-data="workOrderApp()" class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Work Order Masuk</h1>
        <p class="text-sm text-gray-500">Petugas dapat mengambil WO untuk menjadi tanggung jawabnya</p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-5 py-3 text-center">No</th>
                    <th class="px-5 py-3 text-center">Nomor WO</th>
                    <th class="px-5 py-3 text-center">Unit</th>
                    <th class="px-5 py-3 text-center">Tanggal</th>
                    <th class="px-5 py-3 text-center">Instruksi</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="wo in dataWOMasuk" :key="wo.id">
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-5 py-3 text-center"x-text="dataWOMasuk.indexOf(wo) + 1"></td>
                        <td class="px-5 py-3 font-medium"x-text="wo.nomor_tiket"></td>
                        <td class="px-5 py-3 font-medium" x-text="wo.unit"></td>
                        <td class="px-5 py-3" x-text="wo.tanggal"></td>
                        <td class="px-5 py-3" x-text="wo.instruksi"></td>
                        <td class="px-5 py-2 text-center">

                            <div class="flex items-center justify-center gap-1.5">

                                {{-- AMBIL WO --}}
                                <template x-if="!wo.diambil">

                                    <button
                                        @click="ambilWO(wo)"
                                        title="Ambil Work Order"
                                        class="p-0.5 rounded-md
                                        hover:bg-emerald-50 transition">

                                        @include('components.buttons.btn-ambil')

                                    </button>

                                </template>

                                {{-- DETAIL --}}
                                <button
                                    @click="openModal(wo)"
                                    title="Detail Work Order"
                                    class="p-0.5 rounded-md
                                    hover:bg-sky-50 transition">

                                    @include('components.buttons.btn-view')

                                </button>

                            </div>

                        </td>
                    </tr>
                </template>

                <template x-if="dataWOMasuk.length === 0">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500 italic">
                            Tidak ada Work Order baru
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- MODAL DETAIL WO --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="if(!openPreview) showModal = false"
             class="bg-white w-full sm:max-w-md md:max-w-2xl rounded-lg shadow-lg max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div class="px-6 py-4 bg-gray-200 flex justify-between items-center border-b flex-shrink-0">
                <h2 class="text-lg font-semibold text-gray-800">Detail Work Order</h2>
                <button @click="showModal = false" class="text-gray-700 text-2xl hover:text-gray-900">&times;</button>
            </div>

            {{-- Body --}}
            <div class="p-6 flex-1 overflow-y-auto space-y-6 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <p><strong>No. Unit:</strong> <span x-text="selected.unit"></span></p>
                        <p><strong>No. WO / Tiket:</strong> <span x-text="selected.nomor_tiket"></span></p>
                        <p><strong>Requestor / Penghuni:</strong> <span x-text="selected.requestor ?? selected.penghuni"></span></p>
                    </div>
                    <div class="space-y-2">
                        <p><strong>Telepon:</strong> <span x-text="selected.no_telepon"></span></p>
                        <p><strong>Tanggal:</strong> <span x-text="selected.tanggal"></span></p>
                        <p><strong>TR penanggung Jawab:</strong> <span x-text="selected.tr"></span></p>
                    </div>
                </div>

                {{-- ================= PENGAJUAN PENGHUNI ================= --}}
                <div class="bg-white p-6 rounded-xl shadow space-y-5">

                    <h3 class="font-semibold">
                        Pengajuan Penghuni
                    </h3>

                    <div class="grid grid-cols-2 gap-4 text-sm">

                        <div>
                            <p class="font-medium mb-1">
                                Nomor Unit
                            </p>

                            <div
                                class="bg-gray-100 rounded-lg p-3 text-gray-700"
                                x-text="selected.unit">
                            </div>
                        </div>

                        <div>
                            <p class="font-medium mb-1">
                                Penghuni
                            </p>

                            <div
                                class="bg-gray-100 rounded-lg p-3 text-gray-700"
                                x-text="selected.penghuni">
                            </div>
                        </div>

                    </div>

                    <div>
                        <p class="text-sm font-medium mb-1">
                            Judul Keluhan
                        </p>

                        <div
                            class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700"
                            x-text="selected.judul_keluhan">
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium mb-1">
                            Deskripsi Keluhan
                        </p>

                        <div
                            class="bg-gray-100 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line"
                            x-text="selected.deskripsi_keluhan">
                        </div>
                    </div>

                    {{-- LAMPIRAN --}}
                    <div>

                        <p class="text-sm font-medium mb-2">
                            Lampiran Penghuni
                        </p>

                        <template
                            x-if="
                                selected.lampiran_pengajuan &&
                                selected.lampiran_pengajuan.length
                            ">

                            <div class="flex flex-wrap gap-2">

                                <template
                                    x-for="(file, i) in selected.lampiran_pengajuan"
                                    :key="i">

                                    <button
                                        @click="previewFile(file)"
                                        title="Preview Lampiran"
                                        class="inline-flex items-center gap-1.5
                                        px-2 py-1 rounded-lg
                                        bg-blue-50 text-blue-700
                                        text-xs font-medium
                                        hover:bg-blue-100 transition">

                                        @include('components.buttons.btn-view')

                                        <span
                                            x-text="
                                                selected.lampiran_pengajuan.length > 1
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
                                !selected.lampiran_pengajuan ||
                                !selected.lampiran_pengajuan.length
                            ">

                            <p class="text-xs text-gray-400 italic">
                                Tidak ada lampiran
                            </p>

                        </template>

                    </div>

                </div>
                {{-- Instruksi --}}
                <div class="bg-white rounded-lg p-4 border">
                    <p class="font-medium mb-2">Instruksi Detail:</p>
                    <div class="bg-gray-100 p-3 rounded" x-text="selected.instruksi"></div>
                </div>


                {{-- Lampiran --}}
                <div>
                    <p class="font-medium mb-2">Lampiran Work Order:</p>

                    <div class="flex flex-wrap gap-2">

                        <template
                            x-if="selected.lampiran &&
                            selected.lampiran.length">

                            <template
                                x-for="(file, i) in selected.lampiran"
                                :key="i">

                                <button
                                    @click="previewFile(file)"
                                    title="Preview Lampiran"
                                    class="inline-flex items-center gap-1.5
                                    px-2 py-1 rounded-lg
                                    bg-blue-50 text-blue-700
                                    text-xs font-medium
                                    hover:bg-blue-100 transition">

                                    @include('components.buttons.btn-view')

                                    <span
                                        x-text="
                                            selected.lampiran.length > 1
                                            ? 'Lampiran ' + (i + 1)
                                            : 'Lampiran'
                                        ">
                                    </span>

                                </button>

                            </template>

                        </template>

                        <template
                            x-if="!selected.lampiran ||
                            !selected.lampiran.length">

                            <div
                                class="bg-gray-50 border
                                border-dashed border-gray-200
                                rounded-xl px-4 py-3
                                text-sm text-gray-400 italic">

                                Tidak ada lampiran

                            </div>

                        </template>

                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-3 bg-gray-100 border-t flex justify-end flex-shrink-0">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
            </div>

        </div>
    </div>

    <!-- ================= MODAL PREVIEW FILE ================= -->
    <div
        x-show="openPreview"
        x-cloak
        @click.self="openPreview = false"
        class="fixed inset-0 bg-black/70
        flex items-center justify-center
        z-[9999]">
            <div
             @click.stop
             class="bg-white rounded-lg p-4 max-w-4xl w-full relative">

            <button
                @click.stop="openPreview = false"
                type="button"
                class="absolute top-2 right-2 text-xl">
                ✕
            </button>

                <div class="mt-6">

                    <!-- IMAGE -->
                    <template
                        x-if="
                            previewUrl.endsWith('.jpg') ||
                            previewUrl.endsWith('.png') ||
                            previewUrl.endsWith('.jpeg')
                        ">

                        <div
                            class="flex justify-center items-center
                            max-h-[75vh] overflow-auto">

                            <img
                                :src="'/storage/' + previewUrl"
                                class="max-w-full max-h-[75vh]
                                object-contain rounded-2xl shadow">

                        </div>

                    </template>

                    <!-- PDF -->
                    <template x-if="previewUrl.endsWith('.pdf')">

                    <div
                        class="flex items-center justify-between
                        bg-gray-50 border border-gray-200
                        rounded-xl px-4 py-3
                        w-full max-w-4xl">

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
                                x-text="previewUrl.split('/').pop()">
                            </div>

                        </div>

                        {{-- ACTION --}}
                        <a
                            :href="'/storage/' + previewUrl"
                            target="_blank"
                            class="text-sm font-medium
                            text-blue-600 hover:text-blue-700">

                            Buka PDF

                        </a>

                    </div>

                    </template>

                </div>
            </div>
        </div>

<script>
function workOrderApp(){
    return {

        showModal: false,
        openPreview: false,
        selected: {},
        previewUrl: '',

        // 🔥 DATA DARI BACKEND
        dataWOMasuk: @json($wo),

        // ================= AMBIL WO =================
        ambilWO(wo){

        Swal.fire({
            title: 'Ambil Work Order?',
            html:
                 `Work Order <b>${wo.nomor_tiket}</b> akan menjadi tanggung jawab Anda`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Ambil',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                // 🔥 LOADING
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/work-order/${wo.id}/ambil`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },
                    body: JSON.stringify({})
                })
                .then(async res => {
                    const text = await res.text();
                    let data = {};
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        console.error(text);
                        throw {
                            message:
                                'Terjadi error pada server'
                        };
                    }

                    if (!res.ok) {

                        throw data;
                    }

                    return data;
                    })
                .then(res => {

                    Swal.close();

                    // hapus dari tabel
                    this.dataWOMasuk = this.dataWOMasuk.filter(x => x.id !== wo.id);

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                })
                .catch(err => {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: err.message || 'Terjadi kesalahan'
                    });

                });

            }

        });
        },

        previewFile(file){
            this.previewUrl = file;
            this.openPreview = true;
        },

        // ================= MODAL =================
        openModal(wo){
            this.selected = {
                ...wo,
                tr: wo.tr ?? '-'
            };
            this.showModal = true;
        },

    }
}
</script>
@endsection