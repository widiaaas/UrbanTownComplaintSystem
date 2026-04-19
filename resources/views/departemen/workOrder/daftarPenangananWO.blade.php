@extends('layouts.app')

@section('title', 'Daftar Penanganan Work Order')

@section('content')
<div x-data="penangananWOApp()" class="p-6 space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Penanganan Work Order</h1>
        <p class="text-sm text-gray-500">
            Work Order yang telah diambil dan menjadi tanggung jawab Anda
        </p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="max-h-[420px] overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-700 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3 text-center">No</th>
                        <th class="px-5 py-3 text-center">No WO</th>
                        <th class="px-5 py-3 text-center">Tanggal</th>
                        <th class="px-5 py-3 text-center">Unit</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(wo, index) in woPetugas" :key="wo.id">
                        <tr class="border-t hover:bg-gray-50">
                        <td class="px-5 py-3 text-center" x-text="index + 1"></td>
                            <td class="px-5 py-3 text-center font-medium" x-text="wo.no"></td>
                            <td class="px-5 py-3 text-center" x-text="wo.tanggal"></td>
                            <td class="px-5 py-3 text-center" x-text="wo.unit"></td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs"
                                      :class="statusClass(wo.status)"
                                      x-text="wo.status"></span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a
                                        :href="'/detailWorkOrder?id=' + wo.id"
                                        class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700">
                                        Detail
                                    </a>

                                    <!-- <template x-if="wo.status !== 'Close'">
                                        <button
                                            @click="openStatusModal(wo)"
                                            class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                                            Ubah Status
                                        </button>
                                    </template> -->
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template x-if="woPetugas.length === 0">
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500 italic">
                                Tidak ada Work Order yang ditangani
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function penangananWOApp() {
    return {
        woPetugas: @json($wo),

        statusModalOpen: false,
        selectedWO: {},
        newStatus: '',

        openStatusModal(wo) {
            this.selectedWO = { ...wo };
            this.newStatus = wo.status;
            this.statusModalOpen = true;
        },

        statusClass(status) {
            const classes = {
                'Open': 'bg-blue-100 text-blue-700',
                'On progress': 'bg-yellow-100 text-yellow-700',
                'Waiting': 'bg-orange-100 text-orange-700',
                'Close': 'bg-green-100 text-green-700'
            };
            return classes[status] || 'bg-gray-100 text-gray-700';
        }
    }
}
</script>
@endsection