<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>

    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100">

<div class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white p-6 rounded-2xl shadow-lg border space-y-6">

        {{-- HEADER --}}
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-800">
                🔐 Ganti Password
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Demi keamanan, silakan ubah password Anda terlebih dahulu
            </p>
        </div>

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 text-sm p-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 text-sm p-3 rounded-lg">
                Terjadi kesalahan. Periksa kembali input Anda.
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="/ganti-password" class="space-y-5">
            @csrf

            {{-- PASSWORD --}}
            <div>
                <label class="text-sm font-medium text-gray-700">
                    Password Baru
                </label>

                <div class="relative mt-1">
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200 pr-10"
                        required>

                    <button type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">

                        <span class="icon-wrapper">
                            <x-icons.eye class="w-5 h-5" />
                        </span>

                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <p class="text-xs text-gray-400 mt-1">
                    Minimal 6 karakter, mengandung huruf besar dan angka
                </p>
            </div>

            {{-- CONFIRM --}}
            <div>
                <label class="text-sm font-medium text-gray-700">
                    Konfirmasi Password
                </label>

                <div class="relative mt-1">
                    <input type="password" name="password_confirmation" id="confirm"
                        class="w-full px-3 py-2 border rounded-lg focus:ring focus:ring-blue-200 pr-10"
                        required>

                    <button type="button"
                        onclick="togglePassword('confirm', this)"
                        class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">

                        <span class="icon-wrapper">
                            <x-icons.eye class="w-5 h-5" />
                        </span>

                    </button>
                </div>
            </div>

            {{-- BUTTON --}}
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition shadow-sm font-medium">
                Simpan Password
            </button>

        </form>

    </div>

</div>

{{-- SCRIPT --}}
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const wrapper = btn.querySelector('.icon-wrapper');

    if (input.type === 'password') {
        input.type = 'text';
        wrapper.innerHTML = `{!! str_replace('"','\\"', view('components.icons.eyeSlash')->render()) !!}`;
    } else {
        input.type = 'password';
        wrapper.innerHTML = `{!! str_replace('"','\\"', view('components.icons.eye')->render()) !!}`;
    }
}
</script>

</body>
</html>