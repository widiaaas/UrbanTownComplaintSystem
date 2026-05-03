@extends('layouts.app')

@section('title', 'Rekap Penanganan Keluhan')

@section('content')
<div x-data="rekapPenangananApp()" class="p-6 space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Rekap Penanganan Keluhan</h1>
        <p class="text-sm text-gray-500">
            Rekap keluhan berdasarkan periode, departemen, dan status
        </p>
    </div>

    {{-- FILTER --}}
    <div class="bg-white p-5 rounded-xl shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- TANGGAL AWAL --}}
            <div>
                <label class="text-sm font-medium">Tanggal Awal</label>
                <input type="date"
                    x-model="filter.tglAwal"
                    :max="today"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            {{-- TANGGAL AKHIR --}}
            <div>
                <label class="text-sm font-medium">Tanggal Akhir</label>
                <input type="date"
                    x-model="filter.tglAkhir"
                    :max="today"
                    :min="filter.tglAwal"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            {{-- DEPARTEMEN --}}
            <div>
                <label class="text-sm font-medium">Departemen</label>
                <select x-model="filter.departemen"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">

                    <option value="Semua">Semua</option>

                    <template x-for="dept in departemenList" :key="dept">
                        <option :value="dept" x-text="dept"></option>
                    </template>
                </select>
            </div>

            {{-- STATUS --}}
            <div>
                <label class="text-sm font-medium">Status</label>
                    <select x-model="filter.status"
                        class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">

                        <option value="Semua">Semua</option>

                        <template x-for="s in statusList" :key="s">
                            <option :value="s" x-text="s"></option>
                        </template>
                    </select>
            </div>
        </div>

        {{-- ACTION --}}
        <div class="flex justify-end mt-4 gap-2">
            <button
                @click="preview = true"
                class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Preview
            </button>
            <button
                @click="cetak()"
                class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                Cetak
            </button>
        </div>
    </div>

    {{-- TABEL PREVIEW --}}
    <template x-if="preview">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="max-h-[420px] overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="px-5 py-3 text-left">No Tiket</th>
                            <th class="px-5 py-3 text-left">Tanggal</th>
                            <th class="px-5 py-3 text-left">Penghuni</th>
                            <th class="px-5 py-3 text-left">Departemen</th>
                            <th class="px-5 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="k in dataRekap" :key="k.id">
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium" x-text="k.tiket"></td>

                                {{-- FORMAT TANGGAL DI SINI --}}
                                <td class="px-5 py-3" x-text="formatTanggal(k.tanggal)"></td>

                                <td class="px-5 py-3" x-text="k.nama"></td>
                                <td class="px-5 py-3" x-text="k.departemen"></td>
                                <td class="px-5 py-3">
                                    <span class="px-3 py-1 rounded-full text-xs"
                                        :class="statusClass(k.status)"
                                        x-text="k.status">
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </template>

</div>

<script>
function rekapPenangananApp() {
    return {
        preview: false,

        filter: {
            tglAwal: '',
            tglAkhir: '',
            departemen: 'Semua',
            status: 'Semua'
        },

        // 🔥 BLOCK TANGGAL > HARI INI
        today: new Date().toLocaleDateString('en-CA'),

        departemenList: [],
        statusList: [],

        init() {
            this.loadDepartemen();
            this.loadStatus();
        },

        async loadDepartemen() {
            const res = await fetch('/rekap-penanganan/departemen');
            this.departemenList = await res.json();
        },

        async loadStatus() {
            const res = await fetch('/rekap-penanganan/status');
            this.statusList = await res.json();
        },

        cetak() {
            const params = new URLSearchParams(this.filter).toString();
            window.open(`/rekap-penanganan/pdf?${params}`, '_blank');
        },

        // 🔥 FORMAT DD/MM/YY
        formatTanggal(date) {
            if (!date) return '';

            const d = new Date(date);

            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = String(d.getFullYear()).slice(-2);

            return `${day}/${month}/${year}`;
        },

        statusClass(status) {
            return {
                'Open': 'bg-blue-100 text-blue-700',
                'On Progress': 'bg-yellow-100 text-yellow-700',
                'Close': 'bg-green-100 text-green-700'
            }[status] || 'bg-gray-100 text-gray-700';
        }
    }
}
</script>
@endsection