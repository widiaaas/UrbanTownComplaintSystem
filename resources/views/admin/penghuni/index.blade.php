@extends('layouts.app')

@section('title', 'Kelola Penghuni')

@section('content')

<div x-data="penghuniManager()" x-init='init(@json($penghunis))' class="p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Kelola Penghuni</h1>

        <button 
            @click="
                openCreate = true;
                newPenghuni = {nama:'',email:'',telepon:'',status:'Aktif'};
            "
            class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            + Tambah Penghuni
        </button>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-lg shadow p-4 flex flex-col md:flex-row md:items-end gap-4">
        <div class="flex-1">
            <label class="text-sm font-medium text-gray-700">Cari Nama</label>
            <input type="text" 
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filter</button>
            <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Reset</button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm text-center border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Unit</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Telepon</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="(p, index) in penghuni" :key="p.id">
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="px-4 py-3" x-text="index+1"></td>
                    <td class="px-4 py-3" x-text="p.nama"></td>
                    <td class="px-4 py-3" x-text="p.unit?.no_unit ?? '-'"></td>
                    <td class="px-4 py-3" x-text="p.email"></td>
                    <td class="px-4 py-3" x-text="p.telepon"></td>
                    <td class="px-4 py-3">
                        <span x-show="p.status=='Aktif'" class="text-green-600 font-medium">Aktif</span>
                        <span x-show="p.status=='Nonaktif'" class="text-red-600 font-medium">Nonaktif</span>
                    </td>

                    <td class="space-x-2">
                        <button @click="edit(p)" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Edit</button>
                        <button @click="hapus(p)" class="px-2 py-1 bg-red-500 text-white rounded text-xs">Hapus</button>
                    </td>
                </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- MODAL CREATE --}}
    <div x-show="openCreate" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">

            <h2 class="text-lg font-semibold mb-4">Tambah Penghuni</h2>

            <div class="space-y-3">

                <div>
                    <label class="text-sm font-medium text-gray-700">Nama</label>
                    <input x-model="newPenghuni.nama" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input x-model="newPenghuni.email" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Telepon</label>
                    <input x-model="newPenghuni.telepon" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select x-model="newPenghuni.jenis_kelamin" class="w-full border px-3 py-2 rounded">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        @foreach($jenisKelamin as $jk)
                            <option value="{{ $jk }}">{{ $jk }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <select x-model="newPenghuni.status" class="w-full border px-3 py-2 rounded">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="openCreate=false" class="px-3 py-2 border rounded">Batal</button>
                <button @click="store()" class="px-3 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>

        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div x-show="openEdit" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">

            <h2 class="text-lg font-semibold mb-4">Edit Penghuni</h2>

            <div class="space-y-3">

                <div>
                    <label class="text-sm font-medium text-gray-700">Nama</label>
                    <input x-model="selected.nama" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input x-model="selected.email" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Telepon</label>
                    <input x-model="selected.telepon" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select x-model="selected.jenis_kelamin" class="w-full border px-3 py-2 rounded">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        @foreach($jenisKelamin as $jk)
                            <option value="{{ $jk }}">{{ $jk }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <select x-model="selected.status" class="w-full border px-3 py-2 rounded">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="openEdit=false" class="px-3 py-2 border rounded">Batal</button>
                <button @click="update()" class="px-3 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>

        </div>
    </div>

</div>

<script>
function penghuniManager(){
    return{
        penghuni:[],
        openCreate:false,
        openEdit:false,

        newPenghuni:{
            nama:'',
            email:'',
            telepon:'',
            jenis_kelamin:'',
            status:'Aktif'
        },

        selected:{},

        errors:{}, // 🔥 untuk error per field

        init(data){
            this.penghuni = data;
        },

        // ================= ERROR HANDLER =================
        showError(msg, errors = {}){
            this.errors = errors;

            this.openCreate = true;

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg
            }).then(() => {
                setTimeout(() => {
                    this.openCreate = true;
                }, 50);
            });
        },

        // ================= STORE =================
        store(){

            // RESET ERROR
            this.errors = {};

            // VALIDASI FRONTEND
            if(!this.newPenghuni.nama){
                this.showError('Nama wajib diisi', {nama:['Nama wajib diisi']});
                return;
            }

            if(!this.newPenghuni.email){
                this.showError('Email wajib diisi', {email:['Email wajib diisi']});
                return;
            }

            if(!this.newPenghuni.telepon){
                this.showError('No. Telepon wajib diisi', {telepon:['No. Telepon wajib diisi']});
                return;
            }
            if(!this.newPenghuni.jenis_kelamin){
                this.showError('Jenis kelamin wajib dipilih', {jenis_kelamin:['Wajib dipilih']});
                return;
            }

            // REQUEST
            fetch("{{ route('admin.penghuni.store') }}",{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'Accept':'application/json',
                    'X-Requested-With': 'XMLHttpRequest', // 🔥 INI WAJIB
                    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                },
                body:JSON.stringify(this.newPenghuni)
            })
            .then(async res => {

                let data;

                try {
                    data = await res.json();
                } catch (e) {
                    throw { message: 'Server mengembalikan response tidak valid (bukan JSON)' };
                }

                if(!res.ok){
                    throw data;
                }

                return data;
                })
            .then(res => {

                if(res.success){

                    Swal.fire({
                        icon: 'success',
                        title: 'Penghuni Berhasil Ditambahkan',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });

                    // RESET FORM
                    this.newPenghuni = {
                        nama:'',
                        email:'',
                        telepon:'',
                        jenis_kelamin:'',
                        status:'Aktif'
                    };

                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menyimpan','error');
                }
            })
            .catch(err => {

                console.error(err);

                let message = 'Terjadi kesalahan';

                if(err.errors){
                    message = Object.values(err.errors)
                        .flat()
                        .join('\n');
                } 
                else if(err.message){
                    message = err.message;
                }

                this.showError(message, err.errors || {});
                });
        },

        // ================= EDIT =================
        edit(p){
            this.selected = {
                ...p,
                jenis_kelamin: p.jenis_kelamin || ''
            };
            this.errors = {};
            this.openEdit = true;
        },

        // ================= UPDATE =================
        update(){

            this.errors = {};

            fetch(`/penghuni/update/${this.selected.id}`,{
                method:'PUT',
                headers:{
                    'Content-Type':'application/json',
                    'Accept':'application/json', // 🔥 INI WAJIB
                    'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
                },
                body:JSON.stringify(this.selected)
            })
            .then(async res => {
                if(!res.ok){
                    const err = await res.json();
                    throw err;
                }
                return res.json();
            })
            .then(res=>{
                if(res.success){
                    Swal.fire('Berhasil','Data diperbarui','success')
                    .then(()=> location.reload());
                } else {
                    Swal.fire('Error','Gagal update','error');
                }
            })
            .catch(err => {

                console.error(err);

                let message = 'Terjadi kesalahan';

                if(err.errors){
                    message = Object.values(err.errors)
                        .flat()
                        .join('\n');
                }

                this.showError(message, err.errors || {});
            });
        },

        // ================= DELETE =================
        hapus(p){
            Swal.fire({
                title: 'Hapus?',
                text: 'Data tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result=>{
                if(!result.isConfirmed) return;

                fetch(`/penghuni/delete/${p.id}`,{
                    method:'DELETE',
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
        }
    }
}
</script>

@endsection