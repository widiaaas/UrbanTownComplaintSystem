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
                        <th class="px-5 py-3 text-left">No WO</th>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Unit</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="wo in woPetugas" :key="wo.id">
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium" x-text="wo.no"></td>
                            <td class="px-5 py-3" x-text="wo.tanggal"></td>
                            <td class="px-5 py-3" x-text="wo.unit"></td>
                            <td class="px-5 py-3">
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
        woPetugas: [
            {
                id: 1,
                no: 'WO-001',
                tiket: 'TCK-001',
                unit: 'A-101',
                tanggal: '12 Feb 2026 10:30',
                requestor: 'Budi Santoso',
                telepon: '08123456789',
                instruksi: 'Periksa AC ruang tamu A-101',
                lampiran: ['foto_ac_1.jpg','foto_ac_2.jpg'],
                status: 'Open',
                riwayat: []
            },
            {
                id: 2,
                no: 'WO-002',
                tiket: 'TCK-002',
                unit: 'B-205',
                tanggal: '13 Feb 2026 09:30',
                requestor: 'Siti Aminah',
                telepon: '08129876543',
                instruksi: 'Periksa kran wastafel kamar mandi B-205',
                lampiran: ['lampu_mati.jpg'],
                status: 'Waiting',
                riwayat: []
            },
            {
                id: 3,
                no: 'WO-003',
                tiket: 'TCK-003',
                unit: 'C-310',
                tanggal: '14 Feb 2026 14:00',
                requestor: 'Ahmad Rizki',
                telepon: '08121234567',
                instruksi: 'Ganti lampu ruang tamu C-310',
                lampiran: ['wo_before.jpg','wo_after.jpg'],
                status: 'Close',
                riwayat: []
            }
        ],
        statusModalOpen: false,
        selectedWO: {},
        newStatus: '',

        openStatusModal(wo) {
            this.selectedWO = { ...wo };
            this.newStatus = wo.status;
            this.statusModalOpen = true;
        },

        simpanPerubahanStatus() {
            if (!this.newStatus) { alert('Pilih status baru terlebih dahulu'); return; }
            if (this.newStatus === this.selectedWO.status) { alert('Status baru harus berbeda dari status saat ini'); return; }

            const index = this.woPetugas.findIndex(w => w.id === this.selectedWO.id);
            if (index !== -1) {
                const oldStatus = this.woPetugas[index].status;
                this.woPetugas[index].status = this.newStatus;
                this.woPetugas[index].riwayat.push({
                    judul: 'Status Berubah',
                    ket: `Status berubah dari ${oldStatus} ke ${this.newStatus}`,
                    waktu: new Date().toLocaleString()
                });
                alert(`Status WO ${this.selectedWO.no} berhasil diubah menjadi ${this.newStatus}`);
            }

            this.statusModalOpen = false;
        },

        statusClass(status) {
            const classes = {
                'Open': 'bg-blue-100 text-blue-700',
                'On Progress': 'bg-yellow-100 text-yellow-700',
                'Waiting': 'bg-orange-100 text-orange-700',
                'Close': 'bg-green-100 text-green-700'
            };
            return classes[status] || 'bg-gray-100 text-gray-700';
        }
    }
}
</script>
@endsection