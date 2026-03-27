<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind --}}
    @vite('resources/css/app.css')

    {{-- Alpine --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 overflow-x-hidden">

<div x-data="{ sidebarOpen: false }" class="flex h-screen">

    {{-- SIDEBAR --}}
    @include('components.sidebar')

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col">

        {{-- NAVBAR --}}
        @include('components.navbar')

        {{-- CONTENT --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>