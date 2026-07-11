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
                <a href="#" class="p-3 bg-amber-800 rounded-xl block text-white shadow">Kasir</a>
                <a href="#" class="p-3 text-amber-200 hover:text-white block">Riwayat Transaksi</a>
                <a href="#" class="p-3 text-amber-200 hover:text-white block">Kelola Data</a>
            </div>
            <div class="text-sm">Logout</div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex overflow-hidden">
            @yield('content')
        </div>
    </div>
</body>
</html>