@extends('layouts.app')

@section('title', 'Keluhan Masuk')

@section('content')
<div x-data="keluhanApp()" class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Keluhan Masuk</h1>
        <p class="text-sm text-gray-500">TR dapat mengambil keluhan untuk menjadi tanggung jawabnya</p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-5 py-3 text-left">Unit</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Penghuni</th>
                    <th class="px-5 py-3 text-left">Keluhan</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="k in dataKeluhanMasuk" :key="k.id">
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium" x-text="k.unit"></td>
                        <td class="px-5 py-3" x-text="k.tanggal"></td>
                        <td class="px-5 py-3" x-text="k.penghuni"></td>
                        <td class="px-5 py-3" x-text="k.judul"></td>
                        <td class="px-5 py-3">
                            <span class="px-3 py-1 rounded-full text-xs"
                                  :class="statusClass(k.status)"
                                  x-text="k.status"></span>
                        </td>
                        <td class="px-5 py-3 text-center space-x-1">
                            {{-- Tombol Ambil --}}
                            <button 
                                x-show="!k.penanggungJawab" 
                                @click="ambilKeluhan(k)"
                                class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                Ambil Keluhan
                            </button>

                            {{-- Tombol Detail --}}
                            <button 
                                @click="openModal(k)"
                                class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                                Detail
                            </button>
                        </td>
                    </tr>
                </template>

                <template x-if="dataKeluhanMasuk.length === 0">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500 italic">
                            Tidak ada keluhan baru
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- MODAL DETAIL --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div @click.outside="showModal=false"
             class="bg-gray-100 w-full max-w-xl rounded-xl shadow-lg overflow-hidden">

            {{-- HEADER --}}
            <div class="px-6 py-4 bg-gray-200 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Detail Keluhan</h2>
                <button @click="showModal=false" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
            </div>

            {{-- BODY --}}
            <div class="p-6 grid grid-cols-1 gap-4 text-sm">

                <div class="bg-white rounded-lg p-4 space-y-2">
                    <p><strong>No. Unit:</strong> <span x-text="selected.unit"></span></p>
                    <p><strong>No. Tiket:</strong> <span x-text="selected.id"></span></p>
                    <p><strong>Nama:</strong> <span x-text="selected.penghuni"></span></p>
                    <p><strong>Telepon:</strong> <span x-text="selected.telepon"></span></p>
                    <p><strong>Hari & Tanggal:</strong> <span x-text="selected.tanggal"></span></p>
                    <div>
                        <p class="font-medium">Deskripsi Keluhan:</p>
                        <div class="mt-1 bg-gray-100 p-3 rounded" x-text="selected.deskripsi"></div>
                    </div>
                    <div x-show="selected.lampiran">
                        <p class="font-medium">Lampiran:</p>
                        <a :href="selected.lampiran" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function keluhanApp() {
    return {
        showModal: false,
        selected: {},
        currentTR: 'TR1', // ganti dengan Auth::user()->name
        dataKeluhanMasuk: [
            {
                id: 'TK-001',
                unit: 'A-101',
                tanggal: 'Senin, 12 Feb 2026',
                penghuni: 'Budi Santoso',
                telepon: '08123456789',
                judul: 'AC Tidak Dingin',
                deskripsi: 'AC ruang tamu tidak dingin sejak pagi.',
                lampiran: 'https://example.com/file/ac_photo.jpg',
                status: 'Unassign',
                penanggungJawab: null
            },
            {
                id: 'TK-002',
                unit: 'B-203',
                tanggal: 'Selasa, 13 Feb 2026',
                penghuni: 'Siti Aminah',
                telepon: '08198765432',
                judul: 'Lampu Mati',
                deskripsi: 'Lampu kamar mati.',
                lampiran: '',
                status: 'Unassign',
                penanggungJawab: null
            }
        ],

        dataKeluhanTR: [],

        ambilKeluhan(k){
            k.status = 'Open';
            k.penanggungJawab = this.currentTR;

            // pindahkan ke daftar TR
            this.dataKeluhanTR.push(k);

            // hapus dari daftar keluhan masuk
            this.dataKeluhanMasuk = this.dataKeluhanMasuk.filter(x => x.id !== k.id);

            alert('Keluhan berhasil diambil! Silakan cek halaman "Keluhan Tanggung Jawab".');
        },

        openModal(k){
            this.selected = JSON.parse(JSON.stringify(k));
            this.showModal = true;
        },

        statusClass(status){
            return {
                'bg-yellow-100 text-yellow-700': status === 'Unassign',
                'bg-green-100 text-green-700': status === 'Open'
            }
        }
    }
}
</script>
@endsection
