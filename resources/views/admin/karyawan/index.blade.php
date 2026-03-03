@extends('layouts.app')

@section('title', 'Kelola Karyawan')

@section('content')

<div x-data="{ 
        openEdit:false, 
        openDetail:false, 
        openCreate:false,
        selectedEmployee:{
            nama:'', telp:'', email:'', departemen:'',
            gender:'', status:'Aktif'
        }
    }" 
    class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Karyawan</h1>
        <button 
            @click="openCreate = true"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Tambah Karyawan
        </button>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari Nama</label>
            <input type="text" 
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-700">Departemen</label>
            <select class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                <option value="">Semua</option>
                <option>Admin</option>
                <option>Departemen</option>
                <option>Tenant Relation</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filter</button>
            <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Reset</button>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Nama</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Departemen</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Status</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 text-center align-middle">
                    <td class="px-4 py-2">Widiawati Sihaloho</td>
                    <td class="px-4 py-2">Engineering</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
                    </td>
                    <td class="px-4 py-2 flex justify-center gap-1 flex-wrap">
                    <button
                        @click="
                            openDetail=true;
                            selectedEmployee={
                                nama:'Widiawati Sihaloho',
                                telp:'081234567890',
                                email:'widiawati@email.com',
                                departemen:'Engineering',
                                gender:'Perempuan',
                                status:'Aktif'
                            }
                        "
                        class="px-2 py-1 bg-blue-400 text-white rounded text-xs">
                        Detail
                    </button>

                    <button
                        @click="
                            openEdit=true;
                            selectedEmployee={
                                nama:'Widiawati Sihaloho',
                                telp:'081234567890',
                                email:'widiawati@email.com',
                                departemen:'Engineering',
                                gender:'Perempuan',
                                status:'Aktif'
                            }
                        "
                        class="px-2 py-1 bg-yellow-400 text-white rounded text-xs">
                        Edit
                    </button>
                        <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">Hapus</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50 text-center align-middle">
                    <td class="px-4 py-2">Budi Santoso</td>
                    <td class="px-4 py-2">Tenant Relation</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
                    </td>
                    <td class="px-4 py-2 flex justify-center gap-1 flex-wrap">
                        <button class="px-2 py-1 bg-blue-400 text-white rounded hover:bg-blue-500 text-xs"
                            @click="openDetail=true; selectedEmployee.nama">Detail</button>
                        <button class="px-2 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-xs"
                            @click="openEdit=true; selectedEmployee.nama">Edit</button>
                        <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ================= MODAL TAMBAH KARYAWAN ================= --}}
    <div x-show="openCreate" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

        <div @click.outside="openCreate=false"
            class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 space-y-4">

            <h2 class="text-lg font-semibold">Tambah Karyawan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">No. Telepon</label>
                    <input type="text" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Departemen</label>
                    <select class="w-full border rounded-lg px-3 py-2">
                        <option>Admin</option>
                        <option>Departemen</option>
                        <option>Tenant Relation</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Jenis Kelamin</label>
                    <select class="w-full border rounded-lg px-3 py-2">
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select class="w-full border rounded-lg px-3 py-2">
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t">
                <button @click="openCreate=false"
                    class="px-4 py-2 border rounded-lg">Batal</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EDIT KARYAWAN ================= --}}
    <div x-show="openEdit" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

        <div @click.outside="openEdit=false"
            class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 space-y-4">

            <h2 class="text-lg font-semibold">
                Edit Karyawan (<span x-text="selectedEmployee.nama"></span>)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm">Nama</label>
                    <input type="text" x-model="selectedEmployee.nama"
                        class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">No. Telepon</label>
                    <input type="text" x-model="selectedEmployee.telp"
                        class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">Email</label>
                    <input type="email" x-model="selectedEmployee.email"
                        class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">Departemen</label>
                    <select x-model="selectedEmployee.departemen"
                        class="w-full border rounded-lg px-3 py-2">
                        <option>Admin</option>
                        <option>Departemen</option>
                        <option>Tenant Relation</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm">Jenis Kelamin</label>
                    <select x-model="selectedEmployee.gender"
                        class="w-full border rounded-lg px-3 py-2">
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm">Status</label>
                    <select x-model="selectedEmployee.status"
                        class="w-full border rounded-lg px-3 py-2">
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t">
                <button @click="openEdit=false"
                    class="px-4 py-2 border rounded-lg">Batal</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL DETAIL KARYAWAN ================= --}}
    <div x-show="openDetail" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

        <div @click.outside="openDetail=false"
            class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-3">

            <h2 class="text-lg font-semibold">Detail Karyawan</h2>

            <p><strong>Nama:</strong> <span x-text="selectedEmployee.nama"></span></p>
            <p><strong>No. Telp:</strong> <span x-text="selectedEmployee.telp"></span></p>
            <p><strong>Email:</strong> <span x-text="selectedEmployee.email"></span></p>
            <p><strong>Departemen:</strong> <span x-text="selectedEmployee.departemen"></span></p>
            <p><strong>Jenis Kelamin:</strong> <span x-text="selectedEmployee.gender"></span></p>
            <p><strong>Status:</strong> <span x-text="selectedEmployee.status"></span></p>

            <div class="flex justify-end pt-4">
                <button @click="openDetail=false"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

@endsection
