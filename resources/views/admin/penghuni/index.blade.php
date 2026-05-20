@extends('layouts.app')

@section('title', 'Kelola Penghuni')

@section('content')

<div x-data="penghuniManager()" x-init='init(@json($penghunis))' class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Penghuni</h1>

        <button 
            @click="
                openCreate = true;
                newPenghuni = {nama:'',email:'',no_telepon:'',status:'Aktif'};
            "
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Tambah Penghuni
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

                Cari Penghuni

            </label>

            <input
                type="text"
                x-model="search"
                placeholder="Cari nama penghuni atau nomor unit..."
                class="w-full mt-1 border rounded-lg px-3 py-2
                focus:ring focus:ring-blue-200">

        </div>

        {{-- FILTER STATUS --}}
        <div class="md:w-64">

            <label
                class="text-sm font-medium text-gray-700">

                Status

            </label>

            <select
                x-model="statusFilter"
                class="w-full mt-1 border rounded-lg px-3 py-2
                bg-white focus:ring focus:ring-blue-200">

                <option value="">
                    Semua
                </option>

                <option value="Aktif">
                    Aktif
                </option>

                <option value="Nonaktif">
                    Nonaktif
                </option>

            </select>

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
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>

            <tbody>
                {{-- DATA TIDAK ADA --}}
                <template x-if="!filteredPenghuni.length">

                    <tr>
                        <td
                            colspan="7"
                            class="px-4 py-4 text-center text-gray-400 italic"
                        >
                            Data penghuni tidak tersedia
                        </td>
                    </tr>

                </template>

                {{-- DATA ADA --}}
                <template
                    x-for="(p, index) in filteredPenghuni"
                    :key="p.id">

                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-4 py-3" x-text="index+1"></td>
                        <td class="px-4 py-3" x-text="p.nama"></td>
                        <td class="px-4 py-3" x-text=" p.riwayat_hunian?.find( r => r.status === 'Aktif')?.unit?.nomor_unit ?? '-'" ></td>
                        <td class="px-4 py-3">
                            <span
                            x-show="
                                p.riwayat_hunian?.some(
                                    r => r.status === 'Aktif'
                                )
                            "
                                class="text-green-600 font-medium">
                                Aktif
                            </span>
                            <span
                            x-show="
                                    !p.riwayat_hunian?.some(
                                        r => r.status === 'Aktif'
                                    )
                                "
                                class="text-red-600 font-medium">
                                Nonaktif
                            </span>
                        </td>

                        <td class="px-4 py-2 text-center">

                            <div class="flex items-center justify-center gap-2">

                                {{-- DETAIL --}}
                                <button
                                    @click="detail(p)"
                                    class="flex items-center gap-2
                                    px-2  text-xs
                                    
                                    rounded-lg hover:bg-green-200 transition">

                                    @include('components.buttons.btn-view')

                                </button>

                                {{-- EDIT --}}
                                <button
                                    @click="edit(p)"
                                    class="flex items-center gap-2
                                    px-2  text-xs
                                   
                                    rounded-lg hover:bg-green-200 transition">

                                    @include('components.buttons.btn-edit')

                                </button>

                            </div>

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
                    <label class="text-sm font-medium text-gray-700">NIK</label>
                    <input x-model="newPenghuni.nik" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input x-model="newPenghuni.email" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Telepon</label>
                    <input x-model="newPenghuni.no_telepon" class="w-full border px-3 py-2 rounded">
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
                    <label class="text-sm font-medium text-gray-700">NIK</label>
                    <input x-model="selected.nik" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input x-model="selected.email" class="w-full border px-3 py-2 rounded">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Telepon</label>
                    <input x-model="selected.no_telepon" class="w-full border px-3 py-2 rounded">
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
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="openEdit=false" class="px-3 py-2 border rounded">Batal</button>
                <button @click="update()" class="px-3 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>

        </div>
    </div>

    {{-- ================= MODAL DETAIL PENGHUNI ================= --}}
    <div
        x-show="openDetail"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 bg-black/50
        backdrop-blur-sm
        flex items-center justify-center
        z-50 p-4">

        <div
            @click.outside="openDetail = false"
            x-transition.scale
            class="bg-white w-full max-w-lg
            rounded-2xl shadow-2xl overflow-hidden">

            {{-- HEADER --}}
            <div
                class="flex items-center justify-between
                px-6 py-4 border-b border-gray-100">

                <div>

                    <h2 class="text-lg font-semibold text-gray-800">
                        Detail Penghuni
                    </h2>

                    <p class="text-sm text-gray-500">
                        Informasi lengkap data penghuni
                    </p>

                </div>

                {{-- CLOSE --}}
                <button
                    @click="openDetail = false"
                    class="w-9 h-9 rounded-lg
                    flex items-center justify-center
                    hover:bg-gray-100 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-gray-500"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2">

                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>

                    </svg>

                </button>

            </div>

            {{-- CONTENT --}}
            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- NAMA --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Nama Penghuni

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.nama || '-'">
                        </div>

                    </div>

                    {{-- NIK --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            NIK

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.nik || '-'">
                        </div>

                    </div>

                    {{-- EMAIL --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Email

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800 break-all"
                            x-text="selected.email || '-'">
                        </div>

                    </div>

                    {{-- TELEPON --}}
                    <div>

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Nomor Telepon

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.no_telepon || '-'">
                        </div>

                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div class="md:col-span-2">

                        <label
                            class="text-xs font-semibold
                            text-gray-700">

                            Jenis Kelamin

                        </label>

                        <div
                            class="mt-1 bg-gray-50
                            border border-gray-100
                            rounded-xl px-4 py-3
                            text-sm text-gray-800"
                            x-text="selected.jenis_kelamin || '-'">
                        </div>

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div
                class="flex justify-end
                px-6 py-4 border-t
                border-gray-100 bg-gray-50">

                <button
                    @click="openDetail = false"
                    class="px-4 py-2 rounded-xl
                    bg-blue-600 text-white
                    text-sm font-medium
                    hover:bg-blue-700 transition">

                    Tutup

                </button>

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
        openDetail:false,
        search:'',
        statusFilter:'',

        newPenghuni:{
            nik:'',
            nama:'',
            email:'',
            no_telepon:'',
            jenis_kelamin:'',
            status:'Aktif'
        },

        selected:{},

        errors:{}, // 🔥 untuk error per field

        init(data){
            this.penghuni = data;
        },

        // Search
        get filteredPenghuni(){

            return this.penghuni.filter(p => {

                // ================= SEARCH =================

                const keyword =
                    this.search.toLowerCase();

                const unitAktif =
                    p.riwayat_hunian
                        ?.find(r => r.status === 'Aktif')
                        ?.unit
                        ?.nomor_unit || '';

                const matchSearch =

                    (p.nama || '')
                        .toLowerCase()
                        .includes(keyword)

                    ||

                    unitAktif
                        .toLowerCase()
                        .includes(keyword);

                // ================= STATUS =================

                const isAktif =
                    p.riwayat_hunian?.some(
                        r => r.status === 'Aktif'
                    );

                const status =
                    isAktif
                        ? 'Aktif'
                        : 'Nonaktif';

                const matchStatus =

                    !this.statusFilter ||

                    status === this.statusFilter;

                return matchSearch && matchStatus;
            });
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

        showEditError(msg, errors = {}){

            this.errors = errors;

            this.openEdit = true;

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg
            }).then(() => {

                setTimeout(() => {
                    this.openEdit = true;
                }, 50);

            });
            },

        detail(p){
            this.selected = p;
            this.openDetail = true;
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
            if(!this.newPenghuni.nik){
                this.showError('NIKwajib diisi', {nik:['NIK wajib diisi']});
                return;
            }

            if(!this.newPenghuni.email){
                this.showError('Email wajib diisi', {email:['Email wajib diisi']});
                return;
            }

            if(!this.newPenghuni.no_telepon){
                this.showError('No. Telepon wajib diisi', {no_telepon:['No. Telepon wajib diisi']});
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
                        nik:'',
                        nama:'',
                        email:'',
                        no_telepon:'',
                        jenis_kelamin:'',
        
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

            fetch(`{{ url('/penghuni') }}/${this.selected.id}`,{
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

                this.showEditError(message, err.errors || {});
            });
        },

    }
}
</script>

@endsection