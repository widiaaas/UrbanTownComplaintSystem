@extends('layouts.app')

@section('title', 'Kelola Karyawan')

@section('content')

<div x-data="karyawanManager()" x-init='init(@json($karyawans))' class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Karyawan</h1>
        <button 
            @click="
                openCreate = true;

                // reset state
                passwordGenerated = false;
                generatedPassword = '';

                newEmployee = {
                    id_pegawai:'',
                    nama:'',
                    telp:'',
                    email:'',
                    departemen:'',
                    role:'',
                    gender:'',
                    status:'Aktif'
                };

                $nextTick(() => $refs.nama.focus());
            "
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
                <option value="">Pilih Departemen</option>
                            @foreach($departemens as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
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
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">ID Pegawai</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Departemen</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                <template x-for="(emp, index) in employees" :key="emp.id">

                <tr class="hover:bg-gray-50 text-center">
                    <td class="px-4 py-2" x-text="index + 1"></td>

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

                        <div x-data="dropdownMenu(emp)" class="relative inline-block text-left">

                        <button 
                            @click="toggle($event)"
                            class="px-3 py-1.5 text-xs bg-gray-200 rounded hover:bg-gray-300 flex items-center gap-1">
                            Aksi <span class="text-xs">▼</span>
                        </button>

                        <div
                        x-show="open"
                        x-cloak
                        x-transition
                        @click.outside="open = false"
                        x-ref="menu"
                        class="fixed w-44 bg-white border rounded-lg shadow-xl z-[9999]"
                        x-init="
                            $watch('open', value => {
                                if (value) {
                                    let rect = this.button.getBoundingClientRect();
                                    let dropdownHeight = 180;

                                    if ((rect.bottom + dropdownHeight) > window.innerHeight) {
                                        $el.style.top = (rect.top - dropdownHeight) + 'px';
                                    } else {
                                        $el.style.top = rect.bottom + 'px';
                                    }

                                    $el.style.left = (rect.right - 176) + 'px';
                                }
                            })
                        "
                    >

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
                                    @click="hapus(emp)"
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
    <div 
        x-show="openCreate" 
        x-cloak
        x-trap="openCreate"
        x-transition.scale.duration.200ms
        @click.self.stop
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">

        <div @click.outside.stop="openCreate=false"
            class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 space-y-4">

            <h2 class="text-lg font-semibold">Tambah Karyawan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" 
                        x-model="newEmployee.nama"
                        x-ref="nama"
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
                    <label class="text-sm font-medium">Role</label>
                    <select 
                        x-model="newEmployee.role"
                        @change="newEmployee.departemen = ''"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Role</option>
                        <option value="tenant_relation">Tenant Relation</option>
                        <option value="departemen">Departemen</option>

                    </select>
                </div>

                <div x-show="newEmployee.role === 'departemen'" x-transition.opacity.duration.200ms>
                    <label class="text-sm font-medium">Departemen</label>
                    <select 
                        x-model="newEmployee.departemen"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Departemen</option>
                        @foreach($departemens as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach

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

            </div>

            <template x-if="passwordGenerated">
                <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 text-sm space-y-2">
                    <p class="font-semibold text-yellow-800">
                        Akun Karyawan Berhasil Dibuat
                    </p>

                    <p>
                        <strong>Username:</strong>
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
                    @click="openCreate = false"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                    Batal
                </button>
            
                <button 
                    type="button"
                    @click="store()"
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
                    <label class="text-sm">Role</label>
                    <select 
                        x-model="selectedEmployee.role"
                        @change="selectedEmployee.departemen = ''"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Role</option>
                        <option value="tenant_relation">Tenant Relation</option>
                        <option value="departemen">Departemen</option>
                    </select>
                </div>

                <div x-show="selectedEmployee.role === 'departemen'" x-transition.opacity.duration.200ms>
                    <label class="text-sm">Departemen</label>
                    <select x-model="selectedEmployee.departemen"
                        class="w-full border rounded-lg px-3 py-2">
                        <option value="">Pilih Departemen</option>
                        @foreach($departemens as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
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
                <button 
                    @click="openEdit = false"
                    class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                    Batal
                </button>
                <button 
                    @click="update()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
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
                    @click="resetPassword(selectedEmployee)"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Generate Password Baru
                </button>

            </div>

        </div>
    </div>

</div>

<script>
function karyawanManager(){
    return{
        employees:[],
        openCreate:false,
        openEdit:false,
        openDetail:false,
        openResetPassword:false,

        passwordGenerated:false,
        generatedPassword:'',

        newEmployee:{
            id_pegawai:'',
            nama:'', 
            telp:'', 
            email:'', 
            departemen:'',
            role:'',
            gender:'', 
            status:'Aktif'
        },

        selectedEmployee:{},

        init(data){
            this.employees = data.map(e => ({
                ...e,
                id_pegawai: e.nip,
                departemen: e.departemen, // ✅ FIX
                gender: e.jenis_kelamin
            }));
        },
        showError(msg){
            this.openCreate = true;

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg
            }).then(() => {

                // 🔥 DELAY WAJIB
                setTimeout(() => {
                    this.openCreate = true;
                    this.$nextTick(() => this.$refs.nama.focus());
                }, 50);

            });
        },
        

        // ================= STORE =================
        store(){
            this.passwordGenerated = false;

            // ================= VALIDASI FRONTEND =================
            if(!this.newEmployee.id_pegawai){
                this.showError('ID Pegawai wajib diisi');
                return;
            }

            if(!this.newEmployee.nama){
                this.showError('Nama wajib diisi');
                return;
            }

            if(!this.newEmployee.telp){
                this.showError('No. Telepon wajib diisi');
                return;
            }

            if(!this.newEmployee.email){
                this.showError('Email wajib diisi');
                return;
            }

            if(!this.newEmployee.role){
                this.showError('Role wajib dipilih');
                return;
            }

            if(this.newEmployee.role === 'departemen' && !this.newEmployee.departemen){
                this.showError('Departemen wajib dipilih');
                return;
            }

            if(!this.newEmployee.gender){
                this.showError('Jenis kelamin wajib dipilih');
                return;
            }

            // ================= REQUEST =================
            fetch('/karyawan',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                },
                body:JSON.stringify({
                    nip: this.newEmployee.id_pegawai,
                    nama: this.newEmployee.nama,
                    telp: this.newEmployee.telp,
                    email: this.newEmployee.email,
                    departemen: this.newEmployee.departemen,
                    role: this.newEmployee.role,
                    jenis_kelamin: this.newEmployee.gender
                })
            })
            .then(async res => {
                if(!res.ok){
                    const err = await res.json();
                    throw err;
                }
                return res.json();
            })
            .then(res => {

                if(res.success){

                    this.passwordGenerated = true;
                    this.generatedPassword = res.akun.password;

                    Swal.fire({
                        icon: 'success',
                        title: 'Karyawan Berhasil Ditambahkan',
                        html: `
                            Username: <b>${res.akun.username}</b><br>
                            Password: <b>${res.akun.password}</b>
                        `,
                        confirmButtonText: 'OK',
                        allowOutsideClick: false, 
                        allowEscapeKey: false     
                    }).then(() => {

                        location.reload(); 

                    });

                    this.newEmployee = {
                        id_pegawai:'',
                        nama:'',
                        telp:'',
                        email:'',
                        role:'',
                        departemen:'',
                        gender:'',
                        status:'Aktif'
                    };

                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menyimpan','error');
                }
                })
                .catch(err => {

                    console.error(err);

                    let fieldNames = {
                        nip: 'ID Pegawai',
                        nama: 'Nama',
                        telp: 'No. Telepon',
                        email: 'Email',
                        departemen: 'Departemen',
                        jenis_kelamin: 'Jenis Kelamin'
                    };

                    let message = 'Terjadi kesalahan';

                    if(err.errors){
                        message = Object.entries(err.errors)
                            .map(([field, msgs]) => {
                                let label = fieldNames[field] || field;
                                return `${label}: ${msgs.join(', ')}`;
                            })
                            .join('\n');
                    }

                    // 🔥 GUNAKAN showError (JANGAN Swal langsung)
                    this.showError(message);

                    });
                                    
        },

        // ================= UPDATE =================
        update(){
            fetch(`/karyawan/${this.selectedEmployee.id}`,{
                method:'PUT',
                credentials:'same-origin',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                },
                body:JSON.stringify({
                    nama: this.selectedEmployee.nama,
                    telp: this.selectedEmployee.telp,
                    email: this.selectedEmployee.email,
                    departemen: this.selectedEmployee.departemen,
                    jenis_kelamin: this.selectedEmployee.gender,
                    status: this.selectedEmployee.status
                })
            })
            .then(res=>res.json())
            .then(res=>{
                if(res.success){
                    Swal.fire('Berhasil','Data diperbarui','success')
                    .then(()=> location.reload());
                } else {
                    Swal.fire('Error','Gagal update','error');
                }
            })
            .catch(()=>{
                Swal.fire('Error','Terjadi kesalahan server','error');
            });
        },

        // ================= DELETE =================
        hapus(emp){
            Swal.fire({
                title: 'Hapus?',
                text: 'Data tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result=>{
                if(!result.isConfirmed) return;

                fetch(`/karyawan/${emp.id}`,{
                    method:'DELETE',
                    credentials:'same-origin',
                    headers:{
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res=>res.json())
                .then(res=>{
                    if(res.success){
                        Swal.fire('Berhasil','Data dihapus','success')
                        .then(()=> location.reload());
                    } else {
                        Swal.fire('Error','Gagal hapus','error');
                    }
                });
            });
        },

        // ================= RESET PASSWORD =================
        resetPassword(emp){
            Swal.fire({
                title: 'Reset Password?',
                text: emp.nama,
                icon: 'warning',
                showCancelButton: true
            }).then(result=>{
                if(!result.isConfirmed) return;

                fetch(`/karyawan/${emp.id}/reset-password`,{
                    method:'POST',
                    credentials:'same-origin',
                    headers:{
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res=>res.json())
                .then(res=>{
                    if(res.success){
                        this.generatedPassword = res.new_password;

                        Swal.fire({
                            icon:'success',
                            title:'Password Baru',
                            html:`<b>${res.new_password}</b>`
                        });
                    } else {
                        Swal.fire('Error','Gagal reset password','error');
                    }
                });
            });
        }
    }
}

function dropdownMenu(emp){
    return {
        open:false,
        button:null,

        toggle(event){
            this.open = !this.open;
            this.button = event.currentTarget;
        }
    }
}
</script>
@endsection
