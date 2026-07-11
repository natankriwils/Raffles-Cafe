<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Raffles Cafe POS</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#fcf8f5] flex items-center justify-center h-screen font-sans">

    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-md border border-amber-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#8B4513]">RC</h1>
            <h2 class="text-xl font-semibold text-gray-700 mt-2">Raffles-Cafe</h2>
            <p class="text-sm text-gray-400">Silakan login untuk mengakses sistem kasir</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 bg-gray-50 text-gray-700"
                    placeholder="masukkan email kasir...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 bg-gray-50 text-gray-700">
            </div>

            <div class="flex items-center justify-between text-sm text-gray-500">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" class="rounded text-amber-600 focus:ring-amber-500">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" 
                class="w-full bg-[#8B4513] hover:bg-[#6e360f] text-white font-semibold py-3 rounded-xl transition duration-200 shadow-sm">
                Masuk Sistem
            </button>
        </form>
    </div>

</body>
</html>