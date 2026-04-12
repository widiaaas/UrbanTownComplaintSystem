@extends('layouts.app')

@section('title', 'Profile Admin')

@section('content')

<div x-data="profileData()" class="p-6 max-w-xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">Profile Saya</h1>

    <div class="bg-white rounded-lg shadow">

        {{-- TAB --}}
        <div class="flex border-b">
            <button @click="tab='profil'" :class="tab==='profil' ? activeTab : normalTab">Profil</button>
            <button @click="tab='password'" :class="tab==='password' ? activeTab : normalTab">Ubah Password</button>
        </div>

        {{-- ================= PROFIL ================= --}}
        <div x-show="tab==='profil'" class="p-6 space-y-4">

            {{-- VIEW --}}
            <template x-if="!editMode">
                <div class="space-y-3">

                    <div class="flex justify-between"><span>Nama</span><span x-text="form.nama"></span></div>
                    <div class="flex justify-between"><span>Email</span><span x-text="form.email"></span></div>
                    <div class="flex justify-between"><span>Telepon</span><span x-text="form.telp"></span></div>
                    <div class="flex justify-between"><span>Departemen</span><span x-text="form.departemen"></span></div>
                    <div class="flex justify-between"><span>Jenis Kelamin</span><span x-text="form.jenis_kelamin"></span></div>
                    <div class="flex justify-between"><span>Status</span><span class="text-green-600">Aktif</span></div>

                    <div class="text-right">
                        <button class="btn btn-warning" @click="editMode=true">Edit</button>
                    </div>

                </div>
            </template>

            {{-- EDIT --}}
            <template x-if="editMode">
                <div class="space-y-4">

                    <input x-model="form.nama" class="input w-full" placeholder="Nama">
                    <input x-model="form.email" class="input w-full" placeholder="Email">
                    <input x-model="form.telp" class="input w-full" placeholder="Telepon">

                    <select x-model="form.departemen" class="input w-full">
                        <option>Engineering</option>
                        <option>Admin</option>
                        <option>Tenant Relation</option>
                    </select>

                    <select x-model="form.jenis_kelamin" class="input w-full">
                        <option value="">Pilih</option>
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>

                    <div class="flex justify-end gap-2">
                        <button @click="editMode=false" class="btn">Batal</button>
                        <button @click="updateProfile()" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </template>

        </div>

        {{-- ================= PASSWORD ================= --}}
        <div x-show="tab==='password'" class="p-6 space-y-4">

            <input x-model="password.password_lama" type="password" class="input w-full" placeholder="Password Lama">
            <input x-model="password.password_baru" type="password" class="input w-full" placeholder="Password Baru">
            <input x-model="password.password_baru_confirmation" type="password" class="input w-full" placeholder="Konfirmasi Password">

            <div class="text-right">
                <button @click="updatePassword()" class="btn btn-primary">Simpan Password</button>
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
            nama:'{{ $user->nama }}',
            email:'{{ $user->email }}',
            telp:'{{ $user->telp }}',
            departemen:'{{ $user->departemen }}',
            jenis_kelamin:'{{ $user->jenis_kelamin }}'
        },

        password:{
            password_lama:'',
            password_baru:'',
            password_baru_confirmation:''
        },

        // ================= UPDATE PROFILE =================
        async updateProfile(){

            this.errors = {};

            try{
                const res = await fetch('/profileAdmin/update',{
                    method:'PUT',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                    },
                    body:JSON.stringify(this.form)
                });

                let data;
                try {
                    data = await res.json();
                } catch {
                    throw { message:'Server tidak valid (bukan JSON)' };
                }

                if(!res.ok){
                    throw data;
                }

                Swal.fire('Berhasil','Profil diperbarui','success')
                .then(()=>location.reload());

            }catch(err){

                let message = 'Terjadi kesalahan';

                if(err.errors){
                    message = Object.values(err.errors).flat().join('\n');
                    this.errors = err.errors;
                } else if(err.message){
                    message = err.message;
                }

                Swal.fire('Error', message, 'error');
            }
        },

        // ================= UPDATE PASSWORD =================
        async updatePassword(){

            this.errors = {};

            try{
                const res = await fetch('/profileAdmin/update-password',{
                    method:'PUT',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                    },
                    body:JSON.stringify(this.password)
                });

                let data = await res.json();

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