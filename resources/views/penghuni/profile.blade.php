@extends('layouts.app')

@section('title', 'Profile Penghuni')

@section('content')

<div class="p-6 flex justify-center">

    {{-- ================= PROFILE CARD ================= --}}
    <div class="bg-white rounded-lg shadow p-6 space-y-4 w-full max-w-md">

        {{-- Avatar / Foto --}}
        <div class="flex justify-center">
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-blue-500">
                <img src="https://i.pravatar.cc/150?img=12" alt="Avatar Penghuni" class="w-full h-full object-cover">
            </div>
        </div>

        {{-- Nama --}}
        <div class="text-center">
            <h2 class="text-xl font-semibold text-gray-900">Widiawati Sihaloho</h2>
            <p class="text-gray-500">Penghuni Unit A-101</p>
        </div>

        {{-- Informasi detail --}}
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Unit</span>
                <span class="text-gray-900">A-101</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Email</span>
                <span class="text-gray-900">widiawati@example.com</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Telepon</span>
                <span class="text-gray-900">081234567890</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-700">Status</span>
                <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-medium">Aktif</span>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="flex justify-center gap-2 pt-4">
            <button class="btn btn-primary px-4 py-2">Edit Profile</button>
            <button class="btn border px-4 py-2 hover:bg-gray-100">Ganti Password</button>
        </div>

    </div>
</div>

@endsection
