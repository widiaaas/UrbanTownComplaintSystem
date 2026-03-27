@extends('layouts.app')

@section('title', 'Kelola Unit')

@section('content')

<div x-data="unitManager()" x-init='init(@json($units))' class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Unit</h1>
        <button
            @click="openCreateUnit = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Tambah Unit
        </button>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium">Cari Unit / Gedung</label>
            <input type="text" x-model="search" class="w-full mt-1 border rounded-lg px-3 py-2">
        </div>
        <div>
            <label class="text-sm font-medium">Lantai</label>
            <select x-model="floorFilter" class="w-full mt-1 border rounded-lg px-3 py-2">
                <option value="">Semua</option>
                @foreach($units->pluck('lantai')->unique() as $floor)
                    <option value="{{ $floor }}">{{ $floor }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2 items-end">
            <button @click="applyFilter()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
            <button @click="resetFilter()" class="px-4 py-2 border rounded-lg">Reset</button>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto overflow-y-visible">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center">No Unit</th>
                    <th class="px-4 py-2 text-center">Gedung</th>
                    <th class="px-4 py-2 text-center">Lantai</th>
                    <th class="px-4 py-2 text-center">Penghuni</th>
                    <th class="px-4 py-2 text-center">Status</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="unit in filteredUnits" :key="unit.id">
                    <tr class="text-center hover:bg-gray-50">
                        <td class="px-4 py-2" x-text="unit.no_unit"></td>
                        <td class="px-4 py-2" x-text="unit.gedung"></td>
                        <td class="px-4 py-2" x-text="unit.lantai"></td>
                        <td class="px-4 py-2" x-text="unit.currentPenghuni || '-'"></td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded"
                                :class="unit.status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                x-text="unit.status"></span>
                        </td>
                        <td class="px-4 py-2 relative">
                            <div x-data="dropdownMenu(unit)" class="relative inline-block text-left">

                                <!-- BUTTON -->
                                <button @click="open = !open"
                                    class="px-3 py-1.5 text-xs bg-gray-200 rounded hover:bg-gray-300 flex items-center gap-1">
                                    Aksi <span class="text-xs">▼</span>
                                </button>

                                <!-- DROPDOWN -->
                                <div x-show="open"
                                    @click.outside="open = false"
                                    x-transition
                                    x-ref="menu"
                                    class="fixed w-44 bg-white border rounded-lg shadow-xl z-[9999]"
                                    x-cloak
                                    x-init="
                                        $watch('open', value => {
                                            if (value) {
                                                let rect = $el.previousElementSibling.getBoundingClientRect();
                                                $el.style.top = (rect.bottom + window.scrollY) + 'px';
                                                $el.style.left = (rect.right - 176) + 'px';
                                            }
                                        })
                                    ">

                                    <button @click="editUnit()" class="w-full text-left px-3 py-2 hover:bg-gray-100">
                                        ✏️ Edit Unit
                                    </button>

                                    <template x-if="status == 'Aktif'">
                                        <div>
                                            <button @click="gantiPenghuni()" class="w-full text-left px-3 py-2 hover:bg-gray-100">
                                                👥 Ganti Penghuni
                                            </button>
                                            <button @click="resetPassword()" class="w-full text-left px-3 py-2 hover:bg-gray-100">
                                                🔑 Reset Kata Sandi
                                            </button>
                                        </div>
                                    </template>

                                    <div class="border-t my-1"></div>

                                    <template x-if="status == 'Aktif'">
                                        <button @click="toggleStatus('nonaktif')" class="w-full text-left px-3 py-2 text-orange-600 hover:bg-orange-50">
                                            ⛔ Nonaktifkan
                                        </button>
                                    </template>

                                    <template x-if="status == 'Nonaktif'">
                                        <button @click="toggleStatus('aktif')" class="w-full text-left px-3 py-2 text-green-600 hover:bg-green-50">
                                            ✅ Aktifkan
                                        </button>
                                    </template>

                                    <button @click="confirmDelete()" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50">
                                        🗑️ Hapus
                                    </button>

                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- ================= MODAL TAMBAH UNIT ================= --}}
    <div x-show="openCreateUnit" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="openCreateUnit = false" class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 space-y-5">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Tambah Unit Baru</h2>
                <button @click="openCreateUnit = false" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
            </div>

            <form @submit.prevent="saveUnit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Unit</label>
                    <input type="text" x-model="newUnit.no_unit" placeholder="Contoh: A-101" class="w-full mt-1 border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gedung</label>
                    <input type="text" x-model="newUnit.gedung" placeholder="Contoh: Tower A" class="w-full mt-1 border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lantai</label>
                    <input type="number" x-model="newUnit.lantai" placeholder="Contoh: 10" class="w-full mt-1 border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Kamar</label>
                    <input type="number" x-model="newUnit.nomor_kamar" placeholder="Contoh: 1" class="w-full mt-1 border rounded-lg px-3 py-2" required>
                </div>

                <template x-if="createdUnit">
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                        <p class="font-semibold text-yellow-800">Akun Unit Berhasil Dibuat</p>
                        <p><strong>Username Login:</strong> <span x-text="createdUnit.no_unit"></span></p>
                        <p>Password Sementara</p>
                        <div class="bg-white border rounded px-3 py-2 font-mono text-center" x-text="createdUnit.password"></div>
                        <p class="text-xs text-gray-600">Berikan password ini kepada penghuni unit untuk login pertama.</p>
                    </div>
                </template>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <button type="button" @click="openCreateUnit = false" class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Unit</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL EDIT UNIT ================= --}}
    <div x-show="openEdit" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openEdit = false" class="bg-white w-full max-w-md rounded-lg p-6 space-y-4">
            <h2 class="font-semibold text-lg">Edit Unit (<span x-text="selectedUnit.no_unit"></span>)</h2>
            
            <div class="space-y-3">
                <div>
                    <label class="text-sm">Gedung</label>
                    <input type="text" x-model="editForm.gedung" class="w-full mt-1 border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-sm">Lantai</label>
                    <input type="number" x-model="editForm.lantai" class="w-full mt-1 border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-sm">Nomor Kamar</label>
                    <input type="number" x-model="editForm.nomor_kamar" class="w-full mt-1 border rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" @click="openEdit = false" class="px-4 py-2 border rounded-lg">Batal</button>
                {{-- GANTI: tidak pakai form submit, langsung @click --}}
                <button type="button" @click="updateUnit()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL GANTI PENGHUNI ================= --}}
    <div x-show="openEditPenghuni" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.outside="openEditPenghuni = false" class="bg-white w-full max-w-lg rounded-xl shadow-lg max-h-[90vh] flex flex-col">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Pergantian Penghuni Unit <span class="text-blue-600" x-text="selectedUnit.no_unit"></span></h2>
            </div>
            <div class="px-6 py-4 space-y-5 overflow-y-auto">
                <div class="bg-gray-50 border rounded-lg p-3 text-sm space-y-1">
                    <p><strong>Gedung:</strong> <span x-text="selectedUnit.gedung"></span></p>
                    <p><strong>Lantai:</strong> <span x-text="selectedUnit.lantai"></span></p>
                </div>

                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-700">Penghuni Aktif Saat Ini</p>
                    <div class="bg-gray-50 border rounded-lg p-3 text-sm space-y-1">
                        <p><strong>Nama:</strong> <span x-text="selectedUnit.currentPenghuni || '-'"></span></p>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-4">
                    <h3 class="text-sm font-semibold text-red-600">Pilih Penghuni Baru</h3>
                    <select x-model="selectedPenghuniId" @change="fetchPenghuniDetail" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                        <option value="">-- Pilih Penghuni --</option>
                        <template x-for="p in penghuniList" :key="p.id">
                            <option :value="p.id" x-text="p.nama"></option>
                        </template>
                    </select>
                    <template x-if="selectedPenghuniDetail">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm space-y-1">
                            <p><strong>Nama:</strong> <span x-text="selectedPenghuniDetail.nama"></span></p>
                            <p><strong>No. HP:</strong> <span x-text="selectedPenghuniDetail.telepon"></span></p>
                            <p><strong>Email:</strong> <span x-text="selectedPenghuniDetail.email"></span></p>
                        </div>
                    </template>
                </div>

                <template x-if="passwordGenerated">
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                        <p class="font-semibold text-yellow-800">Password Sementara Penghuni Baru</p>
                        <div class="bg-white border rounded px-3 py-2 font-mono text-center" x-text="newPassword"></div>
                    </div>
                </template>
            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-2 bg-white">
                <button @click="openEditPenghuni = false" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Batal</button>
                <button @click="submitGantiPenghuni" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Pergantian</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL HAPUS UNIT ================= --}}
    <div x-show="openDelete" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openDelete = false" class="bg-white w-full max-w-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-red-600">Hapus Unit</h2>
            <p class="text-sm text-gray-600 mt-2">Apakah Anda yakin ingin menghapus unit <strong x-text="selectedUnit.no_unit"></strong>?</p>
            <p class="text-xs text-gray-500 mt-1">Data unit dan relasinya akan dihapus dari sistem.</p>
            <div class="flex justify-end gap-2 mt-6">
                <button @click="openDelete = false" class="px-4 py-2 border rounded-lg">Batal</button>
                <button @click="deleteUnit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Hapus</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL NONAKTIFKAN/AKTIFKAN ================= --}}
    <div x-show="openToggle" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openToggle = false" class="bg-white w-full max-w-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-red-600" x-text="toggleAction == 'nonaktif' ? 'Nonaktifkan Unit' : 'Aktifkan Unit'"></h2>
            <p class="text-sm text-gray-600 mt-2">Unit <strong x-text="selectedUnit.no_unit"></strong> akan <span x-text="toggleAction == 'nonaktif' ? 'dinonaktifkan' : 'diaktifkan'"></span>.</p>
            <div class="flex justify-end gap-2 mt-6">
                <button @click="openToggle = false" class="px-4 py-2 border rounded-lg">Batal</button>
                <button @click="submitToggle" class="px-4 py-2 text-white rounded-lg" :class="toggleAction == 'aktif' ? 'bg-green-600' : 'bg-red-600'">Konfirmasi</button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL RESET PASSWORD ================= --}}
    <div x-show="openReset" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openReset = false" class="bg-white w-full max-w-md rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-800">Reset Password Unit</h2>
            <p class="text-sm text-gray-600">Password login untuk unit <strong x-text="selectedUnit.no_unit"></strong> akan direset.</p>
            <template x-if="resetPasswordGenerated">
                <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                    <p class="font-semibold text-yellow-800">Password Sementara Baru</p>
                    <div class="bg-white border rounded px-3 py-2 font-mono text-center" x-text="newPassword"></div>
                    <p class="text-xs text-gray-600">Berikan password ini kepada penghuni unit untuk login kembali.</p>
                </div>
            </template>
            <div class="flex justify-end gap-2 pt-4">
                <button @click="openReset = false" class="px-4 py-2 border rounded-lg">Batal</button>
                <button @click="submitResetPassword" class="px-4 py-2 bg-purple-600 text-white rounded-lg">Reset Password</button>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL ALERT ================= --}}
<div x-show="alertMessage"
    x-transition.opacity.duration.200ms
     class="fixed inset-0 bg-black/30 flex items-center justify-center z-[100]"
     x-cloak>
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
        <p x-text="alertMessage" class="text-gray-800"></p>
        <div class="mt-4">
            <button @click="alertMessage = ''"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                OK
            </button>
        </div>
    </div>
</div>

<script>
function unitManager() {
    return {
        openCreateUnit: false,
        openEdit: false,
        openEditPenghuni: false,
        openDelete: false,
        openToggle: false,
        openReset: false,
        selectedUnit: { id: null, no_unit: '', gedung: '', lantai: '', currentPenghuni: '' },
        newUnit: { no_unit: '', gedung: '', lantai: '', nomor_kamar: '' },
        editForm: { gedung: '', lantai: '', nomor_kamar: '' },
        createdUnit: null,
        search: '',
        floorFilter: '',
        unitsData: [],
        filteredUnits: [],
        penghuniList: [],
        selectedPenghuniId: '',
        selectedPenghuniDetail: null,
        passwordGenerated: false,
        newPassword: '',
        toggleAction: '',
        resetPasswordGenerated: false,

        init(initialUnits) {
            this.unitsData = initialUnits;
            this.filteredUnits = initialUnits;
            this.fetchPenghuniList();

            window.addEventListener('edit-unit', e => this.editUnit(e.detail.id, e.detail.gedung, e.detail.lantai, e.detail.nomor_kamar));
            window.addEventListener('ganti-penghuni', e => this.openGantiPenghuni(e.detail.id, e.detail.no_unit, e.detail.currentPenghuni));
            window.addEventListener('reset-password', e => this.openResetPassword(e.detail.id, e.detail.no_unit));
            window.addEventListener('toggle-status', e => this.toggleStatus(e.detail.id, e.detail.no_unit, e.detail.action));
            window.addEventListener('confirm-delete', e => this.confirmDelete(e.detail.id, e.detail.no_unit));
        },

        applyFilter() {
            this.filteredUnits = this.unitsData.filter(unit => {
                let match = true;
                if (this.search) {
                    match = unit.no_unit.toLowerCase().includes(this.search.toLowerCase()) ||
                            unit.gedung.toLowerCase().includes(this.search.toLowerCase());
                }
                if (this.floorFilter && unit.lantai != this.floorFilter) {
                    match = false;
                }
                return match;
            });
        },

        resetFilter() {
            this.search = '';
            this.floorFilter = '';
            this.applyFilter();
        },

        fetchPenghuniList() {
            fetch('/penghuni-available')
                .then(res => res.json())
                .then(data => this.penghuniList = data)
                .catch(err => console.error(err));
        },

        fetchPenghuniDetail() {
            this.selectedPenghuniDetail = this.penghuniList.find(p => p.id == this.selectedPenghuniId) || null;
        },

        saveUnit() {
            fetch('/units', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.newUnit)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.createdUnit = data.unit;
                    this.createdUnit.password = data.password;
                    this.newUnit = { no_unit: '', gedung: '', lantai: '', nomor_kamar: '' };

                    Swal.fire({
                        icon: 'success',
                        title: 'Unit Berhasil Ditambahkan',
                        html: `Username: <b>${this.createdUnit.no_unit}</b><br>Password sementara: <b>${this.createdUnit.password}</b>`,
                        timer: 3000,
                        timerProgressBar: true,
                        didClose: () => location.reload()
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => console.error(err));
        },

        editUnit(id, gedung, lantai, nomor_kamar) {
            const unit = this.unitsData.find(u => String(u.id) === String(id));
            if (unit) {
                this.selectedUnit.id = id;
                this.selectedUnit.no_unit = unit.no_unit;
                this.selectedUnit.gedung = unit.gedung;
                this.selectedUnit.lantai = unit.lantai;
                this.selectedUnit.nomor_kamar = unit.nomor_kamar;

                // Pastikan editForm juga diperbarui
                this.editForm.gedung = unit.gedung;
                this.editForm.lantai = unit.lantai;
                this.editForm.nomor_kamar = unit.nomor_kamar;

                this.openEdit = true;
            }
        },

        updateUnit() {
            fetch(`/units/${this.selectedUnit.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.editForm)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Tutup modal
                    this.openEdit = false;

                    // Update unitsData
                    this.unitsData = this.unitsData.map(u => 
                        u.id === this.selectedUnit.id ? {...u, ...this.editForm} : u
                    );

                    // Update filteredUnits juga
                    this.applyFilter();

                    // **Update selectedUnit agar placeholder input edit baru**
                    this.selectedUnit.gedung = this.editForm.gedung;
                    this.selectedUnit.lantai = this.editForm.lantai;
                    this.selectedUnit.nomor_kamar = this.editForm.nomor_kamar;

                    Swal.fire({
                        icon: 'success',
                        title: 'Unit Berhasil Diperbarui',
                        timer: 2000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Terjadi error saat update', 'error'));
        },

        confirmDelete(id, no_unit) {
            this.selectedUnit.id = id;
            this.selectedUnit.no_unit = no_unit;
            Swal.fire({
                title: `Hapus Unit ${no_unit}?`,
                text: "Data unit dan relasinya akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) this.deleteUnit();
            });
        },

        deleteUnit() {
            fetch(`/units/${this.selectedUnit.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', 'Unit berhasil dihapus', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => console.error(err));
        },

        openGantiPenghuni(id, no_unit, currentPenghuni) {
            this.selectedUnit.id = id;
            this.selectedUnit.no_unit = no_unit;
            this.selectedUnit.currentPenghuni = currentPenghuni;
            const unit = this.unitsData.find(u => u.id == id);
            if (unit) {
                this.selectedUnit.gedung = unit.gedung;
                this.selectedUnit.lantai = unit.lantai;
            }
            this.selectedPenghuniId = '';
            this.selectedPenghuniDetail = null;
            this.passwordGenerated = false;
            this.newPassword = '';
            this.openEditPenghuni = true;
        },

        submitGantiPenghuni() {
            if (!this.selectedPenghuniId) {
                Swal.fire('Peringatan', 'Pilih penghuni baru terlebih dahulu', 'warning');
                return;
            }
            fetch(`/units/${this.selectedUnit.id}/change-occupant`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ penghuni_id: this.selectedPenghuniId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.passwordGenerated = true;
                    this.newPassword = data.new_password;

                    Swal.fire({
                        icon: 'success',
                        title: 'Penghuni Berhasil Diganti',
                        html: `Password sementara: <b>${this.newPassword}</b>`,
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => console.error(err));
        },

        openResetPassword(id, no_unit) {
            this.selectedUnit.id = id;
            this.selectedUnit.no_unit = no_unit;
            this.resetPasswordGenerated = false;
            this.newPassword = '';
            this.openReset = true;
        },

        submitResetPassword() {
            fetch(`/units/${this.selectedUnit.id}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.resetPasswordGenerated = true;
                    this.newPassword = data.new_password;

                    Swal.fire({
                        icon: 'success',
                        title: 'Password Reset',
                        html: `Password baru: <b>${this.newPassword}</b>`
                    });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => console.error(err));
        },

        toggleStatus(id, no_unit, action) {
            this.selectedUnit.id = id;
            this.selectedUnit.no_unit = no_unit;
            this.toggleAction = action;
            Swal.fire({
                title: `${action === 'nonaktif' ? 'Nonaktifkan' : 'Aktifkan'} Unit ${no_unit}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) this.submitToggle();
            });
        },

        submitToggle() {
            fetch(`/units/${this.selectedUnit.id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => console.error(err));
        }
    };
}

// Dropdown tetap sama
function dropdownMenu(data) {
    return {
        open: false,
        status: data.status,
        id: data.id,
        gedung: data.gedung,
        lantai: data.lantai,
        nomor_kamar: data.nomor_kamar,
        no_unit: data.no_unit,
        currentPenghuni: data.currentPenghuni,

        editUnit() { window.dispatchEvent(new CustomEvent('edit-unit', { detail: { id: this.id, gedung: this.gedung, lantai: this.lantai, nomor_kamar: this.nomor_kamar } })); this.open=false },
        gantiPenghuni() { window.dispatchEvent(new CustomEvent('ganti-penghuni', { detail: { id: this.id, no_unit: this.no_unit, currentPenghuni: this.currentPenghuni } })); this.open=false },
        resetPassword() { window.dispatchEvent(new CustomEvent('reset-password', { detail: { id: this.id, no_unit: this.no_unit } })); this.open=false },
        toggleStatus(action) { window.dispatchEvent(new CustomEvent('toggle-status', { detail: { id: this.id, no_unit: this.no_unit, action } })); this.open=false },
        confirmDelete() { window.dispatchEvent(new CustomEvent('confirm-delete', { detail: { id: this.id, no_unit: this.no_unit } })); this.open=false }
    };
}

if (typeof window.Alpine !== 'undefined') {
    window.Alpine.data('unitManager', unitManager);
    window.Alpine.data('dropdownMenu', dropdownMenu);
}
</script>
@endsection
