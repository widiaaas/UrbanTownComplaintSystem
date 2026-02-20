<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Unit Complaint System</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
    <!-- Logo -->
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <div class="p-3 rounded-full bg-white flex items-center justify-center">
                 <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-20 object-contain">
            </div>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Unit Complaint System</h1>
        <p class="text-gray-600">Sistem Keluhan Urban Town Serpong</p>
    </div>

    <!-- Form UI Only -->
    <form class="space-y-6">
        <!-- Username -->
        <div class="relative">
            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 0112 14a7 7 0 016.879 3.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Masukkan username" class="block w-full pl-10"/>
            </div>
        </div>

        <!-- Password -->
        <div class="relative">
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c-1.657 0-3 1.343-3 3v3h6v-3c0-1.657-1.343-3-3-3zM7 11V7a5 5 0 1110 0v4"/>
                    </svg>
                </div>
                <input type="password" placeholder="Masukkan password" class="block w-full pl-10"/>
            </div>
        </div>

        <button type="submit"
            class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
            Masuk
        </button>
    </form>

    <!-- Demo Credentials
    <div class="mt-8 pt-6 border-t border-gray-200 text-xs text-center">
        <p class="mb-2">Demo Credentials:</p>
        <div class="grid grid-cols-2 gap-2">
            <div class="bg-gray-50 p-2 rounded">Admin<br>admin / admin123</div>
            <div class="bg-gray-50 p-2 rounded">Tenant Relation<br>tr001 / tr123</div>
            <div class="bg-gray-50 p-2 rounded">Departemen<br>dept001 / dept123</div>
            <div class="bg-gray-50 p-2 rounded">Penghuni<br>unit101 / unit123</div>
        </div>
    </div> -->
</div>

</body>
</html>
