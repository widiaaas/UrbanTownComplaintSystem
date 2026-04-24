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

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-xl shadow p-4 flex flex-col md:flex-row gap-4">

        {{-- Search --}}
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari</label>
            <input 
                type="text"
                x-model="search"
                placeholder="Cari no WO / unit..."
                class="w-full mt-1 border rounded-lg px-3 py-2">
        </div>

        {{-- Status --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select x-model="statusFilter" class="w-full mt-1 border rounded-lg px-3 py-2">
                <option value="">Semua</option>
                <option value="Open">Open</option>
                <option value="On progress">On Progress</option>
                <option value="Waiting">Waiting</option>
                <option value="Close">Close</option>
            </select>
        </div>

        {{-- Reset --}}
        <div class="flex items-end">
            <button 
                @click="resetFilter"
                class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                Reset
            </button>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="max-h-[420px] overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-700 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3 text-center">No</th>
                        <th class="px-5 py-3 text-center">No WO</th>
                        <th class="px-5 py-3 text-center">Unit</th>
                        <th class="px-5 py-3 text-center">Tanggal</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(wo, index) in filteredWO" :key="wo.id">
                        <tr class="border-t hover:bg-gray-50">
                        <td class="px-5 py-3 text-center" x-text="index + 1"></td>
                            <td class="px-5 py-3 text-center font-medium" x-text="wo.no"></td>
                            <td class="px-5 py-3 text-center" x-text="wo.unit"></td>
                            <td class="px-5 py-3 text-center" x-text="wo.tanggal"></td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs"
                                      :class="statusClass(wo.status)"
                                      x-text="wo.status"></span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a
                                        :href="'/detailWorkOrder/' + wo.id"
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

                    <template x-if="filteredWO.length === 0">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500 italic">
                                Data Work Order tidak ditemukan
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
        search: '',
        statusFilter: '',

        // ================= FILTERED DATA =================
        get filteredWO() {
            return this.woPetugas.filter(wo => {

                const search = this.search.toLowerCase();

                const matchSearch =
                    (wo.no || '').toLowerCase().includes(search) ||
                    (wo.unit || '').toLowerCase().includes(search);

                const matchStatus =
                    !this.statusFilter ||
                    wo.status === this.statusFilter;

                return matchSearch && matchStatus;
            });
        },

        // ================= RESET =================
        resetFilter() {
            this.search = '';
            this.statusFilter = '';
        },

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