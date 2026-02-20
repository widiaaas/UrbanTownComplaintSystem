@extends('layouts.app')

@section('title', 'Work Order')

@section('content')
<div x-data="workOrderUI()" class="p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Work Order</h1>
        <button @click="openCreate = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Buat Work Order
        </button>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">No WO</th>
                    <th class="px-4 py-3 text-left">Unit</th>
                    <th class="px-4 py-3 text-left">Departemen</th>
                    <th class="px-4 py-3 text-left">Prioritas</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="wo in workOrders" :key="wo.id">
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium" x-text="wo.kode"></td>
                        <td class="px-4 py-3" x-text="wo.unit"></td>
                        <td class="px-4 py-3" x-text="wo.departemen"></td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold"
                                :class="priorityClass(wo.prioritas)"
                                x-text="wo.prioritas">
                            </span>
                        </td>
                        <td class="px-4 py-3" x-text="wo.status"></td>
                        <td class="px-4 py-3 text-center">
                            <button
                                @click="showDetail(wo)"
                                class="text-blue-600 hover:underline">
                                Detail
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- MODAL DETAIL --}}
    @include('work-order.partials.detail-modal')

    {{-- MODAL CREATE --}}
    @include('work-order.partials.create-modal')

</div>

<script>
function workOrderUI() {
    return {
        openDetail: false,
        openCreate: false,
        selectedWO: null,

        workOrders: [
            {
                id: 1,
                kode: 'WO-001',
                unit: 'A-101',
                departemen: 'Teknik',
                prioritas: 'Tinggi',
                status: 'Dalam Proses',
                deskripsi: 'AC tidak dingin'
            },
            {
                id: 2,
                kode: 'WO-002',
                unit: 'B-202',
                departemen: 'Housekeeping',
                prioritas: 'Sedang',
                status: 'Dikirim ke Departemen',
                deskripsi: 'Kebocoran kamar mandi'
            }
        ],

        showDetail(wo) {
            this.selectedWO = wo
            this.openDetail = true
        },

        priorityClass(p) {
            return {
                'bg-red-100 text-red-700': p === 'Tinggi',
                'bg-yellow-100 text-yellow-700': p === 'Sedang',
                'bg-green-100 text-green-700': p === 'Rendah'
            }
        }
    }
}
</script>
@endsection
