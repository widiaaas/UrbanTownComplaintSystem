@extends('layouts.app')

@section('judul', 'Riwayat Keluhan')

@section('content')


<div x-data="keluhanApp()" x-init="init()" class="p-6 space-y-6">

    {{-- HEADER --}}
    <h1 class="text-2xl font-bold text-gray-900">Riwayat Keluhan</h1>

    {{-- FILTER --}}
    <div class="bg-white rounded-xl shadow p-4 flex flex-col md:flex-row gap-4">

        {{-- SEARCH --}}
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari Keluhan</label>
            <input 
                type="text"
                x-model="search"
                placeholder="Ticket / Judul"
                class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
        </div>

        {{-- STATUS --}}
        <div class="w-full md:w-48">
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select x-model="filterStatus"
                class="w-full mt-1 border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                <option value="">Semua</option>
                <option value="unassigned">Unassigned</option>
                <option value="open">Open</option>
                <option value="on_progress">On Progress</option>
                <option value="close">Close</option>
            </select>
        </div>

        {{-- RESET --}}
        <div class="flex items-end">
            <button 
                @click="resetFilter()"
                class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                Reset
            </button>
        </div>

    </div>

    {{-- LIST --}}
    <div class="bg-white rounded-lg border divide-y">
        <template x-for="k in filteredKeluhan" :key="k.id">
            <div class="p-5 flex justify-between items-center hover:bg-gray-50">
                <div>
                    <div class="flex gap-2 items-center">
                        <b x-text="k.ticket"></b>
                        <span x-html="badgeHtml(k.status)"></span>
                    </div>
                    <p class="font-medium" x-text="k.judul"></p>
                    <p class="text-sm text-gray-600" x-text="k.deskripsi"></p>
                </div>

                <button @click="openDetail(k)"
                    class="bg-blue-500 text-white px-4 py-2 rounded">
                    Detail
                </button>
            </div>
        </template>

        <template x-if="filteredKeluhan.length === 0">
            <p class="p-6 text-center text-gray-500">Tidak ada data</p>
        </template>
    </div>

    {{-- MODAL DETAIL --}}
    <div
        x-show="openModal"
        x-cloak
        @click="closeModal()"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
       
        <div class="bg-white max-w-2xl w-full rounded-lg p-6 space-y-4"
            @click.stop>
            

            <div class="flex justify-between border-b pb-2">
                <div>
                    <h2 class="font-semibold text-lg" x-text="selected.judul"></h2>
                    <p class="text-xs text-gray-500">Nomor Tiket <span x-text="selected.nomor_tiket"></span></p>
                </div>
                <button @click="closeModal()">✕</button>
            </div>

            <div class="grid grid-cols-2 text-sm gap-4">
                <div>
                    <p class="font-medium">Status</p>
                    <span x-html="badgeHtml(selected.status)"></span>
                </div>
                <div>
                    <p class="font-medium">Tanggal</p>
                    <p x-text="selected.tanggal"></p>
                </div>
            </div>

            {{-- 🔥 PENGAJUAN (PENGHUNI) --}}
            <div>
                <p class="font-medium text-sm mb-1">Pengajuan Keluhan</p>

                <div class="bg-gray-100 rounded p-3 text-sm space-y-2">
                <p x-text="selected.deskripsi"></p>
                    <p class="text-xs text-gray-400" x-text="selected.tanggal"></p>

                    {{-- 🔥 LAMPIRAN PENGHUNI --}}
                    <template x-if="selected.lampiran_pengajuan && selected.lampiran_pengajuan.length">
                        <div class="mt-2">

                            <div class="flex flex-wrap gap-2">
                                <template x-for="(file, i) in selected.lampiran_pengajuan" :key="i">
                                    <button 
                                        @click="previewFile = '/storage/' + file; previewOpen = true"
                                        class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:underline">
                                        <span x-text="'📎 Lampiran ' + (i+1)"></span>
                                    </button>
                                </template>
                            </div>

                        </div>
                    </template>

                    <template x-if="!selected.lampiran_pengajuan || selected.lampiran.length === 0">
                        <p class="text-xs text-gray-400 italic">Tidak ada lampiran</p>
                    </template>
                </div>
            </div>


            {{-- riwayat --}}
            <div>
                <p class="font-medium text-sm mb-1">Keputusan</p>

                <template x-if="selected.keputusan">
                    <div class="border-l-4 border-green-500 pl-3 space-y-1">
                        <p class="text-sm" x-text="selected.keputusan"></p>
                        <p class="text-xs text-gray-400"
                        x-text="selected.tanggal_keputusan_format ?? '-'"></p>
                    </div>
                </template>

                {{-- 🔥 LAMPIRAN KEPUTUSAN --}}
                <template x-if="selected.lampiran_keputusan && selected.lampiran_keputusan.length">
                    <div class="mt-2 flex flex-wrap gap-2">

                        <template x-for="(file, i) in selected.lampiran_keputusan" :key="i">
                            <button
                                @click="previewFile = '/storage/' + file; previewOpen = true"
                                class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:underline">

                                <span x-text="'📎 Lampiran Keputusan ' + (i + 1)"></span>

                            </button>
                        </template>

                    </div>
                </template>

                <template x-if="!selected.keputusan">
                    <p class="text-sm italic text-gray-400">Belum ada keputusan</p>
                </template>
            </div>

        </div>
    </div>

    <!-- ================= MODAL PREVIEW FILE ================= -->
    <div
        x-show="previewOpen"
        x-cloak
        class="fixed inset-0
        bg-black/70
        flex items-center justify-center
        p-4
        z-[9999]">

        <div
            class="bg-white
            w-full max-w-5xl
            rounded-3xl
            shadow-2xl
            p-6
            relative">

            <!-- CLOSE -->
            <button 
                @click="previewOpen=false"
                class="absolute top-2 right-2 text-xl">
                ✕
            </button>

            <!-- CONTENT -->
            <div class="mt-6">

            
                <!-- IMAGE -->
                <template x-if="previewFile.match(/\.(jpg|jpeg|png|gif)$/i)">

                <div
                    class="flex justify-center items-center
                    bg-gray-50 rounded-3xl
                    h-[80vh]">

                    <img
                        :src="previewFile"
                        class="max-w-full max-h-full
                        object-contain rounded-2xl">

                </div>

                </template>

                <!-- PDF -->
                <template x-if="previewFile.match(/\.pdf$/i)">

                    <div
                        class="flex items-center justify-between
                        bg-gray-50 border border-gray-200
                        rounded-xl px-4 py-4">

                        <div class="flex items-center gap-3">

                            <div
                                class="w-12 h-12 rounded-lg
                                bg-red-100 text-red-600
                                flex items-center justify-center">

                                📄

                            </div>

                            <div>

                                <p class="font-medium text-gray-800">
                                    File PDF
                                </p>

                                <p
                                    class="text-sm text-gray-500"
                                    x-text="previewFile.split('/').pop()">
                                </p>

                            </div>

                        </div>

                        <a
                            :href="previewFile"
                            target="_blank"
                            class="px-4 py-2
                            bg-blue-600 text-white
                            rounded-lg hover:bg-blue-700">

                            Buka PDF

                        </a>

                    </div>

                </template>

                <!-- FILE LAIN -->
                <template x-if="!previewFile.match(/\.(jpg|jpeg|png|gif|pdf)$/i)">
                    <div class="text-center">
                        <p class="mb-2">Preview tidak tersedia</p>
                        <a :href="previewFile" target="_blank"
                            class="text-blue-600 underline">
                            Download File
                        </a>
                    </div>
                </template>

            </div>
        </div>
    </div>
</div>

<script>
function keluhanApp() {
    return {
        search: '',
        filterStatus: '',
        keluhan: @json($keluhan),
        filteredKeluhan: [],
        openModal: false,
        selected: {
            riwayat: []
        },
        previewFile: '',
        previewOpen: false, 

        init() {
            this.filteredKeluhan = this.keluhan;
            this.$watch('search', () => this.applyFilter());
    this.$watch('filterStatus', () => this.applyFilter());
        },

        applyFilter() {
            const search = this.search.toLowerCase();

            this.filteredKeluhan = this.keluhan.filter(k => {

                const status = (k.status || '').toLowerCase();

                const matchSearch =
                    !search ||
                    k.ticket.toLowerCase().includes(search) ||
                    k.judul.toLowerCase().includes(search);

                const matchStatus =
                    !this.filterStatus ||
                    status === this.filterStatus;

                return matchSearch && matchStatus;
            });
        },

        resetFilter() {
            this.search = '';
            this.filterStatus = '';
            this.filteredKeluhan = this.keluhan;
        },

        openDetail(k) {
            this.selected = k;
            this.openModal = true;
        },

        closeModal() {
            this.openModal = false;
        },

        badgeHtml(status) {
            const s = (status || '').toLowerCase().replace(' ', '_');

            const map = {
                'unassigned': 'bg-gray-100 text-gray-700 border border-gray-300',
                'open': 'bg-blue-100 text-blue-700 border border-blue-200',
                'on_progress': 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                'close': 'bg-green-100 text-green-700 border border-green-200'
            };

            const labelMap = {
                'unassigned': 'Unassigned',
                'open': 'Open',
                'on_progress': 'On Progress',
                'close': 'Close'
            };

            return `
                <span class="px-2 py-1 text-xs font-medium rounded-full ${map[s] || 'bg-gray-100'}">
                    ${labelMap[s] || s}
                </span>
            `;
        }
    }
}
</script>

@endsection