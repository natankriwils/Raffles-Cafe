<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Coffee POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        body, button, input, select, textarea { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#FAF8F5] antialiased h-screen overflow-hidden text-[#1C2220] selection:bg-[#244C38] selection:text-white">
    <div class="flex h-full">

        <div class="w-64 bg-white flex flex-col justify-between py-6 px-4 flex-shrink-0 border-r border-[#EAE7E1] shadow-sm z-20">
            <div>
                <div class="flex items-center gap-3 px-2 mb-8 border-b border-[#FAF8F5] pb-5">
                    <img src="{{ asset('images/Logo-Kopi-Daily.png') }}" alt="Daily Coffee" class="w-12 h-12 object-contain rounded-xl">
                    <div>
                        <span class="font-extrabold text-base text-[#1C2220] tracking-tight block">Daily Coffee</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#244C38] bg-[#EAF2EE] px-1.5 py-0.5 rounded">POS System</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('kasir.index') }}"
                       class="w-full py-3 px-4 text-xs font-bold tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center gap-3 
                       {{ request()->routeIs('kasir.index') 
                            ? 'bg-[#244C38] text-white shadow-md shadow-[#244C38]/15 font-extrabold border border-transparent' 
                            : 'text-[#4A524F] bg-white border border-[#EAE7E1] hover:bg-[#FAF8F5] hover:border-[#244C38] hover:text-[#1C2220]' }}">
                        <div class="p-1.5 rounded-lg bg-[#FAF8F5] group-hover:bg-white shrink-0 border border-[#FAF8F5]">
                            <img src="{{ asset('images/kasir.png') }}" alt="Cashier" class="w-4 h-4 object-contain">
                        </div>
                        <span>Cashier</span>
                    </a>

                    <a href="{{ route('riwayat.index') }}"
                       class="w-full py-3 px-4 text-xs font-bold tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center gap-3 
                       {{ request()->routeIs('riwayat.index') 
                            ? 'bg-[#244C38] text-white shadow-md shadow-[#244C38]/15 font-extrabold border border-transparent' 
                            : 'text-[#4A524F] bg-white border border-[#EAE7E1] hover:bg-[#FAF8F5] hover:border-[#244C38] hover:text-[#1C2220]' }}">
                        <div class="p-1.5 rounded-lg bg-[#FAF8F5] group-hover:bg-white shrink-0 border border-[#FAF8F5]">
                            <img src="{{ asset('images/riwayat-transaksi.png') }}" alt="Order History" class="w-5 h-5 object-contain">
                        </div>
                        <span>Order History</span>
                    </a>

                    <a href="{{ route('dashboard.index') }}"
                       class="w-full py-3 px-4 text-xs font-bold tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center gap-3 
                       {{ request()->routeIs('dashboard.index') 
                            ? 'bg-[#244C38] text-white shadow-md shadow-[#244C38]/15 font-extrabold border border-transparent' 
                            : 'text-[#4A524F] bg-white border border-[#EAE7E1] hover:bg-[#FAF8F5] hover:border-[#244C38] hover:text-[#1C2220]' }}">
                        <div class="p-1.5 rounded-lg bg-[#FAF8F5] group-hover:bg-white shrink-0 border border-[#FAF8F5]">
                            <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard" class="w-5 h-5 object-contain">
                        </div>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('kelola-menu.index') }}"
                       class="w-full py-3 px-4 text-xs font-bold tracking-wider uppercase rounded-xl transition-all duration-200 flex items-center gap-3 
                       {{ request()->routeIs('kelola-menu.*') 
                            ? 'bg-[#244C38] text-white shadow-md shadow-[#244C38]/15 font-extrabold border border-transparent' 
                            : 'text-[#4A524F] bg-white border border-[#EAE7E1] hover:bg-[#FAF8F5] hover:border-[#244C38] hover:text-[#1C2220]' }}">
                        <div class="p-1.5 rounded-lg bg-[#FAF8F5] group-hover:bg-white shrink-0 border border-[#FAF8F5]">
                            <img src="{{ asset('images/editmenu.png') }}" alt="Manage Menu" class="w-4 h-4 object-contain">
                        </div>
                        <span>Manage Menu</span>
                    </a>
                </div>
            </div>

            <div class="border-t border-[#FAF8F5] pt-4 px-2">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-bold text-[#1C2220]">Natan (Cashier)</div>
                    <div class="w-2 h-2 rounded-full bg-[#244C38]"></div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 text-center text-[#D9534F] bg-[#FFF5F5] hover:bg-[#FFE0E0] rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 flex overflow-hidden">
            @yield('content')
        </div>
    </div>
</body>
</html>