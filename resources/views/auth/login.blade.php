<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Daily Coffee POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, button, input { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#FAF8F5] flex items-center justify-center h-screen selection:bg-[#244C38] selection:text-white">

    <div class="bg-white p-10 rounded-3xl w-full max-w-md border border-[#EAE7E1] shadow-[0_10px_30px_rgba(28,34,32,0.03)] text-center">
        
        <div class="mb-6 flex flex-col items-center justify-center">
            <img src="{{ asset('images/Logo-Kopi-Daily.png') }}" alt="Daily Coffee Logo" class="w-28 h-28 object-contain mb-2">
            <h1 class="text-2xl font-extrabold text-[#1C2220] tracking-tight">Daily Coffee</h1>
            <p class="text-xs font-semibold text-[#7A827E] mt-1 tracking-widest uppercase">Point of Sales System</p>
        </div>

        @if ($errors->any())
            <div class="bg-[#FFF5F5] text-[#D9534F] border border-[#FFE0E0] rounded-xl p-3 text-sm mb-6 text-center font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-5 text-left">
            @csrf

            <div>
                <label class="block text-xs font-bold text-[#4A524F] uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15 bg-[#FAF8F5]/50 text-[#1C2220] font-medium transition-all"
                    placeholder="cashier@dailycoffee.com">
            </div>

            <div>
                <label class="block text-xs font-bold text-[#4A524F] uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15 bg-[#FAF8F5]/50 text-[#1C2220] font-medium transition-all"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-sm text-[#7A827E]">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-[#D5D2CC] text-[#244C38] focus:ring-[#244C38]">
                    <span class="font-medium text-xs">Remember Me</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-[#244C38] hover:bg-[#1A3829] text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-[#244C38]/15 tracking-wider text-xs uppercase">
                Sign In
            </button>
        </form>
    </div>

</body>
</html>