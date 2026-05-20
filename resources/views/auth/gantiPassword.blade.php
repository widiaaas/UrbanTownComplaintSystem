<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Ganti Kata Sandi
    </title>

    @vite('resources/css/app.css')

</head>

<body
    class="min-h-screen
    bg-gradient-to-br
    from-slate-100 via-blue-50 to-sky-100
    flex items-center justify-center
    px-4 py-8">

    <div
        class="w-full max-w-lg
        bg-white/90 backdrop-blur-xl
        border border-white/40
        rounded-3xl shadow-2xl
        overflow-hidden">

        {{-- TOP ACCENT --}}
        <div
            class="h-2 bg-gradient-to-r
            from-blue-500 via-sky-500 to-cyan-400">
        </div>

        <div class="p-8 space-y-6">

            {{-- HEADER --}}
            <div class="text-center">

                {{-- ICON --}}
                <div
                    class="mx-auto mb-5
                    w-20 h-20 rounded-3xl
                    bg-blue-50
                    flex items-center justify-center
                    shadow-inner">

                    <div
                        class="w-12 h-12 rounded-2xl
                        bg-blue-600 text-white
                        flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 11c-1.657 0-3 1.343-3 3v3h6v-3
                                c0-1.657-1.343-3-3-3zM7 11V7a5 5
                                0 1110 0v4"/>

                        </svg>

                    </div>

                </div>

                {{-- TITLE --}}
                <h1
                    class="text-3xl font-black
                    tracking-tight text-gray-900">

                    Ganti Kata Sandi

                </h1>

                {{-- SUBTITLE --}}
                <p
                    class="text-sm text-gray-500
                    mt-2 leading-relaxed">

                    Demi keamanan akun,
                    silakan ubah Kata Sandi Anda
                    terlebih dahulu sebelum melanjutkan

                </p>

            </div>

            {{-- SUCCESS --}}
            @if(session('success'))

                <div
                    class="rounded-2xl
                    border border-emerald-200
                    bg-emerald-50
                    px-4 py-3">

                    <p
                        class="text-sm text-emerald-700
                        font-medium">

                        {{ session('success') }}

                    </p>

                </div>

            @endif

            {{-- ERROR --}}
            @if ($errors->any())

                <div
                    class="rounded-2xl
                    border border-red-200
                    bg-red-50
                    px-4 py-3">

                    <p
                        class="text-sm text-red-700
                        font-medium">

                        Terjadi kesalahan.
                        Periksa kembali input Anda.

                    </p>

                </div>

            @endif

            {{-- FORM --}}
            <form
                method="POST"
                action="/ganti-password"
                class="space-y-5">

                @csrf

                {{-- PASSWORD --}}
                <div>

                    <label
                        class="block text-sm
                        font-semibold text-gray-700 mb-2">

                        Kata Sandi Baru

                    </label>

                    <div class="relative">

                        {{-- INPUT --}}
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Masukkan password baru"
                            required
                            class="w-full px-4 py-3 pr-11
                            rounded-2xl
                            border border-gray-200
                            bg-white
                            focus:ring-2 focus:ring-blue-500
                            focus:border-blue-500
                            focus:outline-none
                            transition">

                        {{-- EYE --}}
                        <button
                            type="button"
                            onclick="togglePassword('password', this)"
                            class="absolute inset-y-0 right-0
                            flex items-center pr-4
                            text-gray-400
                            hover:text-gray-700
                            transition">

                            <span class="icon-wrapper">

                                @include('components.icons.eye')

                            </span>

                        </button>

                    </div>

                    @error('password')

                        <p class="text-red-500 text-xs mt-1.5">
                            {{ $message }}
                        </p>

                    @enderror

                    {{-- HINT --}}
                    <div
                        class="mt-2 text-xs
                        text-gray-400 leading-relaxed">

                        Gunakan minimal 6 karakter
                        dengan kombinasi huruf besar
                        dan angka

                    </div>

                </div>

                {{-- CONFIRM --}}
                <div>

                    <label
                        class="block text-sm
                        font-semibold text-gray-700 mb-2">

                        Konfirmasi Kata Sandi

                    </label>

                    <div class="relative">

                        {{-- INPUT --}}
                        <input
                            type="password"
                            name="password_confirmation"
                            id="confirm"
                            placeholder="Ulangi password baru"
                            required
                            class="w-full px-4 py-3 pr-11
                            rounded-2xl
                            border border-gray-200
                            bg-white
                            focus:ring-2 focus:ring-blue-500
                            focus:border-blue-500
                            focus:outline-none
                            transition">

                        {{-- EYE --}}
                        <button
                            type="button"
                            onclick="togglePassword('confirm', this)"
                            class="absolute inset-y-0 right-0
                            flex items-center pr-4
                            text-gray-400
                            hover:text-gray-700
                            transition">

                            <span class="icon-wrapper">

                                @include('components.icons.eye')

                            </span>

                        </button>

                    </div>

                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="w-full py-3 rounded-2xl
                    bg-blue-600 text-white
                    font-semibold tracking-wide
                    shadow-lg shadow-blue-500/20
                    hover:bg-blue-700
                    hover:shadow-blue-500/30
                    active:scale-[0.99]
                    transition">

                    Simpan Kata Sandi

                </button>

            </form>

        </div>

    </div>

    {{-- SCRIPT --}}
    <script>

        function togglePassword(id, btn) {

            const input =
                document.getElementById(id);

            const wrapper =
                btn.querySelector('.icon-wrapper');

            if (input.type === 'password') {

                input.type = 'text';

                wrapper.innerHTML =
                    `{!! str_replace('"','\\"', view('components.icons.eyeSlash')->render()) !!}`;

            } else {

                input.type = 'password';

                wrapper.innerHTML =
                    `{!! str_replace('"','\\"', view('components.icons.eye')->render()) !!}`;

            }

        }

    </script>

</body>

</html>