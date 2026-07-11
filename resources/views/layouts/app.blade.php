<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raffles-Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-DUMMYKEY"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body class="bg-gray-100 font-sans antialiased h-screen overflow-hidden">
    <div class="flex h-full">
        <div class="w-20 bg-amber-900 flex flex-col items-center py-6 justify-between text-white flex-shrink-0">
            <div class="font-bold text-xl tracking-wider">Raffles Cafe</div>
            <div class="space-y-8 flex flex-col items-center">
                <a href="{{ route('kasir.index') }}"
                   class="p-3 {{ request()->routeIs('kasir.index') ? 'bg-amber-800 text-white shadow rounded-xl' : 'text-amber-200 hover:text-white' }} block">
                    Kasir
                </a>

                <a href="{{ route('riwayat.index') }}"
                   class="p-3 {{ request()->routeIs('riwayat.index') ? 'bg-amber-800 text-white shadow rounded-xl' : 'text-amber-200 hover:text-white' }} block">
                    Riwayat Transaksi
                </a>

                <a href="{{ route('dashboard.index') }}"
                   class="p-3 {{ request()->routeIs('dashboard.index') ? 'bg-amber-800 text-white shadow rounded-xl' : 'text-amber-200 hover:text-white' }} block">
                    Dashboard
                </a>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="text-sm">
                @csrf
                <button type="submit" class="p-2 text-amber-200 hover:text-white">Logout</button>
            </form>

        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex overflow-hidden">
            @yield('content')
        </div>
    </div>
</body>
</html>