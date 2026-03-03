@extends('layouts.app')

@section('title', 'Detail Work Order')

@section('content')
<div x-data="detailWOApp()" x-init="init()" class="p-6 max-w-5xl mx-auto space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Work Order</h1>
            <p class="text-sm text-gray-500">
                No WO: <span x-text="wo.no"></span>
            </p>
        </div>

        <a href="/daftarWorkOrder" class="text-sm text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

    {{-- ================= INFO UTAMA WO ================= --}}
    <div class="grid grid-cols-2 gap-4 text-sm bg-white p-6 rounded-xl shadow">
        <p><b>Ticket</b><br><span x-text="wo.tiket"></span></p>
        <p><b>Departemen</b><br><span x-text="wo.dept"></span></p>
        <p><b>Petugas</b><br><span x-text="wo.petugas"></span></p>
        <p><b>Tanggal WO</b><br><span x-text="wo.tanggal"></span></p>
        <p><b>Status WO</b><br>
            <span class="inline-block text-xs px-2 py-1 rounded"
                  :class="statusClass(wo.status)"
                  x-text="wo.status">
            </span>
        </p>
    </div>

    {{-- ================= INSTRUKSI ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <h3 class="font-semibold">Instruksi Pekerjaan</h3>
        <div class="bg-gray-100 rounded-lg p-4 text-sm" x-text="wo.instruksi"></div>
    </div>

    {{-- ================= RIWAYAT PENANGANAN ================= --}}
    <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">Riwayat Penanganan</h3>
        </div>

        <template x-if="wo.laporan && wo.laporan.length">
            <div class="space-y-3">
                <template x-for="(lapor, index) in wo.laporan" :key="index">
                    <div class="relative pl-5 py-3 rounded-md"
                         :class="{
                             'border-l-4 border-blue-500 bg-blue-50/30': lapor.status === 'On Progress',
                             'border-l-4 border-orange-500 bg-orange-50/30': lapor.status === 'Waiting',
                             'border-l-4 border-green-500 bg-green-50/30': lapor.status === 'Close'
                         }">
                        <p class="font-medium text-gray-800" x-text="lapor.judul"></p>
                        <p class="text-gray-600 mt-1" x-text="lapor.ket"></p>
                        <p class="text-xs text-gray-400 mt-1" x-text="lapor.waktu"></p>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!wo.laporan || !wo.laporan.length">
            <p class="text-sm text-gray-400 italic">
                Belum ada laporan pekerjaan dari departemen.
            </p>
        </template>
    </div>

    {{-- STATUS workOrder --}}
    <div class="bg-white p-4 rounded-xl border space-y-2">
        <h3 class="font-semibold text-sm">Status Work Order</h3>

        <select x-model="workOrder.status"
            class="w-full border rounded-lg px-3 py-2">
            <option>Open</option>
            <option>On Progress</option>
            <option>Waiting</option>
            <option>Close</option>
        </select>
    </div>

    {{-- ================= FORM Penanganan ================= --}}
    <template x-if="wo.status !== 'Close'">
        <div class="bg-white p-6 rounded-xl shadow space-y-4">

            <h3 class="font-semibold">Form Penanganan WO</h3>

            {{-- JUDUL Penanganan --}}
            <div>
                <label class="text-sm font-medium mb-1 block">
                    Judul Penanganan
                </label>
                <input
                    type="text"
                    x-model="penanganan.judul"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    placeholder="Masukkan judul penanganan"
                >
            </div>

            {{-- CATATAN Penanganan --}}
            <div>
                <label class="text-sm font-medium mb-1 block">
                    Catatan Penanganan
                </label>
                <textarea
                    x-model="penanganan.catatan"
                    class="w-full border rounded-lg px-3 py-2 text-sm"
                    rows="3"
                    placeholder="Masukkan catatan penanganan"
                ></textarea>
            </div>

            {{-- LAMPIRAN Penanganan --}}
            <div>
                <label class="text-sm font-medium mb-1 block">
                    Lampiran Dokumentasi
                </label>

                <input
                    type="file"
                    multiple
                    @change="handleUploadpenanganan($event)"
                    class="text-sm"
                >

                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(file, index) in penanganan.lampiran" :key="index">
                        <div class="relative border rounded px-3 py-1 text-xs bg-gray-50">
                            <span x-text="file.name"></span>
                            <button
                                @click="hapusLampiranpenanganan(index)"
                                class="ml-2 text-red-500 hover:text-red-700">
                                ✕
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- {{-- UBAH STATUS --}}
            <div>
                <label class="text-sm font-medium mb-1 block">Ubah Status WO</label>
                <select x-model="newStatus" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="Open">Open</option>
                    <option value="On Progress">On Progress</option>
                    <option value="Waiting">Waiting</option>
                    <option value="Close">Close</option>
                </select>
            </div> -->

            {{-- ACTION --}}
            <div class="flex gap-3 pt-2">
                <button
                    @click="simpanpenanganan"
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    Simpan Penanganan 
                </button>
            </div>
        </div>
    </template>

</div>

<script>
function detailWOApp() {
    return {
        wo: {},
        penanganan: {
            judul: '',
            catatan: '',
            lampiran: []
        },
        newStatus: '',
        workOrders: [
            {
                id: 1,
                tiket: 'TCK-001',
                no: 'WO-001',
                dept: 'Teknik',
                instruksi: 'Periksa AC ruang tamu A-101',
                status: 'Open',
                petugas: 'Budi Santoso',
                laporan: [],
                lampiran: ['foto_ac_1.jpg','foto_ac_2.jpg'],
                tanggal: '12 Feb 2026 10:30'
            },
            {
                id: 2,
                tiket: 'TCK-002',
                no: 'WO-002',
                dept: 'Teknik',
                instruksi: 'Periksa kran wastafel kamar mandi B-205',
                status: 'Waiting',
                petugas: 'Siti Aminah',
                laporan: [
                    {
                        status: 'Waiting',
                        judul: 'Menunggu Sparepart',
                        ket: 'Sparepart kran belum tersedia',
                        waktu: '13 Feb 2026 14:00'
                    }
                ],
                lampiran: ['lampu_mati.jpg'],
                tanggal: '13 Feb 2026 09:30'
            },
            {
                id: 3,
                tiket: 'TCK-003',
                no: 'WO-003',
                dept: 'Teknik',
                instruksi: 'Ganti lampu ruang tamu C-310',
                status: 'Close',
                petugas: 'Ahmad Rizki',
                laporan: [
                    {
                        status: 'On Progress',
                        judul: 'Pekerjaan Dimulai',
                        ket: 'Pengecekan instalasi lampu',
                        waktu: '14 Feb 2026 13:30'
                    },
                    {
                        status: 'Close',
                        judul: 'Pekerjaan Selesai',
                        ket: 'Lampu diganti dan menyala normal',
                        waktu: '14 Feb 2026 14:00'
                    }
                ],
                lampiran: ['wo_before.jpg','wo_after.jpg'],
                tanggal: '14 Feb 2026 14:00'
            }
        ],

        init() {
            const params = new URLSearchParams(window.location.search);
            const id = parseInt(params.get('id'));
            const woData = this.workOrders.find(w => w.id === id);
            if (woData) {
                this.wo = woData;
                this.newStatus = woData.status; // set default select
            } else {
                alert('Data Work Order tidak ditemukan');
            }
        },

        // Upload lampiran penanganan
        handleUploadPenanganan(event) {
            for (let i = 0; i < event.target.files.length; i++) {
                this.penanganan.lampiran.push(event.target.files[i]);
            }
        },

        // Hapus lampiran penanganan
        hapusLampiranPenanganan(index) {
            this.penanganan.lampiran.splice(index, 1);
        },

        // Simpan penanganan + status (dummy)
        simpanPenanganan() {
            if (!this.penanganan.judul) {
                alert('Judul penanganan tidak boleh kosong');
                return;
            }
            if (!this.penanganan.catatan) {
                alert('Catatan penanganan tidak boleh kosong');
                return;
            }

            // Simpan status
            const oldStatus = this.wo.status;
            this.wo.status = this.newStatus;

            alert(`penanganan tersimpan!\nJudul: ${this.penanganan.judul}\nCatatan: ${this.penanganan.catatan}\nLampiran: ${this.penanganan.lampiran.length} file\nStatus berubah: ${oldStatus} → ${this.newStatus}`);

            // Reset form
            this.penanganan.judul = '';
            this.penanganan.catatan = '';
            this.penanganan.lampiran = [];
        },

        statusClass(status) {
            const classes = {
                'Open': 'bg-blue-100 text-blue-800',
                'On Progress': 'bg-yellow-100 text-yellow-800',
                'Waiting': 'bg-orange-100 text-orange-800',
                'Close': 'bg-green-100 text-green-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-700';
        }
    }
}
</script>
@endsection