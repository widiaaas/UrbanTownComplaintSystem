@extends('layouts.app')

@section('title', 'Work Order Masuk')

@section('content')
<div x-data="workOrderApp()" class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Work Order Masuk</h1>
        <p class="text-sm text-gray-500">Petugas dapat mengambil WO untuk menjadi tanggung jawabnya</p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-5 py-3 text-left">Unit</th>
                    <th class="px-5 py-3 text-left">Tanggal</th>
                    <th class="px-5 py-3 text-left">Instruksi</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="wo in dataWOMasuk" :key="wo.id">
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-5 py-3 font-medium" x-text="wo.unit"></td>
                        <td class="px-5 py-3" x-text="wo.tanggal"></td>
                        <td class="px-5 py-3" x-text="wo.instruksi"></td>
                        <td class="px-5 py-3">
                            <span class="px-3 py-1 rounded-full text-xs inline-block"
                                  :class="statusClass(wo.status)"
                                  x-text="wo.status"></span>
                        </td>
                        <td class="px-5 py-3 text-center space-x-1">
                            {{-- Tombol Ambil WO --}}
                            <button 
                                x-show="!wo.penanggungJawab" 
                                @click="ambilWO(wo)"
                                class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                Ambil WO
                            </button>

                            {{-- Tombol Detail --}}
                            <button 
                                @click="openModal(wo)"
                                class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">
                                Detail
                            </button>
                        </td>
                    </tr>
                </template>

                <template x-if="dataWOMasuk.length === 0">
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500 italic">
                            Tidak ada Work Order baru
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- MODAL DETAIL WO --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="showModal = false"
             class="bg-white w-full sm:max-w-md md:max-w-2xl rounded-lg shadow-lg max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div class="px-6 py-4 bg-gray-200 flex justify-between items-center border-b flex-shrink-0">
                <h2 class="text-lg font-semibold text-gray-800">Detail Work Order</h2>
                <button @click="showModal = false" class="text-gray-700 text-2xl hover:text-gray-900">&times;</button>
            </div>

            {{-- Body --}}
            <div class="p-6 flex-1 overflow-y-auto space-y-6 text-sm">

                {{-- Info WO --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <p><strong>No. Unit:</strong> <span x-text="selected.unit"></span></p>
                        <p><strong>No. WO / Tiket:</strong> <span x-text="selected.id"></span></p>
                        <p><strong>Requestor / Penghuni:</strong> <span x-text="selected.requestor ?? selected.penghuni"></span></p>
                    </div>
                    <div class="space-y-2">
                        <p><strong>Telepon:</strong> <span x-text="selected.telepon"></span></p>
                        <p><strong>Tanggal:</strong> <span x-text="selected.tanggal"></span></p>
                    </div>
                </div>

                {{-- Instruksi --}}
                <div class="bg-white rounded-lg p-4 border">
                    <p class="font-medium mb-2">Instruksi / Deskripsi WO:</p>
                    <div class="bg-gray-100 p-3 rounded" x-text="selected.instruksi"></div>
                </div>

                {{-- Lampiran --}}
                <div x-show="selected.lampiran" class="bg-white rounded-lg p-4 border">
                    <p class="font-medium mb-2">Lampiran:</p>
                    <a :href="selected.lampiran" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-3 bg-gray-100 border-t flex justify-end flex-shrink-0">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
            </div>

        </div>
    </div>

</div>

<script>
function workOrderApp() {
    return {
        showModal: false,
        selected: {},
        currentPetugas: 'Petugas1', // ganti dengan Auth::user()->name
        dataWOMasuk: [
            {
                id: 'WO-001',
                unit: 'A-101',
                tanggal: 'Senin, 12 Feb 2026',
                requestor: 'Budi Santoso',
                telepon: '08123456789',
                instruksi: 'Periksa AC unit A-101',
                lampiran: 'https://example.com/file/ac_photo.jpg',
                status: 'Unassign',
                penanggungJawab: null
            },
            {
                id: 'WO-002',
                unit: 'B-203',
                tanggal: 'Selasa, 13 Feb 2026',
                requestor: 'Siti Aminah',
                telepon: '08198765432',
                instruksi: 'Ganti lampu ruang B-203',
                lampiran: '',
                status: 'Unassign',
                penanggungJawab: null
            }
        ],

        dataWOPetugas: [],

        ambilWO(wo){
            wo.status = 'Open';
            wo.penanggungJawab = this.currentPetugas;

            // pindahkan ke daftar WO petugas
            this.dataWOPetugas.push(wo);

            // hapus dari daftar WO masuk
            this.dataWOMasuk = this.dataWOMasuk.filter(x => x.id !== wo.id);

            alert('WO berhasil diambil! Silakan cek halaman "Work Order Tanggung Jawab".');
        },

        openModal(wo){
            this.selected = JSON.parse(JSON.stringify(wo));
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