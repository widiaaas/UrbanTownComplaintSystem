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

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-xl shadow p-4 flex flex-col md:flex-row gap-4">

        {{-- Search --}}
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari</label>
            <input 
                type="text"
                x-model="search"
                placeholder="Cari tiket / unit / penghuni..."
                class="w-full mt-1 border rounded-lg px-3 py-2">
        </div>

        {{-- Status --}}
        <div>
            <label class="text-sm font-medium text-gray-700">Status</label>
            <select x-model="statusFilter" class="w-full mt-1 border rounded-lg px-3 py-2">
                <option value="">Semua</option>
                <option value="open">Open</option>
                <option value="on progress">On Progress</option>
                <option value="close">Close</option>
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
                        <th class="px-5 py-3 text-center">No Tiket</th>
                        <th class="px-5 py-3 text-center">Unit</th>
                        <th class="px-5 py-3 text-center">Tanggal</th>
                        <th class="px-5 py-3 text-center">Penghuni</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- DATA --}}
                    <template x-for="(k, index) in filteredKeluhan" :key="k.id">
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-5 py-3 text-center" x-text="index + 1"></td>
                            <td class="px-5 py-3 text-center font-medium" x-text="k.ticket"></td>
                            <td class="px-5 py-3 text-center" x-text="k.unit"></td>
                            <td class="px-5 py-3 text-center" x-text="k.waktu"></td>
                            <td class="px-5 py-3 text-center" x-text="k.penghuni"></td>

                            <td class="px-5 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs"
                                    :class="statusClass(k.status)"
                                    x-text="k.status">
                                </span>
                            </td>

                            <td class="px-5 py-3 text-center">
                                <a :href="'/keluhan/' + k.id"
                                class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    </template>

                    {{-- 🔥 EMPTY STATE (SAMA PERSIS KAYAK WO) --}}
                    <template x-if="filteredKeluhan.length === 0">
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500 italic">
                                Data keluhan tidak ditemukan
                            </td>
                        </tr>
                    </template>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
function penangananApp() {
    return {
        // ================= DATA =================
        keluhan: @json($keluhan),
        loading: false,

        // ================= STATE =================
        statusModalOpen: false,
        selectedKeluhan: {},
        newStatus: '',
        statusCatatan: '',
        search: '',
        statusFilter: '',


        // ================= COMPUTED =================
        get filteredKeluhan() {
            return this.keluhan.filter(k => {

                const search = this.search.toLowerCase();

                const matchSearch =
                    k.ticket.toLowerCase().includes(search) ||
                    k.unit.toLowerCase().includes(search) ||
                    k.penghuni.toLowerCase().includes(search);

                const matchStatus =
                    !this.statusFilter ||
                    k.status.toLowerCase() === this.statusFilter;

                return matchSearch && matchStatus;
            });
        },

    // ================= RESET =================
    resetFilter() {
        this.search = '';
        this.statusFilter = '';
    },

        // ================= OPEN MODAL =================
        openStatusModal(keluhan) {
            this.selectedKeluhan = { ...keluhan };
            this.newStatus = keluhan.status;
            this.statusCatatan = '';
            this.statusModalOpen = true;
        },

        // ================= VALIDASI + CONFIRM =================
        simpanPerubahanStatus(){

            if (!this.newStatus) {
                Swal.fire('Oops!', 'Pilih status dulu', 'warning');
                return;
            }

            if (this.newStatus === this.selectedKeluhan.status) {
                Swal.fire('Oops!', 'Status harus berbeda dari sebelumnya', 'warning');
                return;
            }

            if (!this.statusCatatan.trim()) {
                Swal.fire('Oops!', 'Catatan wajib diisi', 'warning');
                return;
            }

            Swal.fire({
                title: 'Update Status?',
                text: 'Perubahan akan disimpan ke sistem',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.prosesUpdateStatus();
                }
            });
        },

        // ================= HIT API =================
        prosesUpdateStatus(){

            this.loading = true;

            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menyimpan perubahan',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/keluhan/${this.selectedKeluhan.id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    status: this.newStatus,
                    catatan: this.statusCatatan
                })
            })
            .then(res => res.json())
            .then(res => {

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                // 🔥 UPDATE UI
                const index = this.keluhan.findIndex(k => k.id === this.selectedKeluhan.id);
                if(index !== -1){
                    this.keluhan[index].status = this.newStatus;
                }

                this.statusModalOpen = false;
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat update status'
                });
            })
            .finally(() => {
                this.loading = false;
            });
        },

        // ================= STATUS BADGE =================
        statusClass(status){
            const s = (status || '')
                .toLowerCase()
                .trim()
                .replace(/\s+/g, ' '); // 🔥 normalize spasi

            return {
                'bg-blue-100 text-blue-700': s === 'open',
                'bg-yellow-100 text-yellow-700': s === 'on progress',
                'bg-green-100 text-green-700': s === 'close',
                'bg-gray-100 text-gray-700': !['open','on progress','close'].includes(s)
            }
        },

        // ================= FORMAT WAKTU =================
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