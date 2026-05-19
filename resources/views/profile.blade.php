@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div x-data="profileData()" x-init="init()" class="p-6 max-w-xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">Profile Saya</h1>

    <div class="bg-white rounded-lg shadow">

        {{-- TAB --}}
        <div class="flex border-b">
            <button @click="tab='profil'" :class="tab==='profil' ? activeTab : normalTab">Profil</button>
            <button @click="tab='password'" :class="tab==='password' ? activeTab : normalTab">Ubah Kata Sandi</button>
        </div>

        {{-- ================= PROFIL ================= --}}
        <div x-show="tab==='profil'" class="p-6 space-y-4">

        <template x-if="!editMode">
            <div class="space-y-3">

                {{-- USERNAME --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Username</span>
                    <span x-text="user.username"></span>
                </div>

                {{-- NAMA --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Nama</span>
                    <span x-text="form.nama"></span>
                </div>

                {{-- NIK (HANYA PENGHUNI) --}}
                <template x-if=" user.role === 'unit'">
                    <div class="flex justify-between">
                        <span class="font-semibold">NIK</span>
                        <span x-text="form.nik"></span>
                    </div>
                </template>

                {{-- EMAIL --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Email</span>
                    <span x-text="form.email"></span>
                </div>

                {{-- TELEPON --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Nomor Telepon</span>
                    <span x-text="form.no_telepon"></span>
                </div>

                {{-- DEPARTEMEN (HANYA KARYAWAN) --}}
                <template x-if=" user.role === 'admin' || user.role === 'tenant_relation' || user.role === 'departemen'">
                    <div class="flex justify-between">
                        <span class="font-semibold">Jabatan</span>
                        <span x-text="form.jabatan"></span>
                    </div>
                </template>

                {{-- JENIS KELAMIN --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Jenis Kelamin</span>
                    <span x-text="form.jenis_kelamin"></span>
                </div>

                {{-- STATUS --}}
                <div class="flex justify-between">
                    <span class="font-semibold">
                        Status
                    </span>
                    <span
                        :class="form.nama === 'Belum ada penghuni'? 'text-red-600': 'text-green-600'"
                        x-text="form.nama === 'Belum ada penghuni'? 'Kosong': 'Aktif'">
                    </span>
                </div>

                {{-- BUTTON --}}
                <div class="text-right">
                    <button
                        class="btn btn-warning"
                        @click="editMode=true"
                        :disabled="
                            user.role === 'unit' &&
                            form.nama === 'Belum ada penghuni'
                        "
                        :class="
                            user.role === 'unit' &&
                            form.nama === 'Belum ada penghuni'
                            ? 'opacity-50 cursor-not-allowed'
                            : ''
                        ">
                        Edit
                    </button>
                </div>
            </div>
        </template>

            {{-- EDIT --}}
            <template x-if="editMode">
                <div class="space-y-4">

                <div>
                    <label class="block text-sm font-semibold mb-1">Nama</label>
                    <input x-model="form.nama" class="input w-full">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">NIK</label>
                    <input x-model="form.nik" class="input w-full">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Email</label>
                    <input type="email" x-model="form.email"class="input w-full">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Telepon</label>
                    <input type="tel" x-model="form.no_telepon" class="input w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Jenis Kelamin</label>
                    <select x-model="form.jenis_kelamin" class="input w-full">
                        <option value="">Pilih</option>
                        <template x-for="item in options.jenis_kelamin" :key="item">
                            <option :value="item" x-text="item"></option>
                        </template>
                    </select>
                </div>

                    <div class="flex justify-end gap-2">
                        <button @click="editMode=false" class="btn">Batal</button>
                        <button @click="updateProfile()" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </template>

        </div>

        {{-- ================= PASSWORD ================= --}}
        <div x-show="tab==='password'" class="p-6 space-y-4">

            <!-- PASSWORD LAMA -->
            <div>
                <label class="block text-sm font-semibold mb-1">Kata Sandi Lama</label>

                <div class="relative">
                    <input 
                        :type="showPassword.lama ? 'text' : 'password'"
                        x-model="password.password_lama" 
                        class="input w-full pr-12"
                    >

                    <button 
                        type="button"
                        @click="showPassword.lama = !showPassword.lama"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                    >
                        <template x-if="!showPassword.lama">
                            @include('components.icons.eye')
                        </template>

                        <template x-if="showPassword.lama">
                            @include('components.icons.eyeSlash')
                        </template>
                    </button>
                </div>
            </div>

            <!-- PASSWORD BARU -->
            <div>
                <label class="block text-sm font-semibold mb-1">Kata Sandi Baru</label>

                <div class="relative">
                    <input 
                        :type="showPassword.baru ? 'text' : 'password'"
                        x-model="password.password_baru" 
                        class="input w-full pr-12"
                    >

                    <button 
                        type="button"
                        @click="showPassword.baru = !showPassword.baru"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                    >
                        <template x-if="!showPassword.baru">
                            @include('components.icons.eye')
                        </template>

                        <template x-if="showPassword.baru">
                            @include('components.icons.eyeSlash')
                        </template>
                    </button>
                </div>
            </div>

            <!-- KONFIRMASI PASSWORD -->
            <div>
                <label class="block text-sm font-semibold mb-1">Konfirmasi Kata Sandi</label>

                <div class="relative">
                    <input 
                        :type="showPassword.konfirmasi ? 'text' : 'password'"
                        x-model="password.password_baru_confirmation" 
                        class="input w-full pr-12"
                    >

                    <button 
                        type="button"
                        @click="showPassword.konfirmasi = !showPassword.konfirmasi"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                    >
                        <template x-if="!showPassword.konfirmasi">
                            @include('components.icons.eye')
                        </template>

                        <template x-if="showPassword.konfirmasi">
                            @include('components.icons.eyeSlash')
                        </template>
                    </button>
                </div>
            </div>

            <div class="text-right">
                <button @click="updatePassword()" class="btn btn-primary">
                    Simpan Kata Sandi
                </button>
            </div>

        </div>


        </div>

    </div>
</div>

<script>
function profileData(){
    return {
        tab:'profil',
        editMode:false,
        errors:{},

        activeTab:'px-4 py-3 border-b-2 border-yellow-500 text-yellow-600',
        normalTab:'px-4 py-3 text-gray-500',

        form:{
            nama:'',
            nik:'',
            email:'',
            no_telepon:'',
            jabatan:'',
            jenis_kelamin:''
        },

        password:{
            password_lama:'',
            password_baru:'',
            password_baru_confirmation:''
        },

        showPassword:{
            lama:false,
            baru:false,
            konfirmasi:false
        },

        options:{
            departemen:[],
            jenis_kelamin:[]
        },
        user:{},

        // ================= INIT (FETCH DATA) =================
        async init(){
            try{
                const res = await fetch('/profile/show', {
                    headers:{
                        'Accept':'application/json'
                    }
                });

                const data = await res.json();

                // isi options
                this.user = data.user;
                this.options.departemen = data.options?.departemen ?? [];
                this.options.jenis_kelamin = data.options?.jenis_kelamin ?? [];

                // jika karyawan
                if(data.user.role === 'admin' ||data.user.role === 'tenant_relation' ||data.user.role === 'departemen'){
                    const p = data.profile;

                    this.form.nama = p?.nama ?? '';
                    this.form.email = p?.email ?? '';
                    this.form.no_telepon = p?.no_telepon ?? '';
                    this.form.jenis_kelamin = p?.jenis_kelamin ?? '';

                    // 🔥 mapping role ke label
                    if(data.user.role === 'admin'){
                        this.form.jabatan = 'Admin';
                    }
                    else if(data.user.role === 'tenant_relation'){
                        this.form.jabatan = 'Tenant Relation';
                    }
                    else if(data.user.role === 'departemen'){
                        this.form.jabatan =
                            p?.departemen?.nama_departemen ?? 'Departemen';
                    }
                }

                // jika unit
                // jika unit
                if(data.user.role === 'unit'){
                    const unit = data.profile;
                    const penghuni = unit?.penghuni;
                    this.user.username =unit?.nomor_unit ?? '-';
                    this.form.nama = penghuni?.nama ??'Belum ada penghuni';
                    this.form.nik =penghuni?.nik ?? '-';
                    this.form.email =penghuni?.email ?? '-';
                    this.form.no_telepon =penghuni?.no_telepon ?? '-';
                    this.form.jenis_kelamin =penghuni?.jenis_kelamin ?? '-';
                }
            }catch(err){
                console.error(err);
            }
        },

        // ================= UPDATE PROFILE =================
        async updateProfile(){

            this.errors = {};

            const confirm =
                await Swal.fire({

                    title: 'Simpan perubahan?',

                    text: 'Perubahan profil akan diperbarui',

                    icon: 'question',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Simpan',

                    cancelButtonText: 'Batal',

                    confirmButtonColor: '#2563eb',

                    cancelButtonColor: '#6b7280',
                });

            if(!confirm.isConfirmed){
                return;
            }

            try{

                const res = await fetch('/profile/update',{

                    method:'PUT',

                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },

                    body:JSON.stringify(this.form)
                });

                const data = await res.json();

                if(!res.ok){
                    throw data;
                }

                Swal.fire(
                    'Berhasil',
                    'Profil berhasil diperbarui',
                    'success'
                );

                this.editMode = false;

            }catch(err){

                let message = 'Terjadi kesalahan';

                if(err.errors){

                    message =
                        Object.values(err.errors)
                            .flat()
                            .join('\n');

                    this.errors = err.errors;
                }

                Swal.fire(
                    'Error',
                    message,
                    'error'
                );
            }
            },

        // ================= UPDATE PASSWORD =================
        async updatePassword(){

            this.errors = {};

            const confirm =
                await Swal.fire({

                    title: 'Ubah kata sandi?',

                    text: 'Kata sandi akun akan diperbarui',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Ubah',

                    cancelButtonText: 'Batal',

                    confirmButtonColor: '#d97706',

                    cancelButtonColor: '#6b7280',
                });

            if(!confirm.isConfirmed){
                return;
            }

            try{

                const res = await fetch(
                    '/profile/update-password',
                {

                    method:'PUT',

                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },

                    body:JSON.stringify(this.password)
                });

                const data = await res.json();

                if(!res.ok){
                    throw data;
                }

                Swal.fire(
                    'Berhasil',
                    'Password berhasil diubah',
                    'success'
                );

                this.password = {

                    password_lama:'',

                    password_baru:'',

                    password_baru_confirmation:''
                };

            }catch(err){

                let message = 'Terjadi kesalahan';

                if(err.errors){

                    message =
                        Object.values(err.errors)
                            .flat()
                            .join('\n');
                }

                Swal.fire(
                    'Error',
                    message,
                    'error'
                );
            }
            }
    }
}
</script>

@endsection