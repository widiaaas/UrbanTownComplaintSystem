@extends('layouts.app')

@section('title', 'Riwayat Unit')

@section('content')

<div
    x-data="riwayatUnitApp()"
    class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                Riwayat Unit
            </h1>

            <p class="text-sm text-gray-500">
                Riwayat seluruh keluhan unit apartemen
            </p>

        </div>

    </div>

    {{-- ================= SEARCH ================= --}}
    <div class="bg-white p-4 rounded-xl shadow">

        <input
            type="text"
            x-model="search"
            placeholder="Cari nomor unit, penghuni, atau judul keluhan..."
            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-100">

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">

                    <tr>

                        <th class="px-4 py-3 text-center w-14">
                            No
                        </th>

                        <th class="px-4 py-3 text-center">
                            Nomor Tiket
                        </th>

                        <th class="px-4 py-3 text-center">
                            Unit
                        </th>

                        <th class="px-4 py-3 text-center">
                            Penghuni
                        </th>

                        <th class="px-4 py-3 text-center">
                            Judul Keluhan
                        </th>

                        <th class="px-4 py-3 text-center">
                            Status
                        </th>

                        <th class="px-4 py-3 text-center">
                            Tanggal
                        </th>

                        <th class="px-4 py-3 text-center">
                            TR
                        </th>

                        <th class="px-4 py-3 text-center w-28">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    {{-- DATA --}}
                    <template
                        x-if="filteredData.length">

                        <template
                            x-for="(item, index) in filteredData"
                            :key="item.id">

                            <tr class="border-t hover:bg-gray-50">

                                {{-- NO --}}
                                <td
                                    class="px-4 py-3"
                                    x-text="index + 1">
                                </td>

                                {{-- NOMOR TIKET --}}
                                <td
                                    class="px-4 py-3 font-medium text-gray-800"
                                    x-text="item.nomor_tiket">
                                </td>

                                {{-- UNIT --}}
                                <td
                                    class="px-4 py-3"
                                    x-text="item.unit">
                                </td>

                                {{-- PENGHUNI --}}
                                <td
                                    class="px-4 py-3"
                                    x-text="item.penghuni">
                                </td>

                                {{-- JUDUL --}}
                                <td
                                    class="px-4 py-3"
                                    x-text="item.judul">
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-3">

                                    <span
                                        class="px-2 py-1 text-xs rounded-full"
                                        :class="statusClass(item.status)"
                                        x-text="formatStatus(item.status)">
                                    </span>

                                </td>

                                {{-- TANGGAL --}}
                                <td
                                    class="px-4 py-3"
                                    x-text="item.tanggal">
                                </td>
                            
                                <td class="px-4 py-3"
                                    x-text="item.tr">
                                </td>

                                {{-- AKSI --}}
                                <td class="px-4 py-3 text-center">

                                    <a
                                        :href="`/keluhan/${item.id}`"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600 text-xs">

                                        Detail

                                    </a>

                                </td>

                            </tr>

                        </template>

                    </template>

                    {{-- DATA KOSONG --}}
                    <template
                        x-if="!filteredData.length">

                        <tr>

                            <td
                                colspan="8"
                                class="px-4 py-4 text-center text-gray-400 italic">

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

function riwayatUnitApp() {

    return {

        search: '',

        data: @json($keluhan),
        
        get filteredData() {

            if (!this.search) {

                return this.data;
            }

            const keyword =
                this.search.toLowerCase();

            return this.data.filter(item =>

                (item.unit || '')
                    .toLowerCase()
                    .includes(keyword)

                ||

                (item.penghuni || '')
                    .toLowerCase()
                    .includes(keyword)

                ||

                (item.judul || '')
                    .toLowerCase()
                    .includes(keyword)
                    
            );
        },

        formatStatus(status) {

            const s =
                (status || '')
                    .toLowerCase();

            if (s === 'unassigned') {
                return 'Unassigned';
            }

            if (s === 'open') {
                return 'Open';
            }

            if (s === 'on_progress') {
                return 'On Progress';
            }

            if (s === 'close') {
                return 'Close';
            }

            return status;
        },

        statusClass(status) {

            const s =
                (status || '')
                    .toLowerCase();

            return {

                'bg-gray-100 text-gray-700':
                    s === 'unassigned',

                'bg-blue-100 text-blue-700':
                    s === 'open',

                'bg-yellow-100 text-yellow-700':
                    s === 'on_progress',

                'bg-green-100 text-green-700':
                    s === 'close'
            };
        }
    }
}
</script>

@endsection