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
                    <th class="px-5 py-3 text-center">No</th>
                    <th class="px-5 py-3 text-center">Unit</th>
                    <th class="px-5 py-3 text-center">Tanggal</th>
                    <th class="px-5 py-3 text-center">Instruksi</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="wo in dataWOMasuk" :key="wo.id">
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-5 py-3" x-text="wo.no"></td>
                        <td class="px-5 py-3 font-medium" x-text="wo.unit"></td>
                        <td class="px-5 py-3" x-text="wo.tanggal"></td>
                        <td class="px-5 py-3" x-text="wo.instruksi"></td>
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
                <p x-text="JSON.stringify(selected)"></p>
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
                        <p><strong>TR penanggung Jawab:</strong> <span x-text="selected.tr"></span></p>
                    </div>
                </div>

                {{-- Instruksi --}}
                <div class="bg-white rounded-lg p-4 border">
                    <p class="font-medium mb-2">Instruksi / Deskripsi WO:</p>
                    <div class="bg-gray-100 p-3 rounded" x-text="selected.instruksi"></div>
                </div>


                {{-- Lampiran --}}
                <div>
                    <p class="font-medium mb-2">Lampiran:</p>

                    <div class="flex gap-2 flex-wrap">

                        <template x-if="selected.lampiran && selected.lampiran.length">
                            <template x-for="file in selected.lampiran" :key="file">
                                <button
                                    @click="previewFile(file)"
                                    class="px-3 py-1 bg-blue-50 text-blue-600 text-xs rounded hover:underline">
                                    Lihat File
                                </button>
                            </template>
                        </template>

                        <template x-if="!selected.lampiran || !selected.lampiran.length">
                            <span class="text-gray-400 text-sm italic">Tidak ada lampiran</span>
                        </template>

                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-3 bg-gray-100 border-t flex justify-end flex-shrink-0">
                <button @click="showModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
            </div>

        </div>

            <!-- ================= MODAL PREVIEW FILE ================= -->
        <div x-show="openPreview" x-cloak
            class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">

            <div class="bg-white rounded-lg p-4 max-w-4xl w-full relative">

                <button @click="openPreview=false"
                    class="absolute top-2 right-2 text-xl">✕</button>

                <div class="mt-6">

                    <!-- IMAGE -->
                    <template x-if="previewUrl.endsWith('.jpg') || previewUrl.endsWith('.png') || previewUrl.endsWith('.jpeg')">
                        <img :src="'/storage/' + previewUrl" class="w-full rounded">
                    </template>

                    <!-- PDF -->
                    <template x-if="previewUrl.endsWith('.pdf')">
                        <iframe :src="'/storage/' + previewUrl" class="w-full h-[70vh]"></iframe>
                    </template>

                </div>
            </div>
        </div>
    </div>

<script>
function workOrderApp(){
    return {

        showModal: false,
        openPreview: false,
        selected: {},
        previewUrl: '',

        // 🔥 DATA DARI BACKEND
        dataWOMasuk: @json($wo),

        // ================= AMBIL WO =================
        ambilWO(wo){

        Swal.fire({
            title: 'Ambil Work Order?',
            text: `Work Order ${wo.id} akan menjadi tanggung jawab Anda`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Ambil',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                // 🔥 LOADING
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/work-order/${wo.id}/ambil`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;
                    return data;
                })
                .then(res => {

                    Swal.close();

                    // hapus dari tabel
                    this.dataWOMasuk = this.dataWOMasuk.filter(x => x.id !== wo.id);

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });

                })
                .catch(err => {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: err.message || 'Terjadi kesalahan'
                    });

                });

            }

        });
        },

        previewFile(file){
            this.previewUrl = file;
            this.openPreview = true;
        },

        // ================= MODAL =================
        openModal(wo){
            this.selected = {
                ...wo,
                tr: wo.tr ?? '-'
            };
            this.showModal = true;
        },

    }
}
</script>
@endsection