<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title')
    </title>

    {{-- CSRF --}}
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    {{-- TAILWIND --}}
    @vite('resources/css/app.css')

    {{-- ALPINE --}}
    <script
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
        defer>
    </script>

    {{-- SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body
    class="bg-[#f3f6fb]
    overflow-hidden">

    {{-- APP --}}
    <div
        x-data="{ sidebarOpen: false }"
        class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        @include('components.sidebar')

        {{-- MAIN --}}
        <div
            class="flex-1 flex flex-col
            min-w-0">

            {{-- NAVBAR --}}
            <header
                class="sticky top-0 z-20
                bg-white/80 backdrop-blur-xl
                border-b border-gray-200/70
                shadow-sm">

                @include('components.navbar')

            </header>

            {{-- CONTENT --}}
            <main
                class="flex-1 overflow-y-auto
                px-6 py-5 min-w-0">

                <div
                    class="w-full min-h-full">

                    @yield('content')

                </div>

            </main>

        </div>

    </div>

</body>

</html>