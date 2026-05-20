@extends('layouts.app')

@section('title', 'Ajukan Keluhan')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-5">

        <h1
            class="text-2xl font-black
            tracking-tight text-gray-800">

            Ajukan Keluhan

        </h1>

        <p
            class="text-sm text-gray-500 mt-1">

            Sampaikan keluhan Anda secara detail
            agar dapat segera ditindaklanjuti

        </p>

    </div>

    {{-- CARD --}}
    <div
        class="bg-white/90 backdrop-blur-sm
        border border-white/50
        rounded-2xl shadow-xl
        overflow-hidden">

        <form
            x-data="keluhanForm()"
            @submit.prevent="submitForm"
            enctype="multipart/form-data"
            class="p-6 space-y-5">

            {{-- ================= JUDUL ================= --}}
            <div>

                <label
                    class="block text-sm
                    font-semibold text-gray-700 mb-2">

                    Judul Keluhan

                </label>

                <input
                    type="text"
                    x-model="form.judul"
                    placeholder="Masukkan judul keluhan"
                    class="w-full rounded-2xl
                    border border-gray-200
                    bg-white
                    px-4 py-2.5
                    focus:ring-2 focus:ring-blue-500
                    focus:border-blue-500
                    focus:outline-none
                    transition">

            </div>

            {{-- ================= DESKRIPSI ================= --}}
            <div>

                <label
                    class="block text-sm
                    font-semibold text-gray-700 mb-2">

                    Deskripsi Keluhan

                </label>

                <textarea
                    rows="5"
                    x-model="form.deskripsi"
                    placeholder="Jelaskan keluhan Anda secara detail..."
                    class="w-full rounded-2xl
                    border border-gray-200
                    bg-white
                    px-4 py-3
                    resize-none
                    focus:ring-2 focus:ring-blue-500
                    focus:border-blue-500
                    focus:outline-none
                    transition"></textarea>

            </div>

            {{-- ================= LAMPIRAN ================= --}}
            <div>

                <label
                    class="block text-sm
                    font-semibold text-gray-700 mb-2">

                    Lampiran Keluhan

                    <span class="text-gray-400 font-normal">
                        (Opsional)
                    </span>

                </label>

                {{-- UPLOAD BOX --}}
                <label
                    class="flex flex-col items-center
                    justify-center gap-3
                    w-full px-5 py-6
                    rounded-2xl
                    border-2 border-dashed
                    border-gray-200
                    bg-gray-50/70
                    hover:border-blue-400
                    hover:bg-blue-50/50
                    transition cursor-pointer">

                    {{-- ICON --}}
                    <div
                        class="w-12 h-12 rounded-2xl
                        bg-blue-100 text-blue-600
                        flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v1
                                a3 3 0 003 3h10a3 3 0 003-3v-1"/>

                        </svg>

                    </div>

                    {{-- TEXT --}}
                    <div class="text-center">

                        <p
                            class="font-semibold
                            text-gray-700">

                            Klik untuk upload file

                        </p>

                        <p
                            class="text-xs text-gray-400 mt-1">

                            JPG, PNG, PDF
                            • Maksimal 1MB
                            • Bisa lebih dari 1 file

                        </p>

                    </div>

                    {{-- INPUT --}}
                    <input
                        type="file"
                        multiple
                        @change="handleFile"
                        x-ref="fileInput"
                        class="hidden">

                </label>

            </div>

            {{-- ================= PREVIEW FILE ================= --}}
            <div
                x-show="form.lampiran.length > 0"
                x-transition
                class="space-y-3">

                <div
                    class="flex items-center justify-between">

                    <p
                        class="text-sm font-semibold
                        text-gray-700">

                        Lampiran Dipilih

                    </p>

                    <span
                        class="text-xs text-gray-400"
                        x-text="form.lampiran.length + ' file'">
                    </span>

                </div>

                {{-- FILE LIST --}}
                <div class="space-y-2">

                    <template
                        x-for="(file, index) in form.lampiran"
                        :key="index">

                        <div
                            class="flex items-center justify-between
                            px-3 py-2
                            rounded-xl
                            border border-gray-200
                            bg-gray-50">

                            {{-- LEFT --}}
                            <div
                                class="flex items-center gap-2
                                min-w-0">

                                {{-- IMAGE --}}
                                <template
                                    x-if="file.type.startsWith('image/')">

                                    <img
                                        :src="URL.createObjectURL(file)"
                                        class="w-10 h-10 rounded-xl
                                        object-cover">

                                </template>

                                {{-- PDF --}}
                                <template
                                    x-if="!file.type.startsWith('image/')">

                                    <div
                                        class="w-12 h-12 rounded-xl
                                        bg-red-100 text-red-600
                                        flex items-center justify-center
                                        shrink-0">

                                        📄

                                    </div>

                                </template>

                                {{-- INFO --}}
                                <div class="min-w-0">

                                    <p
                                        class="text-xs font-medium
                                        text-gray-800 truncate"
                                        x-text="file.name">
                                    </p>

                                    <p
                                        class="text-xs text-gray-400"
                                        x-text="(file.size / 1024).toFixed(1) + ' KB'">
                                    </p>

                                </div>

                            </div>

                            {{-- ACTION --}}
                            <div
                                class="flex items-center gap-1.5 shrink-0">

                                {{-- PREVIEW --}}
                                <button
                                    type="button"
                                    @click="previewFile(file)"
                                    title="Preview Lampiran"
                                    class="hover:scale-105 transition">

                                    @include('components.buttons.btn-view')

                                </button>

                                {{-- DELETE --}}
                                <button
                                    type="button"
                                    @click="removeFile(index)"
                                    title="Hapus Lampiran"
                                    class="hover:scale-105 transition">

                                    @include('components.buttons.btn-delete')

                                </button>

                            </div>

                        </div>

                    </template>

                </div>

            </div>

            {{-- ================= BUTTON ================= --}}
            <div
                class="flex justify-end pt-2">

                <button
                    type="submit"
                    :disabled="submitting"
                    class="px-5 py-2.5 rounded-xl
                    bg-blue-600 text-white
                    font-semibold tracking-wide
                    shadow-lg shadow-blue-500/20
                    hover:bg-blue-700
                    hover:shadow-blue-500/30
                    disabled:opacity-50
                    active:scale-[0.99]
                    transition">

                    <span x-show="!submitting">

                        Kirim Keluhan

                    </span>

                    <span
                        x-show="submitting"
                        x-cloak>

                        Mengirim...

                    </span>

                </button>

            </div>

            {{-- ================= MODAL PREVIEW ================= --}}
            <div
                x-show="preview.open"
                x-cloak
                class="fixed inset-0 z-50
                bg-black/70 backdrop-blur-sm
                flex items-center justify-center
                p-4">

                <div
                    class="bg-white rounded-2xl
                    w-full max-w-5xl
                    p-4 relative shadow-2xl">

                    {{-- CLOSE --}}
                    <button
                        type="button"
                        @click="closePreview"
                        class="absolute top-4 right-4
                        w-10 h-10 rounded-xl
                        bg-gray-100 text-gray-500
                        hover:bg-gray-200
                        hover:text-gray-700
                        transition">

                        ✕

                    </button>

                    {{-- IMAGE --}}
                    <template x-if="preview.type === 'image'">

                        <img
                            :src="preview.url"
                            class="w-full max-h-[80vh]
                            object-contain rounded-2xl">

                    </template>

                    {{-- PDF --}}
                    <template x-if="preview.type === 'pdf'">

                        <div
                            class="flex items-center justify-between
                            bg-gray-50 border border-gray-200
                            rounded-2xl px-4 py-3">

                            {{-- LEFT --}}
                            <div class="flex items-center gap-3 min-w-0">

                                {{-- ICON --}}
                                <div
                                    class="w-11 h-11 rounded-xl
                                    bg-red-100 text-red-600
                                    flex items-center justify-center
                                    shrink-0 text-lg">

                                    📄

                                </div>

                                {{-- INFO --}}
                                <div class="min-w-0">

                                    <p
                                        class="text-sm font-semibold
                                        text-gray-800">

                                        File PDF

                                    </p>

                                    <p
                                        class="text-xs text-gray-400
                                        truncate max-w-[240px]"
                                        x-text="preview.name || 'Dokumen PDF'">
                                    </p>

                                </div>

                            </div>

                            {{-- ACTION --}}
                            <div class="flex items-center gap-2">

                                {{-- VIEW --}}
                                <a
                                    :href="preview.url"
                                    target="_blank"
                                    title="Buka PDF"
                                    class="hover:scale-105 transition">

                                    @include('components.buttons.btn-view')

                                </a>

                                

                            </div>

                        </div>

                    </template>

                </div>

            </div>

        </form>

    </div>

</div>

<script>

function keluhanForm() {

    return {

        submitting: false,

        form: {
            judul: '',
            deskripsi: '',
            lampiran: []
        },

        preview: {
            open: false,
            url: null,
            type: null
        },

        // ================= PREVIEW =================
        previewFile(file) {

            const url =
                URL.createObjectURL(file);

            // IMAGE
            if (file.type.startsWith('image/')) {

                this.preview.type = 'image';
                this.preview.url = url;
                this.preview.open = true;

            }

            // PDF
            else if (file.type === 'application/pdf') {

                this.preview.type = 'pdf';
                this.preview.url = url;
                this.preview.open = true;

            }

            // OTHER
            else {

                window.open(url, '_blank');

            }

        },

        closePreview() {

            this.preview.open = false;
            this.preview.url = null;
            this.preview.type = null;

        },

        // ================= HANDLE FILE =================
        handleFile(e) {

            const files =
                Array.from(e.target.files);

            this.form.lampiran = [
                ...this.form.lampiran,
                ...files
            ];

        },

        removeFile(index) {

            this.form.lampiran.splice(index, 1);

        },

        // ================= SUBMIT =================
        async submitForm() {

            const confirm =
                await Swal.fire({

                    icon: 'question',

                    title: 'Kirim Keluhan?',

                    text:
                        'Pastikan data keluhan sudah benar',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Kirim',

                    cancelButtonText: 'Batal',

                    reverseButtons: true

                });

            if (!confirm.isConfirmed) {

                return;

            }

            this.submitting = true;

            let formData =
                new FormData();

            formData.append(
                'judul',
                this.form.judul
            );

            formData.append(
                'deskripsi',
                this.form.deskripsi
            );

            // MULTIPLE FILE
            this.form.lampiran.forEach(file => {

                formData.append(
                    'lampiran[]',
                    file
                );

            });

            try {

                const res =
                    await fetch('/keluhan', {

                        method: 'POST',

                        headers: {

                            'X-CSRF-TOKEN':
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content

                        },

                        body: formData

                    });

                const contentType =
                    res.headers.get('content-type');

                let data;

                if (
                    contentType &&
                    contentType.includes('application/json')
                ) {

                    data = await res.json();

                } else {

                    const text =
                        await res.text();

                    console.error(
                        'Response bukan JSON:',
                        text
                    );

                    throw {
                        message:
                            'Server error (response bukan JSON)'
                    };

                }

                if (!res.ok) throw data;

                // SUCCESS
                Swal.fire({

                    icon: 'success',

                    title: 'Berhasil!',

                    text:
                        'Keluhan berhasil dikirim',

                    timer: 1500,

                    showConfirmButton: false

                });

                // RESET
                this.form.judul = '';
                this.form.deskripsi = '';
                this.form.lampiran = [];

                this.$refs.fileInput.value = null;

            } catch (err) {

                let message =
                    'Terjadi kesalahan';

                // VALIDATION
                if (err.errors) {

                    message =
                        Object.values(err.errors)
                            .flat()
                            .join('<br>');

                }

                // CUSTOM ERROR
                if (
                    err.message &&
                    !err.errors
                ) {

                    message =
                        err.message;

                }

                Swal.fire({

                    icon: 'error',

                    title: 'Gagal',

                    html: message

                });

            } finally {

                this.submitting = false;

            }

        }

    }

}

</script>

@endsection