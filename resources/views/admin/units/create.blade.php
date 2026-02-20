@extends('layouts.app')

@section('title', 'Tambah Unit')

@section('content')
<div class="max-w-xl">

    <!-- Judul Halaman -->
    <h1 class="text-2xl font-bold mb-6">
        Tambah Unit Baru
    </h1>

    <!-- Card Form -->
    <div class="bg-white p-6 rounded-xl border shadow-sm space-y-5">

        <!-- Nomor Unit -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Nomor Unit
            </label>
            <input 
                type="text" 
                placeholder="Contoh: A-101"
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
            >
        </div>

        <!-- Gedung -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Gedung
            </label>
            <input 
                type="text" 
                placeholder="Contoh: Tower A"
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
            >
        </div>

        <!-- Lantai -->
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Lantai
            </label>
            <input 
                type="number" 
                placeholder="Contoh: 10"
                class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
            >
        </div>

        <!-- Tombol -->
        <div class="flex justify-end gap-3 pt-4">
            <a href="#"
               class="px-5 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                Batal
            </a>

            <button
                class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Simpan Unit
            </button>
        </div>

    </div>

</div>
@endsection
