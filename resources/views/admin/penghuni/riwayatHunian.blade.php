@extends('layouts.app')

@section('title', 'Riwayat Hunian')

@section('content')

<div
    x-data="riwayatHunianApp()"
    class="p-6 space-y-6">

    {{-- HEADER --}}
    <div>

        <h1 class="text-2xl font-bold text-gray-900">
            Riwayat Hunian
        </h1>

        <p class="text-sm text-gray-500">
            Riwayat perpindahan dan hunian penghuni apartemen
        </p>

    </div>

    {{-- FILTER --}}
    <div
        class="bg-white rounded-xl shadow p-4
        flex flex-col md:flex-row gap-4">

        {{-- SEARCH --}}
        <div class="flex-1">

            <label class="text-sm font-medium text-gray-700">
                Cari Penghuni / Unit
            </label>

            <input
                type="text"
                x-model="search"
                placeholder="Cari nama penghuni atau nomor unit..."
                class="w-full mt-1 border rounded-lg px-3 py-2
                focus:ring focus:ring-blue-200">

        </div>

        {{-- STATUS --}}
        <div class="md:w-56">

            <label class="text-sm font-medium text-gray-700">
                Status
            </label>

            <select
                x-model="statusFilter"
                class="w-full mt-1 border rounded-lg px-3 py-2 bg-white">

                <option value="">
                    Semua
                </option>

                <option value="Aktif">
                    Aktif
                </option>

                <option value="Nonaktif">
                    Nonaktif
                </option>

            </select>

        </div>

    </div>

    {{-- TOTAL --}}
    <p class="text-sm text-gray-500">

        Total data:
        <span
            class="font-semibold"
            x-text="filteredData.length">
        </span>

    </p>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">

                    <tr>

                        <th class="px-4 py-3 text-center">
                            No
                        </th>

                        <th class="px-4 py-3 text-center">
                            Nama Penghuni
                        </th>

                        <th class="px-4 py-3 text-center">
                            Unit
                        </th>

                        <th class="px-4 py-3 text-center">
                            Tanggal Masuk
                        </th>

                        <th class="px-4 py-3 text-center">
                            Tanggal Keluar
                        </th>

                        <th class="px-4 py-3 text-center">
                            Status
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

                            <tr
                                class="border-t hover:bg-gray-50">

                                <td
                                    class="px-4 py-3 text-center"
                                    x-text="index + 1">
                                </td>

                                <td
                                    class="px-4 py-3 text-center"
                                    x-text="item.penghuni">
                                </td>

                                <td
                                    class="px-4 py-3 text-center"
                                    x-text="item.unit">
                                </td>

                                <td
                                    class="px-4 py-3 text-center"
                                    x-text="item.tanggal_masuk">
                                </td>

                                <td
                                    class="px-4 py-3 text-center"
                                    x-text="item.tanggal_keluar">
                                </td>

                                <td class="px-4 py-3 text-center">

                                    <span
                                        class="px-2 py-1 rounded-full text-xs"
                                        :class="
                                            item.status === 'Aktif'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-red-100 text-red-700'
                                        "
                                        x-text="item.status">
                                    </span>

                                </td>

                            </tr>

                        </template>

                    </template>

                    {{-- EMPTY --}}
                    <template
                        x-if="!filteredData.length">

                        <tr>

                            <td
                                colspan="7"
                                class="px-4 py-4 text-center
                                text-gray-400 italic">

                                Data riwayat hunian tidak tersedia

                            </td>

                        </tr>

                    </template>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

function riwayatHunianApp() {

    return {

        search: '',

        statusFilter: '',

        data: @json($riwayat),

        get filteredData() {

            return this.data.filter(item => {

                const keyword =
                    this.search.toLowerCase();

                const matchSearch =

                    (item.penghuni || '')
                        .toLowerCase()
                        .includes(keyword)

                    ||

                    (item.unit || '')
                        .toLowerCase()
                        .includes(keyword);

                const matchStatus =

                    !this.statusFilter ||

                    item.status === this.statusFilter;

                return matchSearch && matchStatus;
            });
        }
    }
}

</script>

@endsection