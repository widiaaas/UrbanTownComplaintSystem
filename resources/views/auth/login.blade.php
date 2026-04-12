<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Unit Complaint System</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

<div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
    
    <!-- Logo -->
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-20 object-contain">
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Unit Complaint System</h1>
        <p class="text-gray-500 text-sm">Sistem Keluhan Urban Town Serpong</p>
    </div>

    <!-- Global Error -->
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
        @csrf

        <!-- Username -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <!-- icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A7 7 0 0112 14a7 7 0 016.879 3.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <input 
                    type="text" 
                    name="username" 
                    value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    class="w-full pl-10 pr-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none @error('username') border-red-500 @enderror"
                    required autofocus autocomplete="username"
                >
            </div>
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 11c-1.657 0-3 1.343-3 3v3h6v-3c0-1.657-1.343-3-3-3zM7 11V7a5 5 0 1110 0v4"/>
                    </svg>
                </span>
                <input 
                    type="password" 
                    name="password"
                    placeholder="Masukkan password"
                    class="w-full pl-10 pr-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none @error('password') border-red-500 @enderror"
                    required autocomplete="current-password"
                >
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition duration-200">
            Masuk
        </button>
    </form>

</div>

</body>
</html>