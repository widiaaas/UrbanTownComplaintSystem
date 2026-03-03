@extends('layouts.app')

@section('title', 'Riwayat Keluhan')

@section('content')

@php
$keluhan = [
    [
        'id' => 1,
        'ticket' => 'CMP-001',
        'title' => 'Perbaikan AC',
        'description' => 'AC tidak dingin.',
        'status' => 'diproses',
        'date' => '2026-02-10',
        'solusi' => [
            [
                'isi' => 'AC telah dicek dan dilakukan pengisian freon.',
                'tanggal' => '10 Feb 2026 11:00'
            ],
        ],
        'konfirmasi' => null
    ],
    [
        'id' => 2,
        'ticket' => 'CMP-002',
        'title' => 'Ganti Lampu',
        'description' => 'Lampu kamar mandi mati.',
        'status' => 'diproses',
        'date' => '2026-02-09',
        'solusi' => [
            [
                'isi' => 'Lampu kamar mandi telah diganti dan berfungsi normal.',
                'tanggal' => '09 Feb 2026 14:20'
            ],
        ],
        'konfirmasi' => null
    ],
];
@endphp

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

            <div>
                <p class="font-medium">Deskripsi</p>
                <div class="bg-gray-100 rounded p-3 text-sm"
                    x-text="selected.description"></div>
            </div>

            {{-- SOLUSI --}}
            <div>
                <p class="font-medium text-sm mb-1">Solusi</p>

                <template x-if="selected.solusi.length">
                    <div class="space-y-2">
                        <template x-for="(s,i) in selected.solusi" :key="i">
                            <div class="border-l-4 border-green-500 pl-3">
                                <p class="text-sm" x-text="s.isi"></p>
                                <p class="text-xs text-gray-400" x-text="s.tanggal"></p>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!selected.solusi.length">
                    <p class="text-sm italic text-gray-400">Belum ada solusi</p>
                </template>
            </div>

            {{-- KONFIRMASI --}}
            <template
                x-if="
                    selected.status === 'diproses' &&
                    selected.solusi.length > 0 &&
                    !selected.konfirmasi
                "
            >
                <div class="border-t pt-3">
                    <p class="text-sm mb-2">Apakah solusi sudah sesuai?</p>
                    <div class="flex gap-2">
                        <button @click="konfirmasiPuas()"
                            class="bg-green-600 text-white px-4 py-2 rounded">
                            Puas
                        </button>
                        <button @click="openTidakPuasModal()"
                            class="bg-red-600 text-white px-4 py-2 rounded">
                            Tidak Puas
                        </button>
                    </div>
                </div>
            </template>

            <div class="text-right">
                <button @click="closeModal()" class="border px-4 py-2 rounded">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    {{-- MODAL TIDAK PUAS --}}
    <div x-show="openTidakPuas" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

        <div class="bg-white max-w-md w-full rounded-lg p-6 space-y-4">
            <h3 class="font-semibold">Keluhan Belum Selesai</h3>

            <textarea x-model="alasanTidakPuas"
                class="w-full border rounded p-2"
                rows="4"
                placeholder="Tuliskan alasan..."></textarea>

            <div class="flex justify-end gap-2">
                <button @click="openTidakPuas=false" class="border px-4 py-2 rounded">
                    Batal
                </button>
                <button @click="kirimTidakPuas()"
                    class="bg-red-600 text-white px-4 py-2 rounded">
                    Kirim
                </button>
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
        openTidakPuas: false,
        alasanTidakPuas: '',
        selected: {},

        init() {
            this.filteredKeluhan = this.keluhan;
        },

        applyFilter() {
            this.filteredKeluhan = this.keluhan.filter(k =>
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
            this.selected = k;
            this.openModal = true;
        },

        closeModal() {
            this.openModal = false;
        },

        konfirmasiPuas() {
            this.selected.konfirmasi = {
                status: 'Puas',
                waktu: new Date().toLocaleString()
            };
        },

        openTidakPuasModal() {
            this.openTidakPuas = true;
        },

        kirimTidakPuas() {
            if (!this.alasanTidakPuas) return;

            this.selected.status = 'diproses';
            this.selected.konfirmasi = {
                status: 'Belum Puas',
                alasan: this.alasanTidakPuas,
                waktu: new Date().toLocaleString()
            };

            this.alasanTidakPuas = '';
            this.openTidakPuas = false;
            this.openModal = false;
        },

        badgeHtml(status) {
            const map = {
                diproses: 'bg-yellow-100 text-yellow-800',
                selesai: 'bg-green-100 text-green-800'
            };
            return `<span class="px-2 py-1 text-xs rounded ${map[status]}">${status}</span>`;
        }
    }
}
</script>

@endsection