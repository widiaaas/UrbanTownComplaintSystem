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
                        <th class="px-4 py-3 text-center">
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

                                <td class="px-4 py-1 text-center">

                                    <div class="flex items-center justify-center">

                                        <button
                                            @click="
                                                selectedDetail = item;
                                                openDetail = true;
                                            "
                                            class="p-1 rounded-md
                                            hover:bg-sky-50 transition">

                                            @include('components.buttons.btn-view')

                                        </button>

                                    </div>

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
    {{-- ================= MODAL DETAIL RIWAYAT HUNIAN ================= --}}
    <div
        x-show="openDetail"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 bg-black/50
        backdrop-blur-sm
        flex items-center justify-center
        z-50 p-4">

        <div
            @click.outside="openDetail = false"
            x-transition.scale
            class="bg-white w-full max-w-2xl
            rounded-2xl shadow-2xl overflow-hidden">

            {{-- HEADER --}}
            <div
                class="flex items-center justify-between
                px-6 py-4 border-b border-gray-100">

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Detail Riwayat Hunian
                    </h2>

                    <p class="text-sm text-gray-500">
                        Informasi detail riwayat hunian penghuni
                    </p>

                </div>

                {{-- CLOSE --}}
                <button
                    @click="openDetail = false"
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

            {{-- CONTENT --}}
            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

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
                            x-text="selectedDetail.penghuni || '-'">
                        </div>

                    </div>

                    {{-- NIK --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            NIK

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selectedDetail.nik || '-'">
                        </div>

                    </div>

                    {{-- EMAIL --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Email

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800 break-all"
                            x-text="selectedDetail.email || '-'">
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
                            x-text="selectedDetail.no_telepon || '-'">
                        </div>

                    </div>

                    {{-- UNIT --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Unit

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selectedDetail.unit || '-'">
                        </div>

                    </div>

                    {{-- STATUS --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Status

                        </label>

                        <div class="mt-2">

                            <span
                                x-show="selectedDetail.status === 'Aktif'"
                                class="inline-flex items-center
                                px-3 py-1 rounded-full
                                text-xs font-medium
                                bg-green-100 text-green-700">

                                Aktif

                            </span>

                            <span
                                x-show="selectedDetail.status === 'Nonaktif'"
                                class="inline-flex items-center
                                px-3 py-1 rounded-full
                                text-xs font-medium
                                bg-red-100 text-red-700">

                                Nonaktif

                            </span>

                        </div>

                    </div>

                    {{-- TANGGAL MASUK --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Tanggal Masuk

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selectedDetail.tanggal_masuk || '-'">
                        </div>

                    </div>

                    {{-- TANGGAL KELUAR --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Tanggal Keluar

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selectedDetail.tanggal_keluar || '-'">
                        </div>

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div
                class="flex justify-end
                px-6 py-4 border-t
                border-gray-100 bg-gray-50">

                <button
                    @click="openDetail = false"
                    class="px-4 py-2 rounded-xl
                    bg-blue-600 text-white
                    text-sm font-medium
                    hover:bg-blue-700 transition">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>

<script>

function riwayatHunianApp() {

    return {

        search: '',

        statusFilter: '',
        openDetail: false,
        
        selectedDetail: {},
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