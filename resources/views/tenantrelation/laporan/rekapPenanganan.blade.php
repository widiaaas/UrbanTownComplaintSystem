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

            <div>
                <label class="text-sm font-medium">Tanggal Awal</label>
                <input type="date" x-model="filter.tglAwal"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Akhir</label>
                <input type="date" x-model="filter.tglAkhir"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium">Departemen</label>
                <select x-model="filter.departemen"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                    <option value="Semua">Semua</option>
                    <option value="IT">IT</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Umum">Umum</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Status</label>
                <select x-model="filter.status"
                    class="w-full mt-1 border rounded-lg px-3 py-2 text-sm">
                    <option value="Semua">Semua</option>
                    <option value="Open">Open</option>
                    <option value="On Progress">On Progress</option>
                    <option value="Close">Close</option>
                </select>
            </div>
        </div>

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
                                <td class="px-5 py-3" x-text="k.tanggal"></td>
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

        dataRekap: [
            {
                id: 1,
                tiket: 'TCK-001',
                tanggal: '12 Feb 2026',
                nama: 'Budi Santoso',
                departemen: 'Maintenance',
                status: 'Open'
            },
            {
                id: 2,
                tiket: 'TCK-002',
                tanggal: '13 Feb 2026',
                nama: 'Siti Aminah',
                departemen: 'Umum',
                status: 'On Progress'
            },
            {
                id: 3,
                tiket: 'TCK-003',
                tanggal: '14 Feb 2026',
                nama: 'Ahmad Rizki',
                departemen: 'IT',
                status: 'Close'
            }
        ],

        cetak() {
            window.print();
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