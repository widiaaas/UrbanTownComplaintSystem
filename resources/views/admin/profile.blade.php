@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div x-data="{ openEdit:false, openChangePassword:false }" class="p-6 space-y-6">

    {{-- ================= HEADER ================= --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Profile Saya</h1>
        <button class="px-4 py-2 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500"
            @click="openEdit=true">Edit Profile</button>
    </div>

    {{-- ================= PROFILE INFO ================= --}}
    <div class="bg-white rounded-lg shadow p-6 space-y-4 max-w-md">
        <div class="flex justify-between">
            <span class="font-medium text-gray-700">Nama</span>
            <span>Widiawati Sihaloho</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-gray-700">Email</span>
            <span>widiawati@example.com</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-gray-700">Telepon</span>
            <span>081234567890</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-gray-700">Role</span>
            <span>Admin</span>
        </div>
        <div class="flex justify-end pt-4">
            <button class="btn btn-warning" @click="openChangePassword=true">Ubah Password</button>
        </div>
    </div>

    {{-- ================= MODAL EDIT PROFILE ================= --}}
    <div x-show="openEdit" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openEdit=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Edit Profile</h2>
            <div class="space-y-4">
                <div>
                    <label class="label">Nama</label>
                    <input type="text" class="input input-bordered w-full" value="Widiawati Sihaloho">
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" class="input input-bordered w-full" value="widiawati@example.com">
                </div>
                <div>
                    <label class="label">Telepon</label>
                    <input type="text" class="input input-bordered w-full" value="081234567890">
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button class="btn" @click="openEdit=false">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL UBAH PASSWORD ================= --}}
    <div x-show="openChangePassword" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div @click.outside="openChangePassword=false" class="bg-white w-full max-w-md rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Ubah Password</h2>
            <div class="space-y-4">
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
                <div class="flex justify-end gap-2 pt-4">
                    <button class="btn" @click="openChangePassword=false">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
