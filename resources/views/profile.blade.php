@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div x-data="profileData()" x-init="init()" class="p-6 max-w-xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">Profile Saya</h1>

    <div class="bg-white rounded-lg shadow">

        {{-- TAB --}}
        <div class="flex border-b">
            <button @click="tab='profil'" :class="tab==='profil' ? activeTab : normalTab">Profil</button>
            <button @click="tab='password'" :class="tab==='password' ? activeTab : normalTab">Ubah Password</button>
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

                {{-- EMAIL --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Email</span>
                    <span x-text="form.email"></span>
                </div>

                {{-- TELEPON --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Nomor Telepon</span>
                    <span x-text="form.telp"></span>
                </div>

                {{-- DEPARTEMEN (HANYA KARYAWAN) --}}
                <template x-if="user.role === 'karyawan'">
                    <div class="flex justify-between">
                        <span class="font-semibold">Departemen</span>
                        <span x-text="form.departemen"></span>
                    </div>
                </template>

                {{-- JENIS KELAMIN --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Jenis Kelamin</span>
                    <span x-text="form.jenis_kelamin"></span>
                </div>

                {{-- STATUS --}}
                <div class="flex justify-between">
                    <span class="font-semibold">Status</span>
                    <span class="text-green-600">Aktif</span>
                </div>

                {{-- BUTTON --}}
                <div class="text-right">
                    <button class="btn btn-warning" @click="editMode=true">
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
                    <label class="block text-sm font-semibold mb-1">Email</label>
                    <input x-model="form.email" class="input w-full">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Telepon</label>
                    <input x-model="form.telp" class="input w-full">
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
                <label class="block text-sm font-semibold mb-1">Password Lama</label>

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
                <label class="block text-sm font-semibold mb-1">Password Baru</label>

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
                <label class="block text-sm font-semibold mb-1">Konfirmasi Password</label>

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
                    Simpan Password
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
            email:'',
            telp:'',
            departemen:'',
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
                const res = await fetch('/profile/me', {
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
                if(data.user.role === 'karyawan'){
                    const p = data.profile;

                    this.form.nama = p?.nama ?? '';
                    this.form.email = p?.email ?? '';
                    this.form.telp = p?.telp ?? '';
                    this.form.jenis_kelamin = p?.jenis_kelamin ?? '';

                    // 🔥 mapping role ke label
                    if(p?.role === 'admin'){
                        this.form.departemen = 'Admin';
                    }
                    else if(p?.role === 'tenant_relation'){
                        this.form.departemen = 'Tenant Relation';
                    }
                    else if(p?.role === 'departemen'){
                        this.form.departemen = p?.departemen ?? '';
                    }
                }

                // jika unit
                if(data.user.role === 'unit'){
                const unit = data.profile;
                const penghuni = unit?.penghuni;

                // 🔥 username = no unit
                this.user.username = unit?.no_unit ?? '-';

                // 🔥 ambil dari penghuni aktif
                this.form.nama = penghuni?.nama ?? 'Belum ada penghuni';
                this.form.email = penghuni?.email ?? '-';
                this.form.telp = penghuni?.telepon ?? '-';
                this.form.jenis_kelamin = penghuni?.jenis_kelamin ?? '-';

            }
            }catch(err){
                console.error(err);
            }
        },

        // ================= UPDATE PROFILE =================
        async updateProfile(){

            this.errors = {};

            try{
                const res = await fetch('/profile/update',{
                    method:'PUT',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                    },
                    body:JSON.stringify(this.form)
                });

                const data = await res.json();

                if(!res.ok){
                    throw data;
                }

                Swal.fire('Berhasil','Profil diperbarui','success');

                this.editMode = false;

            }catch(err){

                let message = 'Terjadi kesalahan';

                if(err.errors){
                    message = Object.values(err.errors).flat().join('\n');
                    this.errors = err.errors;
                }

                Swal.fire('Error', message, 'error');
            }
        },

        // ================= UPDATE PASSWORD =================
        async updatePassword(){

            this.errors = {};

            try{
                const res = await fetch('/profile/update-password',{
                    method:'PUT',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                    },
                    body:JSON.stringify(this.password)
                });

                const data = await res.json();

                if(!res.ok){
                    throw data;
                }

                Swal.fire('Berhasil','Password diubah','success');

                this.password = {
                    password_lama:'',
                    password_baru:'',
                    password_baru_confirmation:''
                };

            }catch(err){

                let message = 'Terjadi kesalahan';

                if(err.errors){
                    message = Object.values(err.errors).flat().join('\n');
                }

                Swal.fire('Error', message, 'error');
            }
        }
    }
}
</script>

@endsection