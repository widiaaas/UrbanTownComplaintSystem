@extends('layouts.app')

@section('title', 'Daftar Penanganan Keluhan')

@section('content')
<div x-data="penangananApp()" class="p-6 space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Penanganan Keluhan</h1>
        <p class="text-sm text-gray-500">
            Keluhan yang telah diambil dan menjadi tanggung jawab Anda
        </p>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="max-h-[420px] overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-700 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3 text-left">No Tiket</th>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        <th class="px-5 py-3 text-left">Penghuni</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="k in keluhan" :key="k.id">
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium" x-text="k.tiket"></td>
                            <td class="px-5 py-3" x-text="k.tanggal"></td>
                            <td class="px-5 py-3" x-text="k.nama"></td>
                            
                            <td class="px-5 py-3">
                                <span class="px-3 py-1 rounded-full text-xs"
                                    :class="statusClass(k.status)"
                                    x-text="k.status">
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Detail --}}
                                    <a
                                        :href="'/detailKeluhan?id=' + k.id"
                                        class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700">
                                        Detail
                                    </a>
                                    
                                    {{-- Tombol Ubah Status (hanya untuk status Open dan On Progress) --}}
                                    <template x-if="k.status !== 'Close'">
                                        <button
                                            @click="openStatusModal(k)"
                                            class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                                            Ubah Status
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL UBAH STATUS --}}
    <div
        x-show="statusModalOpen"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
    >
        <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden">
            
            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    Ubah Status Keluhan
                </h3>
                <button
                    @click="statusModalOpen = false"
                    class="text-gray-500 hover:text-gray-700 text-xl leading-none"
                >
                    &times;
                </button>
            </div>

            {{-- BODY --}}
            <div class="px-6 py-4 space-y-4">
                <p class="text-sm">
                    <span class="font-medium">Tiket:</span> 
                    <span x-text="selectedKeluhan.tiket"></span>
                </p>
                <p class="text-sm">
                    <span class="font-medium">Penghuni:</span> 
                    <span x-text="selectedKeluhan.nama"></span>
                </p>
                <p class="text-sm">
                    <span class="font-medium">Status Saat Ini:</span> 
                    <span class="px-2 py-0.5 rounded-full text-xs"
                          :class="statusClass(selectedKeluhan.status)"
                          x-text="selectedKeluhan.status">
                    </span>
                </p>

                {{-- Pilihan Status Baru --}}
                <div>
                    <label class="text-sm font-medium mb-1 block">
                        Status Baru
                    </label>
                    <select 
                        x-model="newStatus"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                    >
                        <option value="Open">Open</option>
                        <option value="On Progress">On Progress</option>
                        <option value="Close">Close (Selesai)</option>
                    </select>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="text-sm font-medium mb-1 block">
                        Catatan Perubahan
                    </label>
                    <textarea
                        x-model="statusCatatan"
                        class="w-full border rounded-lg px-3 py-2 text-sm"
                        rows="3"
                        placeholder="Tuliskan alasan perubahan status...">
                    </textarea>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t flex justify-end gap-2">
                <button
                    @click="statusModalOpen = false"
                    class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                >
                    Batal
                </button>
                <button
                    @click="simpanPerubahanStatus"
                    class="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white"
                >
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function penangananApp() {
    return {
        // Data keluhan
        keluhan: [
            {
                id: 1,
                tiket: 'TCK-001',
                tanggal: '12 Feb 2026',
                unit: 'A-101',
                nama: 'Budi Santoso',
                telepon: '08123456789',
                status: 'Open',
                deskripsi: 'AC ruang tamu tidak dingin sejak pagi.',
                riwayat: [
                    { judul: 'Keluhan Masuk', ket: 'Keluhan diterima oleh sistem', waktu: '12 Feb 2026 09:00' }
                ]
            },
            {
                id: 2,
                tiket: 'TCK-002',
                tanggal: '13 Feb 2026',
                unit: 'B-205',
                nama: 'Siti Aminah',
                telepon: '08129876543',
                status: 'On Progress',
                deskripsi: 'Lampu di ruang tamu mati.',
                riwayat: [
                    { judul: 'Keluhan Masuk', ket: 'Keluhan diterima oleh sistem', waktu: '13 Feb 2026 08:30' },
                    { judul: 'TR Mengambil Keluhan', ket: 'Keluhan di-assign ke tim TR', waktu: '13 Feb 2026 08:45' }
                ]
            },
            {
                id: 3,
                tiket: 'TCK-003',
                tanggal: '14 Feb 2026',
                unit: 'C-310',
                nama: 'Ahmad Rizki',
                telepon: '08121234567',
                status: 'Close',
                deskripsi: 'Kran wastafel kamar mandi bocor.',
                riwayat: [
                    { judul: 'Keluhan Masuk', ket: 'Keluhan diterima oleh sistem', waktu: '14 Feb 2026 07:15' },
                    { judul: 'TR Mengambil Keluhan', ket: 'Keluhan di-assign ke tim TR', waktu: '14 Feb 2026 07:30' },
                    { judul: 'Keluhan Ditutup', ket: 'Keluhan ditutup oleh TR', waktu: '14 Feb 2026 09:00' }
                ]
            }
        ],

        // State modal
        statusModalOpen: false,
        selectedKeluhan: {},
        newStatus: '',
        statusCatatan: '',

        // Method untuk membuka modal ubah status
        openStatusModal(keluhan) {
            this.selectedKeluhan = { ...keluhan };
            this.newStatus = keluhan.status;
            this.statusCatatan = '';
            this.statusModalOpen = true;
        },

        // Method untuk menyimpan perubahan status
        simpanPerubahanStatus() {
            if (!this.newStatus) {
                alert('Pilih status baru terlebih dahulu');
                return;
            }

            if (this.newStatus === this.selectedKeluhan.status) {
                alert('Status baru harus berbeda dari status saat ini');
                return;
            }

            if (!this.statusCatatan.trim()) {
                alert('Catatan perubahan wajib diisi');
                return;
            }

            // Cari index keluhan yang akan diubah
            const index = this.keluhan.findIndex(k => k.id === this.selectedKeluhan.id);
            
            if (index !== -1) {
                // Simpan status lama
                const oldStatus = this.keluhan[index].status;
                
                // Update status
                this.keluhan[index].status = this.newStatus;
                
                // Tambahkan ke riwayat
                const riwayatBaru = {
                    judul: 'Status Berubah',
                    ket: `Status berubah dari ${oldStatus} ke ${this.newStatus}: ${this.statusCatatan}`,
                    waktu: this.now()
                };
                
                if (!this.keluhan[index].riwayat) {
                    this.keluhan[index].riwayat = [];
                }
                
                this.keluhan[index].riwayat.push(riwayatBaru);
                
                // Tampilkan notifikasi sukses
                alert(`Status keluhan ${this.selectedKeluhan.tiket} berhasil diubah menjadi ${this.newStatus}`);
            }

            // Tutup modal
            this.statusModalOpen = false;
        },

        // Method untuk mendapatkan class status
        statusClass(status) {
            const classes = {
                'Open': 'bg-blue-100 text-blue-700',
                'On Progress': 'bg-yellow-100 text-yellow-700',
                'Close': 'bg-green-100 text-green-700'
            };
            return classes[status] || 'bg-gray-100 text-gray-700';
        },

        // Method untuk mendapatkan waktu sekarang
        now() {
            const d = new Date();
            const hari = d.getDate();
            const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][d.getMonth()];
            const tahun = d.getFullYear();
            const jam = d.getHours().toString().padStart(2, '0');
            const menit = d.getMinutes().toString().padStart(2, '0');
            
            return `${hari} ${bulan} ${tahun} ${jam}:${menit}`;
        }
    }
}
</script>
@endsection