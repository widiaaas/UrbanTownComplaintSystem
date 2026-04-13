@extends('layouts.app')

@section('title', 'Ajukan Keluhan')

@section('content')

<div class="p-6 flex justify-center">

    <div class="bg-white rounded-lg shadow p-6 w-full max-w-lg space-y-6">

        <h1 class="text-2xl font-bold text-gray-900 text-center">Ajukan Keluhan</h1>

        <form x-data="keluhanForm()"
              @submit.prevent="submitForm"
              enctype="multipart/form-data"
              class="space-y-4">

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Judul Keluhan</label>
                <input type="text"
                       x-model="form.judul"
                       class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi Keluhan</label>
                <textarea rows="4"
                          x-model="form.deskripsi"
                          placeholder="Jelaskan keluhan Anda secara detail..."
                          class="w-full mt-1 border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"></textarea>
            </div>

            {{-- Lampiran --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Lampiran Keluhan <span class="text-gray-400">(opsional)</span>
                </label>
                <input type="file"
                    multiple
                    @change="handleFile"
                    x-ref="fileInput"
                    class="w-full mt-1 text-sm">
                       <p class="text-xs text-gray-500 mt-1">
                            Format JPG, PNG, PDF (bisa lebih dari 1 file, max 1MB per file)
                        </p>
            </div>

            {{-- ================= PREVIEW FILE ================= --}}
            <div x-show="form.lampiran.length > 0" class="mt-4 space-y-2">

                <p class="text-sm font-semibold text-gray-700">Preview Lampiran:</p>

                <template x-for="(file, index) in form.lampiran" :key="index">
                    <div class="flex items-center justify-between border rounded-lg p-2">

                    <div class="flex items-center space-x-3 cursor-pointer"
                        @click="previewFile(file)">

                            {{-- IMAGE PREVIEW --}}
                            <template x-if="file.type.startsWith('image/')">
                                <img :src="URL.createObjectURL(file)"
                                    class="w-12 h-12 object-cover rounded">
                            </template>

                            {{-- PDF / OTHER --}}
                            <template x-if="!file.type.startsWith('image/')">
                                <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded">
                                    📄
                                </div>
                            </template>

                            <div>
                                <p class="text-sm font-medium text-gray-800" x-text="file.name"></p>
                                <p class="text-xs text-gray-500" x-text="(file.size/1024).toFixed(1) + ' KB'"></p>
                            </div>
                        </div>

                        {{-- DELETE BUTTON --}}
                        <button type="button"
                                @click="removeFile(index)"
                                class="text-red-500 hover:text-red-700 text-sm">
                            Hapus
                        </button>

                    </div>
                </template>

            </div>

            {{-- ================= MODAL PREVIEW ================= --}}
            <div x-show="preview.open"
                x-cloak
                class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">

                <div class="bg-white rounded-lg p-4 max-w-3xl w-full relative">

                    {{-- CLOSE --}}
                    <button type="button"
                        @click="closePreview"
                        class="absolute top-2 right-2 text-gray-600 hover:text-black text-lg">
                        ✕
                    </button>

                    {{-- IMAGE --}}
                    <template x-if="preview.type === 'image'">
                        <img :src="preview.url" class="w-full max-h-[80vh] object-contain">
                    </template>

                    {{-- PDF --}}
                    <template x-if="preview.type === 'pdf'">
                        <iframe :src="preview.url"
                                class="w-full h-[80vh]"></iframe>
                    </template>

                </div>
            </div>

            {{-- Button --}}
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

        previewFile(file) {
            const url = URL.createObjectURL(file);

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

            // OTHER → download / open tab
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
            const files = Array.from(e.target.files);

            // 🔥 gabung file lama + baru
            this.form.lampiran = [...this.form.lampiran, ...files];
        },
        removeFile(index) {
            this.form.lampiran.splice(index, 1);
        },

        // ================= SUBMIT =================
        async submitForm() {
            this.submitting = true;

            let formData = new FormData();
            formData.append('judul', this.form.judul);
            formData.append('deskripsi', this.form.deskripsi);

            // 🔥 multiple file
            this.form.lampiran.forEach(file => {
                formData.append('lampiran[]', file);
            });

            try {
                const res = await fetch('/keluhan', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const contentType = res.headers.get('content-type');
                let data;

                // 🔥 safe parse
                if (contentType && contentType.includes('application/json')) {
                    data = await res.json();
                } else {
                    const text = await res.text();
                    console.error('Response bukan JSON:', text);

                    throw { message: 'Server error (response bukan JSON)' };
                }

                // 🔥 kalau error dari backend
                if (!res.ok) throw data;

                // ================= SUCCESS =================
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Keluhan berhasil dikirim',
                    timer: 1500,
                    showConfirmButton: false
                });

                // reset form
                this.form.judul = '';
                this.form.deskripsi = '';
                this.form.lampiran = [];

                // reset input file
                this.$refs.fileInput.value = null;

            } catch (err) {

                let message = 'Terjadi kesalahan';

                // 🔥 error validasi laravel
                if (err.errors) {
                    message = Object.values(err.errors)
                        .flat()
                        .join('<br>');
                }

                // 🔥 error custom backend
                if (err.message && !err.errors) {
                    message = err.message;
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