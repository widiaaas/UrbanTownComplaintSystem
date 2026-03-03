@extends('layouts.app')

@section('title', 'Ajukan Keluhan')

@section('content')

<div class="p-6 flex justify-center">

    {{-- ================= FORM AJUKAN KELUHAN ================= --}}
    <div class="bg-white rounded-lg shadow p-6 w-full max-w-lg space-y-6">

        <h1 class="text-2xl font-bold text-gray-900 text-center">Ajukan Keluhan</h1>

        <form x-data="{ submitting:false }"
              @submit.prevent="submitting=true; setTimeout(()=>submitting=false,1000)"
              class="space-y-4">

            {{-- Judul Keluhan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Judul Keluhan</label>
                <input type="text"
                       class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            </div>

            <!-- {{-- Lantai --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Lantai</label>
                <input type="number"
                       placeholder="Contoh: 3"
                       class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            </div> -->

            <!-- {{-- Nomor Unit --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Unit</label>
                <input type="text"
                       placeholder="Contoh: 305"
                       class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            </div> -->

            {{-- Deskripsi Keluhan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi Keluhan</label>
                <textarea rows="4"
                          placeholder="Jelaskan keluhan Anda secara detail..."
                          class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            {{-- Lampiran Keluhan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Lampiran Keluhan <span class="text-gray-400">(opsional)</span>
                </label>
                <input type="file" class="w-full mt-1 text-sm">
                <p class="text-xs text-gray-500 mt-1">Format gambar/PDF, maksimal 1 file</p>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex justify-end pt-2">
                <button type="submit"
                        :disabled="submitting"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                    <span x-show="!submitting">Kirim Keluhan</span>
                    <span x-show="submitting" x-cloak>Mengirim...</span>
                </button>
            </div>

        </form>

    </div>
</div>

@endsection