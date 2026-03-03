@extends('layouts.app')

@section('title', 'Profile Departemen')

@section('content')

<div x-data="{ tab:'profil', editMode:false }" class="p-6 max-w-xl mx-auto">

    {{-- ================= HEADER ================= --}}
    <h1 class="text-2xl font-bold text-gray-900 mb-4">Profile Saya</h1>

    <div class="bg-white rounded-lg shadow">

        {{-- ================= SUB NAVBAR ================= --}}
        <div class="flex border-b">
            <button
                class="px-4 py-3 text-sm font-medium"
                :class="tab==='profil'
                    ? 'border-b-2 border-yellow-500 text-yellow-600'
                    : 'text-gray-500 hover:text-gray-700'"
                @click="tab='profil'">
                Profil
            </button>

            <button
                class="px-4 py-3 text-sm font-medium"
                :class="tab==='password'
                    ? 'border-b-2 border-yellow-500 text-yellow-600'
                    : 'text-gray-500 hover:text-gray-700'"
                @click="tab='password'">
                Ubah Password
            </button>
        </div>

        {{-- ================= TAB PROFIL ================= --}}
        <div x-show="tab==='profil'" x-cloak class="p-6 space-y-4">

            {{-- ===== MODE VIEW ===== --}}
            <template x-if="!editMode">
                <div class="space-y-3">

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Nama</span>
                        <span>Widiawati Sihaloho</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Email</span>
                        <span>widiawati@email.com</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">No. Telepon</span>
                        <span>081234567890</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Departemen</span>
                        <span>Engineering</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Jenis Kelamin</span>
                        <span>Perempuan</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Status</span>
                        <span class="text-green-600 font-medium">Aktif</span>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button class="btn btn-warning" @click="editMode=true">
                            Edit Profil
                        </button>
                    </div>

                </div>
            </template>

            {{-- ===== MODE EDIT ===== --}}
            <template x-if="editMode">
                <div class="space-y-4">

                    <div>
                        <label class="text-sm font-medium">Nama</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2"
                               value="Widiawati Sihaloho">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input type="email" class="w-full border rounded-lg px-3 py-2"
                               value="widiawati@email.com">
                    </div>

                    <div>
                        <label class="text-sm font-medium">No. Telepon</label>
                        <input type="text" class="w-full border rounded-lg px-3 py-2"
                               value="081234567890">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Departemen</label>
                        <select class="w-full border rounded-lg px-3 py-2">
                            <option>Engineering</option>
                            <option>Admin</option>
                            <option>Tenant Relation</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Jenis Kelamin</label>
                        <select class="w-full border rounded-lg px-3 py-2">
                            <option>Perempuan</option>
                            <option>Laki-laki</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Status</label>
                        <select class="w-full border rounded-lg px-3 py-2" disabled>
                            <option>Aktif</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            Status diatur oleh Admin
                        </p>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button class="btn" @click="editMode=false">Batal</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </template>

        </div>

        {{-- ================= TAB UBAH PASSWORD ================= --}}
        <div x-show="tab==='password'" x-cloak class="p-6 space-y-4">

            <div>
                <label class="label">Password Lama</label>
                <input type="password" class="input input-bordered w-full">
            </div>

            <div>
                <label class="label">Password Baru</label>
                <input type="password" class="input input-bordered w-full">
            </div>

            <div>
                <label class="label">Konfirmasi Password Baru</label>
                <input type="password" class="input input-bordered w-full">
            </div>

            <div class="flex justify-end pt-4">
                <button class="btn btn-primary">Simpan Password</button>
            </div>

        </div>

    </div>
</div>

@endsection