@extends('layouts.app')

@section('title', 'Riwayat Keluhan')

@section('content')


<div x-data="keluhanApp()" x-init="init()" class="p-6 space-y-6">

    {{-- HEADER --}}
    <h1 class="text-2xl font-bold text-gray-900">Riwayat Keluhan</h1>

    {{-- FILTER --}}
    <div class="bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

            <div class="md:col-span-6">
                <label class="text-sm font-medium">Cari Keluhan</label>
                <input type="text"
                    x-model="search"
                    placeholder="Ticket / Judul"
                    class="w-full border rounded-lg px-3 py-2">
            </div>

            <div class="md:col-span-3">
                <label class="text-sm font-medium">Status</label>
                <select x-model="filterStatus"
                    class="w-full border rounded-lg px-3 py-2">
                    <option value="">Semua</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <div class="md:col-span-3 flex gap-2">
                <button @click="applyFilter()"
                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded">
                    Filter
                </button>
                <button @click="resetFilter()"
                    class="flex-1 border px-4 py-2 rounded">
                    Reset
                </button>
            </div>

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
                    <p class="font-medium" x-text="k.title"></p>
                    <p class="text-sm text-gray-600" x-text="k.description"></p>
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
    <div x-show="openModal" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white max-w-2xl w-full rounded-lg p-6 space-y-4"
            @click.outside="closeModal()">

            <div class="flex justify-between border-b pb-2">
                <div>
                    <h2 class="font-semibold text-lg" x-text="selected.title"></h2>
                    <p class="text-xs text-gray-500">Ticket <span x-text="selected.ticket"></span></p>
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
                    <p x-text="selected.date"></p>
                </div>
            </div>

            {{-- 🔥 PENGAJUAN (PENGHUNI) --}}
            <div>
                <p class="font-medium text-sm mb-1">Pengajuan Keluhan</p>

                <div class="bg-gray-100 rounded p-3 text-sm space-y-2">
                    <p x-text="selected.pengajuan?.deskripsi"></p>
                    <p class="text-xs text-gray-400" x-text="selected.pengajuan?.tanggal"></p>

                    {{-- 🔥 LAMPIRAN PENGHUNI --}}
                    <template x-if="selected.pengajuan?.lampiran && selected.pengajuan.lampiran.length">
                        <div class="mt-2">

                        <div class="flex flex-wrap gap-2">
                            <template x-for="(file, i) in selected.pengajuan.lampiran" :key="i">
                                <button 
                                    @click="previewFile = '/storage/' + file; previewOpen = true"
                                    class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:underline">
                                    <span x-text="'📎 Lampiran ' + (i+1)"></span>
                                </button>
                            </template>
                        </div>

                        </div>
                    </template>

                    <template x-if="!selected.pengajuan?.lampiran || selected.pengajuan.lampiran.length === 0">
                        <p class="text-xs text-gray-400 italic">Tidak ada lampiran</p>
                    </template>
                </div>
            </div>


            {{-- Keputusan --}}
            <div>
                <p class="font-medium text-sm mb-1">Keputusan</p>

                <template x-if="selected.keputusan && selected.keputusan.length">
                    <div class="space-y-2">
                        <template x-for="(s,i) in selected.keputusan" :key="i">
                        <div class="border-l-4 border-green-500 pl-3 space-y-1">
                            <p class="text-sm" x-text="s.isi"></p>
                            <p class="text-xs text-gray-400" x-text="s.tanggal"></p>

                            {{-- 🔥 LAMPIRAN --}}
                            <template x-if="s.lampiran">
                                <div class="mt-1">

                                    {{-- kalau array --}}
                                    <template x-if="Array.isArray(s.lampiran)">
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="(file, i) in s.lampiran" :key="i">
                                                <a :href="'/storage/' + file"
                                                target="_blank"
                                                class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:underline">
                                                    📎 Lampiran
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    {{-- kalau string --}}
                                    <template x-if="!Array.isArray(s.lampiran)">
                                        <a :href="'/storage/' + s.lampiran"
                                        target="_blank"
                                        class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:underline inline-block">
                                            📎 Lihat Lampiran
                                        </a>
                                    </template>

                                </div>
                            </template>

                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!selected.keputusan || selected.keputusan.length === 0">
                    <p class="text-sm italic text-gray-400">Belum ada keputusan</p>
                </template>
            </div>

        </div>
    </div>

    <!-- ================= MODAL PREVIEW FILE ================= -->
    <div x-show="previewOpen" x-cloak
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-[9999]">

        <div class="bg-white w-full max-w-3xl rounded-lg p-4 relative">

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
                    <img :src="previewFile" class="max-h-[70vh] mx-auto rounded">
                </template>

                <!-- PDF -->
                <template x-if="previewFile.match(/\.pdf$/i)">
                    <iframe :src="previewFile" class="w-full h-[70vh]"></iframe>
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
            keputusan: []
        },
        previewFile: '',
        previewOpen: false, 

        init() {
            const allowedStatus = ['open', 'on progress', 'close'];

            this.filteredKeluhan = this.keluhan.filter(k =>
                allowedStatus.includes((k.status || '').toLowerCase())
            );
        },

        applyFilter() {
            const allowedStatus = ['open', 'on progress', 'close'];

            this.filteredKeluhan = this.keluhan.filter(k =>
                allowedStatus.includes((k.status || '').toLowerCase()) &&

                (this.search === '' ||
                    k.ticket.toLowerCase().includes(this.search.toLowerCase()) ||
                    k.title.toLowerCase().includes(this.search.toLowerCase())
                ) &&
                (this.filterStatus === '' || k.status === this.filterStatus)
            );
        },

        resetFilter() {
            this.search = '';
            this.filterStatus = '';
            this.filteredKeluhan = this.keluhan;
        },

        openDetail(k) {
            this.selected = {
                ...k,
                keputusan: k.keputusan ?? []
            };
            this.openModal = true;
        },

        closeModal() {
            this.openModal = false;
        },

        badgeHtml(status) {
            const map = {
                'open': 'bg-blue-100 text-blue-800',
                'on progress': 'bg-yellow-100 text-yellow-800',
                'close': 'bg-green-100 text-green-800'
            };

            return `<span class="px-2 py-1 text-xs rounded ${map[status?.toLowerCase()] || 'bg-gray-100'}">${status}</span>`;
        }
    }
}
</script>

@endsection