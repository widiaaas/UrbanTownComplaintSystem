@extends('layouts.app')

@section('title', 'Riwayat Work Order')

@section('content')

<div
    x-data="riwayatWOApp()"
    class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div>

        <h1 class="text-2xl font-bold text-gray-800">
            Riwayat Work Order
        </h1>

        <p class="text-sm text-gray-500">
            Riwayat seluruh work order departemen
        </p>

    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white p-4 rounded-xl shadow">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

            {{-- SEARCH --}}
            <div class="md:col-span-9">

                <input
                    type="text"
                    x-model="search"
                    placeholder="Cari nomor WO, unit, penghuni..."
                    class="w-full border rounded-lg px-3 py-2">

            </div>

            {{-- STATUS --}}
            <div class="md:col-span-3">

                <select
                    x-model="statusFilter"
                    class="w-full border rounded-lg px-3 py-2 bg-white">
                    <option value=""> Semua Status</option>
                    <option value="unassigned"> Unassigned</option>
                    <option value="open"> Open</option>
                    <option value="on_progress">On Progress</option>
                    <option value="waiting"> Waiting</option>
                    <option value="close">Close</option>
                </select>
            </div>
        </div>

    </div>

    {{-- ================= TOTAL DATA ================= --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Total data:
            <span
                class="font-semibold text-gray-700"
                x-text="filteredData.length">
            </span>

        </p>

        <template x-if="search">
            <p class="text-sm text-gray-400">
                Hasil pencarian untuk:
                <span
                    class="font-medium"
                    x-text="search">
                </span>
            </p>
        </template>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-center"> No</th>
                        <th class="px-4 py-3 text-center">Nomor WO</th>
                        <th class="px-4 py-3 text-center">Unit</th>
                        <th class="px-4 py-3 text-center"> Penghuni</th>
                        <th class="px-4 py-3 text-center">Penanggung Jawab</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Tanggal </th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        x-if="filteredData.length">
                        <template
                            x-for="(item, index) in filteredData"
                            :key="item.id">
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3 text-center"x-text="index + 1"></td>
                                <td class="px-4 py-3 text-center font-medium" x-text="item.nomor_tiket"></td>
                                <td class="px-4 py-3 text-center" x-text="item.unit"></td>
                                <td class="px-4 py-3 text-center" x-text="item.penghuni"></td>
                                <td class="px-4 py-3 text-center" x-text="item.petugas"></td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full"
                                        :class="statusClass(item.status)"
                                        x-text="formatStatus(item.status)">
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center"x-text="item.tanggal"> </td>
                                <td class="px-4 py-3 text-center">
                                    <a
                                        :href="`detailWorkOrder/${item.id}`"
                                        class="px-3 py-1.5 rounded-lg bg-blue-500 text-white text-xs hover:bg-blue-600">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </template>

                    {{-- EMPTY --}}
                    <template
                        x-if="!filteredData.length">
                        <tr>
                            <td
                                colspan="8"
                                class="px-4 py-6 text-center text-gray-400 italic">
                                Data tidak ada
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

function riwayatWOApp() {

    return {
        search: '',
        statusFilter: '',
        data: @json($wo),
        get filteredData() {
            return this.data.filter(item => {
                const keyword =this.search.toLowerCase();
                const matchSearch =!this.search ||
                    (item.nomor_tiket || '')
                        .toLowerCase()
                        .includes(keyword)
                    ||
                    (item.unit || '')
                        .toLowerCase()
                        .includes(keyword)
                    ||
                    (item.penghuni || '')
                        .toLowerCase()
                        .includes(keyword);

                const matchStatus =!this.statusFilter || item.status === this.statusFilter;
                    return matchSearch && matchStatus;
            });
        },

        formatStatus(status) {
            const s = (status || '').toLowerCase();
                if (s === 'Unassigned') {
                    return 'unassigned';
                }
                if (s === 'open') {
                    return 'Open';
                }

                if (s === 'on_progress') {
                    return 'On Progress';
                }

                if (s === 'waiting') {
                    return 'Waiting';
                }

                if (s === 'close') {
                    return 'Close';
                }

                return status;
        },

        statusClass(status) {
            const s =
                (status || '').toLowerCase();
            return {
                'bg-gray-100 text-gray-700': s === 'unassigned',
                'bg-blue-100 text-blue-700': s === 'open',
                'bg-yellow-100 text-yellow-700': s === 'on_progress',
                'bg-orange-100 text-orange-700': s === 'waiting',
                'bg-green-100 text-green-700': s === 'close',
            };
        }
    }
}
</script>

@endsection