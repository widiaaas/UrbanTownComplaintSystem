<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Login | Unit Complaint System
    </title>

    @vite('resources/css/app.css')

    {{-- ALPINE JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body
    class="min-h-screen
    bg-cover bg-center bg-no-repeat
    flex items-center justify-center lg:justify-end
    relative overflow-hidden"
    style="background-image: url('{{ asset('images/gedung.jpg') }}');">

    {{-- OVERLAY --}}
    <div
        class="absolute inset-0
        bg-black/20">
    </div>

    {{-- LEFT PANEL / HORDEN --}}
    <div
        class="hidden lg:flex
        absolute left-0 top-0 bottom-0
        w-[55%]
        
        bg-gradient-to-r
        from-black/80 via-black/60 to-transparent
        backdrop-blur-[2px]
        z-10">

        <div
            class="flex flex-col justify-end
            p-14 text-white">

            {{-- SMALL BADGE --}}
            <div
                class="mt-8 inline-flex items-center
                gap-2 text-sm text-white/80">

                <div
                    class="w-2 h-2 rounded-full
                    bg-emerald-400 animate-pulse">
                </div>

                Urbantown Complaint Management System

            </div>

        </div>

    </div>

    {{-- LOGIN CARD --}}
    <div
        x-data="{ showPassword: false }"
        class="relative z-20
        w-full max-w-md
        mr-0 lg:mr-32
        mx-4
        bg-white/85 backdrop-blur-xl
        rounded-3xl shadow-2xl
        border border-white/30
        p-8">

        {{-- LOGO --}}
        <div class="text-center mb-8">

            <div class="flex justify-center mb-5">

                <div
                    class="w-24 h-24 rounded-3xl
                    bg-white/80 shadow-lg
                    flex items-center justify-center">

                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Logo"
                        class="h-20 w-20 object-contain">

                </div>

            </div>

            {{-- TITLE --}}
            <h1
                class="text-3xl font-black
                tracking-tight
                text-gray-900">

                Selamat Datang

            </h1>

            {{-- SUBTITLE --}}
            <p
                class="text-gray-700 text-sm
                mt-3 tracking-wide">

                Sistem Keluhan Urbantown Serpong

            </p>

        </div>

        {{-- GLOBAL ERROR --}}
        @if ($errors->any())

            <div
                class="mb-5 rounded-2xl
                border border-red-200
                bg-red-50 px-4 py-3
                text-sm text-red-700">

                {{ $errors->first() }}

            </div>

        @endif

        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('login.post') }}"
            class="space-y-5">

            @csrf

            {{-- USERNAME --}}
            <div>

                <label
                    class="block text-sm
                    font-semibold text-gray-700 mb-1.5">

                    Username

                </label>

                <div class="relative">

                    {{-- ICON --}}
                    <span
                        class="absolute inset-y-0 left-0
                        flex items-center pl-3
                        text-gray-400">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5.121 17.804A7 7 0 0112 14
                                a7 7 0 016.879 3.804M15 11a3 3
                                0 11-6 0 3 3 0 016 0z"/>

                        </svg>

                    </span>

                    {{-- INPUT --}}
                    <input
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                        autofocus
                        class="w-full pl-10 pr-4 py-3
                        rounded-2xl border border-gray-200
                        bg-white/90
                        focus:ring-2 focus:ring-blue-500
                        focus:border-blue-500
                        focus:outline-none
                        transition
                        @error('username')
                        border-red-500
                        @enderror">

                </div>

                @error('username')

                    <p class="text-red-500 text-xs mt-1.5">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- PASSWORD --}}
            <div>

                <label
                    class="block text-sm
                    font-semibold text-gray-700 mb-1.5">

                    Password

                </label>

                <div class="relative">

                    {{-- LOCK ICON --}}
                    <span
                        class="absolute inset-y-0 left-0
                        flex items-center pl-3
                        text-gray-400">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
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

                    </span>

                    {{-- INPUT --}}
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                        class="w-full pl-10 pr-11 py-3
                        rounded-2xl border border-gray-200
                        bg-white/90
                        focus:ring-2 focus:ring-blue-500
                        focus:border-blue-500
                        focus:outline-none
                        transition
                        @error('password')
                        border-red-500
                        @enderror">

                    {{-- EYE BUTTON --}}
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0
                        w-11 flex items-center justify-center
                        text-gray-400
                        hover:text-gray-700
                        transition">

                        {{-- EYE --}}
                        <template x-if="!showPassword">

                            <div>

                                @include('components.icons.eye')

                            </div>

                        </template>

                        {{-- EYE OFF --}}
                        <template x-if="showPassword">

                            <div>

                                @include('components.icons.eyeSlash')

                            </div>

                        </template>

                    </button>

                </div>

                @error('password')

                    <p class="text-red-500 text-xs mt-1.5">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            {{-- LOGIN BUTTON --}}
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

                Masuk

            </button>

        </form>

    </div>

</body>

</html>