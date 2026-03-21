@extends('layouts.app')

@section('title', 'Kelola Karyawan')

@section('content')

<div x-data="{ 
    openEdit:false, 
    openDetail:false, 
    openCreate:false,
    openResetPassword:false,

    passwordGenerated:false,
    generatedPassword:'Tmp-9XK21',

    employees:[
        {
            id_pegawai:'EMP001',
            nama:'Widiawati Sihaloho',
            telp:'081234567890',
            email:'widiawati@email.com',
            departemen:'Engineering',
            gender:'Perempuan',
            status:'Aktif'
        },
        {
            id_pegawai:'EMP002',
            nama:'Budi Santoso',
            telp:'082233445566',
            email:'budi@email.com',
            departemen:'Tenant Relation',
            gender:'Laki-laki',
            status:'Aktif'
        },
        {
            id_pegawai:'EMP003',
            nama:'Siti Aminah',
            telp:'083344556677',
            email:'siti@email.com',
            departemen:'Admin',
            gender:'Perempuan',
            status:'Aktif'
        },
        {
            id_pegawai:'EMP004',
            nama:'Andi Saputra',
            telp:'081998877665',
            email:'andi@email.com',
            departemen:'Engineering',
            gender:'Laki-laki',
            status:'Nonaktif'
        },
        {
            id_pegawai:'EMP005',
            nama:'Rina Kartika',
            telp:'081223344556',
            email:'rina@email.com',
            departemen:'Tenant Relation',
            gender:'Perempuan',
            status:'Aktif'
        }
    ],

    newEmployee:{
        id_pegawai:'',
        nama:'', 
        telp:'', 
        email:'', 
        departemen:'',
        gender:'', 
        status:'Aktif'
    },

    selectedEmployee:{
        id_pegawai:'',
        nama:'', 
        telp:'', 
        email:'', 
        departemen:'',
        gender:'', 
        status:'Aktif'
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
                <option>Engineering</option>
                <option>Tenant Relation</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filter</button>
            <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Reset</button>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 text-sm">

            <thead class="bg-gray-100">
                <tr class="text-center">
                    <th class="px-4 py-2 border">ID Pegawai</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Departemen</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                <template x-for="emp in employees" :key="emp.id_pegawai">

                <tr class="hover:bg-gray-50 text-center">

                    <td class="px-4 py-2" x-text="emp.id_pegawai"></td>

                    <td class="px-4 py-2" x-text="emp.nama"></td>

                    <td class="px-4 py-2" x-text="emp.departemen"></td>

                    <!-- STATUS -->
                    <td class="px-4 py-2">

                        <span
                            x-show="emp.status === 'Aktif'"
                            class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                            Aktif
                        </span>

                        <span
                            x-show="emp.status === 'Nonaktif'"
                            class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                            Nonaktif
                        </span>

                    </td>

                    <!-- AKSI DROPDOWN -->
                    <td class="px-4 py-2 relative">

                        <div x-data="{open:false}" class="relative inline-block text-left">

                            <button
                                @click.stop="open=!open"
                                class="px-3 py-1.5 text-xs bg-gray-200 rounded hover:bg-gray-300 flex items-center gap-1">
                                Aksi
                                <span class="text-xs">▼</span>
                            </button>

                            <div
                                x-show="open"
                                x-cloak
                                x-transition
                                @click.outside="open=false"
                                class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-xl z-50">

                                <!-- DETAIL -->
                                <button
                                    @click="
                                        openDetail=true;
                                        selectedEmployee = emp;
                                        open=false
                                    "
                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm hover:bg-gray-100">
                                    👁 Detail
                                </button>

                                <!-- EDIT -->
                                <button
                                    @click="
                                        openEdit=true;
                                        selectedEmployee = {...emp};
                                        open=false
                                    "
                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm hover:bg-gray-100">
                                    ✏ Edit
                                </button>

                                <button
                                    @click="
                                        openResetPassword=true;
                                        selectedEmployee = emp;
                                        open=false
                                    "
                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm hover:bg-gray-100">
                                    🔑 Reset Password
                                </button>

                                <div class="border-t my-1"></div>

                                <!-- DELETE -->
                                <button
                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                    🗑 Hapus
                                </button>

                            </div>

                        </div>

                    </td>

                </tr>

                </template>

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
                    <input type="text" 
                        x-model="newEmployee.nama"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-sm font-medium">ID Pegawai</label>
                    <input type="text"
                        x-model="newEmployee.id_pegawai"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-sm font-medium">No. Telepon</label>
                    <input type="text" 
                        x-model="newEmployee.telp"
                        class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" 
                        x-model="newEmployee.email"
                        class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium">Departemen</label>
                    <select 
                        x-model="newEmployee.departemen"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Departemen</option>
                        <option>Admin</option>
                        <option>Engineering</option>
                        <option>Tenant Relation</option>

                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Jenis Kelamin</label>
                    <select 
                        x-model="newEmployee.gender"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Jenis Kelamin</option>
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>

                <!-- <div>
                    <label class="text-sm font-medium">Status</label>
                    <select class="w-full border rounded-lg px-3 py-2">
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                </div> -->
            </div>

            <template x-if="passwordGenerated">
                <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                    <p class="font-semibold text-yellow-800">
                        Akun Karyawan Berhasil Dibuat
                    </p>

                    <p>
                        <strong>ID Pegawai Login:</strong>
                        <span x-text="newEmployee.id_pegawai"></span>
                    </p>

                    <p>Password Sementara</p>

                    <div class="bg-white border rounded px-3 py-2 font-mono text-center">
                        <span x-text="generatedPassword"></span>
                    </div>

                    <p class="text-xs text-gray-600">
                        Berikan password ini kepada karyawan untuk login pertama.
                    </p>
                </div>
            </template>
            
            <div class="flex justify-end gap-2 pt-4 border-t">
            <button 
                type="button"
                @click="
                    passwordGenerated=true;
                    newEmployee={
                        id_pegawai:'',
                        nama:'',
                        telp:'',
                        email:'',
                        departemen:'',
                        gender:'',
                        status:'Aktif'
                    }
                    "
                class="px-4 py-2 bg-blue-600 text-white rounded-lg">
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
                    <label class="text-sm">ID Pegawai</label>
                    <input type="text"
                        x-model="selectedEmployee.id_pegawai"
                        readonly
                        class="w-full border rounded-lg px-3 py-2 bg-gray-100">
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
            <p><strong>ID Pegawai:</strong> <span x-text="selectedEmployee.id_pegawai"></span></p>
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

    {{-- ================= MODAL RESET PASSWORD ================= --}}
    <div x-show="openResetPassword" x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

        <div @click.outside="openResetPassword=false"
            class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 space-y-4">

            <h2 class="text-lg font-semibold">
                Reset Password
            </h2>

            <p class="text-sm text-gray-600">
                Reset password untuk karyawan:
            </p>

            <div class="bg-gray-50 border rounded-lg p-3 text-sm">
                <p><strong>Nama:</strong> <span x-text="selectedEmployee.nama"></span></p>
                <p><strong>ID Pegawai:</strong> <span x-text="selectedEmployee.id_pegawai"></span></p>
            </div>

            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                <p class="font-semibold text-yellow-800">
                    Password Baru
                </p>

                <div class="bg-white border rounded px-3 py-2 font-mono text-center">
                    <span x-text="generatedPassword"></span>
                </div>

                <p class="text-xs text-gray-600">
                    Berikan password ini kepada karyawan untuk login kembali.
                </p>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t">

                <button 
                    @click="openResetPassword=false"
                    class="px-4 py-2 border rounded-lg">
                    Batal
                </button>

                <button
                    @click="
                        generatedPassword = 'Tmp-' + Math.random().toString(36).substring(2,7).toUpperCase();
                    "
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Generate Password Baru
                </button>

            </div>

        </div>
    </div>

</div>

@endsection
