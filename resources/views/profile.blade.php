@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div 
    x-data="profileData()" 
    x-init="init()" 
    class="py-4 px-3"
>

    <div class="max-w-3xl mx-auto">

        {{-- HEADER --}}
        <div class="mb-3">
            <h1 class="text-2xl font-bold text-gray-800">
                Profile Saya
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Kelola informasi akun dan keamanan password
            </p>
        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">


            {{-- TAB --}}
            <div class="border-b bg-white">
                <div class="flex">

                    <button
                        @click="tab='profil'"
                        class="px-4 py-3 font-medium transition-all duration-300"
                        :class="
                            tab === 'profil'
                            ? 'border-b-4 border-yellow-500 text-yellow-600 bg-yellow-50'
                            : 'text-gray-500 hover:text-yellow-500'
                        "
                    >
                        Profil
                    </button>

                    <button
                        @click="tab='password'"
                        class="px-6 py-4 font-medium transition-all duration-300"
                        :class="
                            tab === 'password'
                            ? 'border-b-4 border-yellow-500 text-yellow-600 bg-yellow-50'
                            : 'text-gray-500 hover:text-yellow-500'
                        "
                    >
                        Ubah Kata Sandi
                    </button>

                </div>
            </div>

            {{-- ================= PROFIL ================= --}}
            <div x-show="tab==='profil'" class="p-5">

                {{-- VIEW MODE --}}
                <template x-if="!editMode">

                    <div class="space-y-5">

                        <div class="grid md:grid-cols-2 gap-3">

                            {{-- USERNAME --}}
                            <div class="border rounded-xl p-3">
                                <p class="text-xs text-gray-500 mb-1">
                                    Username
                                </p>

                                <p 
                                    class="text-sm font-semibold text-gray-800"
                                    x-text="user.username"
                                ></p>
                            </div>

                            {{-- NAMA --}}
                            <div class="border rounded-xl p-3">
                                <p class="text-xs text-gray-500 mb-1">
                                    Nama
                                </p>

                                <p 
                                    class="text-sm font-semibold text-gray-800"
                                    x-text="form.nama"
                                ></p>
                            </div>

                            {{-- NIK --}}
                            <template x-if="user.role === 'unit'">
                                <div class="border rounded-xl p-3">
                                    <p class="text-xs text-gray-500 mb-1">
                                        NIK
                                    </p>

                                    <p 
                                        class="text-sm font-semibold text-gray-800"
                                        x-text="form.nik"
                                    ></p>
                                </div>
                            </template>

                            {{-- EMAIL --}}
                            <div class="border rounded-xl p-3">
                                <p class="text-xs text-gray-500 mb-1">
                                    Email
                                </p>

                                <p 
                                    class="font-semibold text-gray-800 break-all"
                                    x-text="form.email"
                                ></p>
                            </div>

                            {{-- TELEPON --}}
                            <div class="border rounded-xl p-3">
                                <p class="text-xs text-gray-500 mb-1">
                                    Nomor Telepon
                                </p>

                                <p 
                                    class="text-sm font-semibold text-gray-800"
                                    x-text="form.no_telepon"
                                ></p>
                            </div>

                            {{-- JABATAN --}}
                            <template x-if=" user.role === 'admin' || user.role === 'tenant_relation' || user.role === 'departemen'">
                                <div class="border rounded-xl p-3">
                                    <p class="text-xs text-gray-500 mb-1">
                                        Jabatan
                                    </p>

                                    <p 
                                        class="text-sm font-semibold text-gray-800"
                                        x-text="form.jabatan"
                                    ></p>
                                </div>
                            </template>

                            {{-- JK --}}
                            <div class="border rounded-xl p-3">
                                <p class="text-xs text-gray-500 mb-1">
                                    Jenis Kelamin
                                </p>

                                <p 
                                    class="text-sm font-semibold text-gray-800"
                                    x-text="form.jenis_kelamin"
                                ></p>
                            </div>

                            {{-- STATUS --}}
                            <div class="border rounded-xl p-3">
                                <p class="text-sm text-gray-500 mb-2">
                                    Status
                                </p>

                                <span
                                    class="px-3 py-1 rounded-full text-sm font-semibold"
                                    :class="
                                        form.nama === 'Belum ada penghuni'
                                        ? 'bg-red-100 text-red-600'
                                        : 'bg-green-100 text-green-600'
                                    "
                                    x-text="
                                        form.nama === 'Belum ada penghuni'
                                        ? 'Kosong'
                                        : 'Aktif'
                                    "
                                ></span>
                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <div class="pt-4 text-right">

                            <button
                                @click="editMode=true"
                                class="px-4 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-sm text-white font-medium transition-all duration-300"
                                :disabled="
                                    user.role === 'unit' &&
                                    form.nama === 'Belum ada penghuni'
                                "
                                :class="
                                    user.role === 'unit' &&
                                    form.nama === 'Belum ada penghuni'
                                    ? 'opacity-50 cursor-not-allowed'
                                    : ''
                                "
                            >
                                Edit Profil
                            </button>

                        </div>

                    </div>

                </template>

                {{-- EDIT MODE --}}
                <template x-if="editMode">

                    <div class="space-y-5">

                        <div class="grid md:grid-cols-2 gap-5">

                            <div>
                                <label class="block mb-1 text-xs font-semibold text-gray-700">
                                    Nama
                                </label>

                                <input 
                                    x-model="form.nama"
                                    class="w-full rounded-lg border border-gray-300 focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400 px-3 py-2 text-sm outline-none transition"
                                >
                            </div>

                            <template x-if="user.role === 'unit'">

                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-gray-700">
                                        NIK
                                    </label>

                                    <input 
                                        x-model="form.nik"
                                        class="w-full rounded-lg border border-gray-300 focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400 px-3 py-2 text-sm outline-none transition"
                                    >
                                </div>

                            </template>

                            <div>
                                <label class="block mb-1 text-xs font-semibold text-gray-700">
                                    Email
                                </label>

                                <input 
                                    type="email"
                                    x-model="form.email"
                                    class="w-full rounded-lg border border-gray-300 focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400 px-3 py-2 text-sm outline-none transition"
                                >
                            </div>

                            <div>
                                <label class="block mb-1 text-xs font-semibold text-gray-700">
                                    Telepon
                                </label>

                                <input 
                                    type="tel"
                                    x-model="form.no_telepon"
                                    class="w-full rounded-lg border border-gray-300 focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400 px-3 py-2 text-sm outline-none transition"
                                >
                            </div>

                            <div>
                                <label class="block mb-1 text-xs font-semibold text-gray-700">
                                    Jenis Kelamin
                                </label>

                                <select 
                                    x-model="form.jenis_kelamin"
                                    class="w-full rounded-lg border border-gray-300 focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400 px-3 py-2 text-sm outline-none transition"
                                >
                                    <option value="">Pilih</option>

                                    <template 
                                        x-for="item in options.jenis_kelamin" 
                                        :key="item"
                                    >
                                        <option 
                                            :value="item" 
                                            x-text="item"
                                        ></option>
                                    </template>

                                </select>
                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <div class="flex justify-end gap-3 pt-4">

                            <button
                                @click="editMode=false"
                                class="px-5 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 transition"
                            >
                                Batal
                            </button>

                            <button
                                @click="updateProfile()"
                                class="px-6 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-semibold shadow-lg transition"
                            >
                                Simpan Perubahan
                            </button>

                        </div>

                    </div>

                </template>

            </div>

            {{-- ================= PASSWORD ================= --}}
            <div x-show="tab==='password'" class="p-5">

                <div class="max-w-xl mx-auto space-y-5">

                    {{-- PASSWORD LAMA --}}
                    <div>

                        <label class="block mb-1 text-xs font-semibold text-gray-700">
                            Kata Sandi Lama
                        </label>

                        <div class="relative">

                            <input
                                :type="showPassword.lama ? 'text' : 'password'"
                                x-model="password.password_lama"
                                class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 px-4 py-3 pr-12 outline-none transition"
                            >

                            <button
                                type="button"
                                @click="showPassword.lama = !showPassword.lama"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500"
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

                    {{-- PASSWORD BARU --}}
                    <div>

                        <label class="block mb-1 text-xs font-semibold text-gray-700">
                            Kata Sandi Baru
                        </label>

                        <div class="relative">

                            <input
                                :type="showPassword.baru ? 'text' : 'password'"
                                x-model="password.password_baru"
                                class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 px-4 py-3 pr-12 outline-none transition"
                            >

                            <button
                                type="button"
                                @click="showPassword.baru = !showPassword.baru"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500"
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

                    {{-- KONFIRMASI --}}
                    <div>

                        <label class="block mb-1 text-xs font-semibold text-gray-700">
                            Konfirmasi Kata Sandi
                        </label>

                        <div class="relative">

                            <input
                                :type="showPassword.konfirmasi ? 'text' : 'password'"
                                x-model="password.password_baru_confirmation"
                                class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 px-4 py-3 pr-12 outline-none transition"
                            >

                            <button
                                type="button"
                                @click="showPassword.konfirmasi = !showPassword.konfirmasi"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500"
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

                    {{-- BUTTON --}}
                    <div class="pt-3 text-right">

                        <button
                            @click="updatePassword()"
                            class="px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-semibold shadow-lg transition"
                        >
                            Simpan Kata Sandi
                        </button>

                    </div>

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

        // ================= INIT =================
        async init(){

            try{

                const res = await fetch('/profile/show',{

                    headers:{
                        'Accept':'application/json'
                    }

                });

                const data = await res.json();

                this.user = data.user;

                this.options.departemen =
                    data.options?.departemen ?? [];

                this.options.jenis_kelamin =
                    data.options?.jenis_kelamin ?? [];

                // KARYAWAN
                if(
                    data.user.role === 'admin' ||
                    data.user.role === 'tenant_relation' ||
                    data.user.role === 'departemen'
                ){

                    const p = data.profile;

                    this.form.nama = p?.nama ?? '';
                    this.form.email = p?.email ?? '';
                    this.form.no_telepon = p?.no_telepon ?? '';
                    this.form.jenis_kelamin = p?.jenis_kelamin ?? '';

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

                // UNIT
                if(data.user.role === 'unit'){

                    const unit = data.profile;
                    const penghuni = unit?.penghuni;

                    this.user.username =
                        unit?.nomor_unit ?? '-';

                    this.form.nama =
                        penghuni?.nama ?? 'Belum ada penghuni';

                    this.form.nik =
                        penghuni?.nik ?? '-';

                    this.form.email =
                        penghuni?.email ?? '-';

                    this.form.no_telepon =
                        penghuni?.no_telepon ?? '-';

                    this.form.jenis_kelamin =
                        penghuni?.jenis_kelamin ?? '-';
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

                    title:'Simpan perubahan?',
                    text:'Perubahan profil akan diperbarui',
                    icon:'question',

                    showCancelButton:true,

                    confirmButtonText:'Ya, Simpan',
                    cancelButtonText:'Batal',

                    confirmButtonColor:'#eab308',
                    cancelButtonColor:'#6b7280',
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

                    title:'Ubah kata sandi?',
                    text:'Kata sandi akun akan diperbarui',
                    icon:'warning',

                    showCancelButton:true,

                    confirmButtonText:'Ya, Ubah',
                    cancelButtonText:'Batal',

                    confirmButtonColor:'#eab308',
                    cancelButtonColor:'#6b7280',
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