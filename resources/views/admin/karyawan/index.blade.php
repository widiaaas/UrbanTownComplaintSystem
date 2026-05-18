@extends('layouts.app')

@section('title', 'Kelola Karyawan')

@section('content')

<div x-data="karyawanManager()" x-init='init(@json($karyawans))' class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Karyawan</h1>
        <button 
            @click="openCreateModal"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Tambah Karyawan
        </button>
    </div>

    {{-- ================= FILTER ================= --}}
    <div
        class="bg-white rounded-lg shadow p-4
        flex flex-col md:flex-row gap-4">

        {{-- SEARCH --}}
        <div class="flex-1">

            <label
                class="text-sm font-medium text-gray-700">

                Cari Karyawan

            </label>

            <input
                type="text"
                x-model="search"
                placeholder="Cari nama atau ID pegawai..."
                class="w-full mt-1 border rounded-lg px-3 py-2
                focus:ring focus:ring-blue-200">

        </div>

        {{-- FILTER ROLE --}}
        <div class="md:w-72">

            <label
                class="text-sm font-medium text-gray-700">

                Peran

            </label>

            <select
                x-model="roleFilter"
                class="w-full mt-1 border rounded-lg px-3 py-2
                bg-white focus:ring focus:ring-blue-200">

                <option value="">
                    Semua
                </option>

                <option value="tenant_relation">
                    Tenant Relation
                </option>

                @foreach($departemens as $dept)

                    <option value="{{ $dept->nama_departemen }}">

                        {{ $dept->nama_departemen }}

                    </option>

                @endforeach

            </select>

        </div>

    </div>

    </form>
    {{-- ================= TABLE ================= --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 text-sm">

            <thead class="bg-gray-100">
                <tr class="text-center">
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">ID Pegawai</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Role</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

            {{-- DATA TIDAK ADA --}}
            <template x-if="employees.length === 0">

                <tr>
                    <td
                        colspan="6"
                        class="px-4 py-4 text-center text-gray-400 italic">
                            Data karyawan tidak tersedia
                    </td>
                </tr>

            </template>

            {{-- DATA ADA --}}
            <template
                x-for="(emp, index) in filteredEmployees"
                :key="emp.id"
            >

                <tr class="hover:bg-gray-50 text-center">

                    <td class="px-4 py-2" x-text="index + 1"></td>

                    <td class="px-4 py-2" x-text="emp.id_pegawai"></td>

                    <td class="px-4 py-2" x-text="emp.nama"></td>

                    <td
                        class="px-4 py-2"
                        x-text="
                            emp.role === 'tenant_relation'
                                ? 'Tenant Relation'
                                : emp.departemen?.nama_departemen
                        "
                    ></td>

                    {{-- STATUS --}}
                    <td class="px-4 py-2">

                        <span
                            x-show="emp.status === 'Aktif'"
                            class="px-2 py-1 text-xs rounded bg-green-100 text-green-700"
                        >
                            Aktif
                        </span>

                        <span
                            x-show="emp.status === 'Nonaktif'"
                            class="px-2 py-1 text-xs rounded bg-red-100 text-red-700"
                        >
                            Nonaktif
                        </span>

                    </td>

                    {{-- AKSI --}}
                    <td class="px-4 py-2 relative">

                        <div
                            x-data="dropdownMenu(emp)"
                            class="relative inline-block text-left"
                        >

                            <button 
                                @click="toggle($event)"
                                class="px-3 py-1.5 text-xs bg-gray-200 rounded hover:bg-gray-300 flex items-center gap-1"
                            >
                                Aksi
                                <span class="text-xs">▼</span>
                            </button>

                            <!-- Dropdown -->
                            <div
                                x-show="open"
                                x-cloak
                                x-transition
                                @click.outside="open = false"
                                x-ref="menu"
                                class="fixed w-44 bg-white border border-gray-200 rounded-xl shadow-xl z-[9999] overflow-hidden">

                                <div class="py-1">

                                    {{-- DETAIL --}}
                                    <button
                                        @click="
                                            openDetail = true;
                                            selectedEmployee = emp;
                                            open = false
                                        "
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition">

                                        <span>👁️</span>

                                        <span>Detail Karyawan</span>

                                    </button>

                                    {{-- EDIT --}}
                                    <button
                                        @click="
                                            openEdit = true;
                                            selectedEmployee = {...emp};
                                            open = false
                                        "
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition">

                                        <span>✏️</span>

                                        <span>Edit Karyawan</span>

                                    </button>

                                    {{-- RESET PASSWORD --}}
                                    <button
                                        @click="
                                            openResetPassword = true;
                                            selectedEmployee = emp;
                                            open = false
                                        "
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition">

                                        <span>🔑</span>

                                        <span>Reset Kata Sandi</span>

                                    </button>

                                </div>

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
                        x-model="newEmployee.no_telepon"
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
                        @change="newEmployee.departemen_id = ''"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Role</option>
                        <option value="tenant_relation">Tenant Relation</option>
                        <option value="departemen">Departemen</option>

                    </select>
                </div>

                <div x-show="newEmployee.role === 'departemen'" x-transition.opacity.duration.200ms>
                    <label class="text-sm font-medium">Departemen</label>
                    <select 
                        x-model="newEmployee.departemen_id"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Departemen</option>
                        @foreach($departemens as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Jenis Kelamin</label>
                    <select 
                        x-model="newEmployee.jenis_kelamin"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Jenis Kelamin</option>
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>

            </div>

            
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

        <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 space-y-4">

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
                        class="w-full border rounded-lg px-3 py-2">
                </div>   

                <div>
                    <label class="text-sm">No. Telepon</label>
                    <input type="text" x-model="selectedEmployee.no_telepon"
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
                        @change="selectedEmployee.departemen_id = ''"
                        class="w-full border rounded-lg px-3 py-2">

                        <option value="">Pilih Role</option>
                        <option value="tenant_relation">Tenant Relation</option>
                        <option value="departemen">Departemen</option>
                    </select>
                </div>

                <div x-show="selectedEmployee.role === 'departemen'" x-transition.opacity.duration.200ms>
                    <label class="text-sm">Departemen</label>
                    <select x-model="selectedEmployee.departemen_id"
                        class="w-full border rounded-lg px-3 py-2">
                        <option value="">Pilih Departemen</option>
                        @foreach($departemens as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->nama_departemen }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm">Jenis Kelamin</label>
                    <select x-model="selectedEmployee.jenis_kelamin"
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
            <p><strong>No. Telp:</strong> <span x-text="selectedEmployee.no_telepon"></span></p>
            <p><strong>Email:</strong> <span x-text="selectedEmployee.email"></span></p>
            <p><strong>Role:</strong>

            <span
                x-text="
                    selectedEmployee.role === 'tenant_relation'
                            ? 'Tenant Relation'
                            : selectedEmployee.departemen_id?.nama_departemen
                ">
            </span>
        </p>
            <p><strong>Jenis Kelamin:</strong> <span x-text="selectedEmployee.jenis_kelamin"></span></p>
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
        openCredential:false,
        search:'',
        roleFilter:'',

        credentialData:{
            username:'',
            password:''
        },

        credentialTitle:'',
        credentialDescription:'',

        passwordGenerated:false,
        generatedPassword:'',

        newEmployee:{
            id_pegawai:'',
            nama:'', 
            no_telepon:'', 
            email:'', 
            departemen_id:'',
            role:'',
            jenis_kelamin:'', 
            status:'Aktif'
        },

        selectedEmployee:{},

        init(data){
            this.employees = data.map(e => ({
                ...e,
                id_pegawai: e.nip,
                role: e.pengguna?.role,
                departemen: e.departemen??null,
                departemen_id: e.departemen_id,
                jenis_kelamin: e.jenis_kelamin
            }));
        },
        
        get filteredEmployees(){

            return this.employees.filter(emp => {

                // Searcg
                const keyword =
                    this.search.toLowerCase();

                const matchSearch =

                    (emp.nama || '')
                        .toLowerCase()
                        .includes(keyword)

                    ||

                    (emp.id_pegawai || '')
                        .toLowerCase()
                        .includes(keyword)

                    ||

                    (emp.email || '')
                        .toLowerCase()
                        .includes(keyword);

                
                 // Filter role 
                let roleName = '';

                if(emp.role === 'tenant_relation') { roleName = 'tenant_relation';

                } else {

                    roleName =
                        emp.departemen
                            ?.nama_departemen || '';
                }

                const matchRole =

                    !this.roleFilter ||

                    roleName === this.roleFilter;

                return matchSearch && matchRole;
            });
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
        openCreateModal(){
            this.openCreate = true;

            this.passwordGenerated = false;
            this.generatedPassword = '';

            this.newEmployee = {
                id_pegawai:'',
                nama:'',
                no_telepon:'',
                email:'',
                departemen_id:'',
                role:'',
                jenis_kelamin:'',
                status:'Aktif'
            };

            this.$nextTick(() => {
                this.$refs.nama?.focus();
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

            if(!this.newEmployee.no_telepon){
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

            if(this.newEmployee.role === 'departemen' && !this.newEmployee.departemen_id){
                this.showError('Departemen wajib dipilih');
                return;
            }

            if(!this.newEmployee.jenis_kelamin){
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
                    no_telepon: this.newEmployee.no_telepon,
                    email: this.newEmployee.email,
                    departemen_id: this.newEmployee.departemen_id,
                    role: this.newEmployee.role,
                    jenis_kelamin: this.newEmployee.jenis_kelamin
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
                        no_telepon:'',
                        email:'',
                        role:'',
                        departemen_id:'',
                        jenis_kelamin:'',
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
                        no_telepon: 'No. Telepon',
                        email: 'Email',
                        departemen_id: 'Departemen',
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
                    nip: this.selectedEmployee.id_pegawai,
                    nama: this.selectedEmployee.nama,
                    no_telepon: this.selectedEmployee.no_telepon,
                    email: this.selectedEmployee.email,
                    departemen_id: this.selectedEmployee.departemen_id,
                    role: this.selectedEmployee.role,
                    jenis_kelamin: this.selectedEmployee.jenis_kelamin,
                    status: this.selectedEmployee.status
                })
            })
            .then(async res => {

                const data = await res.json();

                if(!res.ok){
                    throw data;
                }

                return data;
            })
            .then(res => {

                Swal.fire({
                    icon:'success',
                    title:'Berhasil',
                    text:'Data berhasil diperbarui'
                }).then(() => {
                    location.reload();
                });

            })
            .catch(err => {

                console.error(err);

                let fieldNames = {
                    nip: 'ID Pegawai',
                    nama: 'Nama',
                    no_telepon: 'No. Telepon',
                    email: 'Email',
                    departemen_id: 'Departemen',
                    jenis_kelamin: 'Jenis Kelamin'
                };

                let message = 'Gagal update';

                if(err.errors){
                    message = Object.entries(err.errors)
                        .map(([field, msgs]) => {
                            let label = fieldNames[field] || field;
                            return `${label}: ${msgs.join(', ')}`;
                        })
                        .join('\n');
                }
                else if(err.message){
                    message = err.message;
                }

                // 🔥 modal tetap terbuka
                this.openEdit = true;

                Swal.fire({
                    icon:'error',
                    title:'Error',
                    text:message
                });
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
