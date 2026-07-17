@extends('layouts.app')

@section('content')
<div class="flex-1 flex overflow-hidden">
    <div class="flex-1 overflow-y-auto p-8 bg-[#FAF8F5]">
        
        <div class="flex items-center gap-4 mb-8">
            <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard Icon" class="w-12 h-12 object-contain">
            <div>
                <h1 class="text-3xl font-extrabold text-[#1C2220] tracking-tight">Daily Coffee Analytics</h1>
                <p class="text-xs font-semibold text-[#7A827E] tracking-widest uppercase mt-0.5">Real-time overview of cafe sales and performance</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            
            <div class="bg-white rounded-2xl border border-[#EAE7E1] p-5 shadow-sm hover:border-[#244C38] transition-all flex flex-col justify-between">
                <div class="text-[11px] font-bold text-[#7A827E] tracking-widest uppercase">Today's Revenue</div>
                <div class="text-2xl font-extrabold text-[#1C2220] mt-3">
                    Rp {{ number_format((float)$todayTotal, 0, ',', '.') }}
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#EAE7E1] p-5 shadow-sm hover:border-[#244C38] transition-all flex flex-col justify-between">
                <div class="text-[11px] font-bold text-[#7A827E] tracking-widest uppercase">Weekly Revenue</div>
                <div class="text-2xl font-extrabold text-[#244C38] mt-3">
                    Rp {{ number_format((float)$weeklyTotal, 0, ',', '.') }}
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#EAE7E1] p-5 shadow-sm hover:border-[#244C38] transition-all flex flex-col justify-between">
                <div class="text-[11px] font-bold text-[#7A827E] tracking-widest uppercase">Today's Customers</div>
                <div class="text-2xl font-extrabold text-[#1C2220] mt-3">
                    {{ $todayCustomers }} <span class="text-xs font-semibold text-[#7A827E]">Orders</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#EAE7E1] p-5 shadow-sm hover:border-[#244C38] transition-all flex flex-col justify-between">
                <div class="text-[11px] font-bold text-[#7A827E] tracking-widest uppercase">Transaction Status</div>
                <div class="flex items-center gap-2 mt-3">
                    <div class="flex items-center gap-1.5 bg-[#EAF2EE] text-[#244C38] px-2.5 py-1 rounded-lg text-xs font-extrabold border border-[#C5DCD0]">
                        <img src="{{ asset('images/success.png') }}" class="w-3.5 h-3.5 object-contain" alt="Success">
                        <span>{{ $todayCountSuccess }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-[#FFF9E6] text-[#D99000] px-2.5 py-1 rounded-lg text-xs font-extrabold border border-[#FFE8A3]">
                        <img src="{{ asset('images/pending.png') }}" class="w-3.5 h-3.5 object-contain" alt="Pending">
                        <span>{{ $todayCountPending }}</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            
            <div class="bg-white rounded-2xl border border-[#EAE7E1] p-6 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-extrabold text-[#1C2220] text-base">Weekly Revenue Trend</h2>
                        <p class="text-xs text-[#7A827E] font-medium mt-0.5">Last 7 days income distribution</p>
                    </div>
                    <span class="text-[10px] font-extrabold bg-[#FAF8F5] text-[#244C38] px-3 py-1 rounded-full border border-[#EAE7E1] tracking-wider uppercase">Bar Chart</span>
                </div>
                <div class="h-64 w-full">
                    <canvas id="weeklyColumnChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-[#EAE7E1] p-6 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-extrabold text-[#1C2220] text-base">Monthly Revenue Trend</h2>
                        <p class="text-xs text-[#7A827E] font-medium mt-0.5">30 days growth trajectory</p>
                    </div>
                    <span class="text-[10px] font-extrabold bg-[#FAF8F5] text-[#244C38] px-3 py-1 rounded-full border border-[#EAE7E1] tracking-wider uppercase">Line Chart</span>
                </div>
                <div class="h-64 w-full">
                    <canvas id="monthlyLineChart"></canvas>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
            
            <div class="bg-white rounded-2xl border border-[#EAE7E1] p-6 shadow-sm lg:col-span-3 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="font-extrabold text-[#1C2220] text-base">Transaction Description</h2>
                            <p class="text-xs text-[#7A827E] font-medium mt-0.5">Live monitoring of latest 7 cafe transactions</p>
                        </div>
                        <span class="text-[10px] font-bold text-[#7A827E] bg-[#FAF8F5] px-2.5 py-1 rounded-lg border border-[#EAE7E1] uppercase">Live Feed</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="bg-[#FAF8F5] text-[#7A827E] font-bold uppercase tracking-wider border-b border-[#EAE7E1]">
                                <tr>
                                    <th class="py-2.5 px-3">Order #</th>
                                    <th class="py-2.5 px-3">Customer</th>
                                    <th class="py-2.5 px-3">Method</th>
                                    <th class="py-2.5 px-3">Status</th>
                                    <th class="py-2.5 px-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#FAF8F5] font-medium">
                                @forelse($recentOrders as $order)
                                <tr class="hover:bg-[#FAF8F5]/50 transition-colors">
                                    <td class="py-2.5 px-3 font-mono font-bold text-[#1C2220]">{{ $order->order_number }}</td>
                                    <td class="py-2.5 px-3 text-[#1C2220] font-semibold">{{ $order->customer_name ?? '-' }}</td>
                                    <td class="py-2.5 px-3">
                                        @if(strtolower($order->payment_method) == 'cash')
                                            <span class="inline-flex items-center gap-1 font-bold text-[#4A524F] bg-gray-100 px-2 py-0.5 rounded text-[10px] uppercase">
                                                <img src="{{ asset('images/uang.png') }}" class="w-3 h-3 object-contain" alt="Cash"> Cash
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 font-bold text-[#244C38] bg-[#EAF2EE] px-2 py-0.5 rounded text-[10px] uppercase">
                                                <img src="{{ asset('images/e-wallet.png') }}" class="w-3 h-3 object-contain" alt="E-Wallet"> E-Wallet
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @if($order->status === 'completed')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-[#244C38] bg-[#EAF2EE] px-2 py-0.5 rounded-full border border-[#C5DCD0] uppercase">
                                                <img src="{{ asset('images/success.png') }}" class="w-2.5 h-2.5 object-contain" alt="Success"> Completed
                                            </span>
                                        @elseif($order->status === 'pending')
                                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-[#D99000] bg-[#FFF9E6] px-2 py-0.5 rounded-full border border-[#FFE8A3] uppercase">
                                                <img src="{{ asset('images/pending.png') }}" class="w-2.5 h-2.5 object-contain" alt="Pending"> Pending
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-extrabold text-[#244C38]">Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 text-xs">No recent transactions recorded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-[#FAF8F5] text-right">
                    <a href="{{ route('riwayat.index') }}" class="text-[11px] font-extrabold text-[#244C38] hover:underline uppercase tracking-wider">View All Transaction History &rarr;</a>
                </div>
            </div>

            <div class="lg:col-span-1 flex flex-col gap-6">
                
                <div onclick="openModal('modalItems')" class="bg-white rounded-2xl border border-[#EAE7E1] p-5 shadow-sm flex-1 flex flex-col justify-between cursor-pointer hover:border-[#244C38] transition-all group">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h2 class="font-extrabold text-[#1C2220] text-sm group-hover:text-[#244C38] transition-colors">Most Bought Items</h2>
                                <p class="text-[11px] text-[#7A827E] font-medium mt-0.5">Top 4 specific menu</p>
                            </div>
                            <span class="text-[10px] font-extrabold text-[#244C38] bg-[#EAF2EE] px-2 py-1 rounded-md border border-[#C5DCD0] group-hover:bg-[#244C38] group-hover:text-white transition-all">View All ↗</span>
                        </div>

                        <div class="space-y-2.5">
                            @forelse($topItems as $item)
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-[#1C2220] leading-tight truncate pr-2">{{ $item['name'] }}</span>
                                <span class="text-[11px] font-extrabold bg-[#FAF8F5] text-[#244C38] px-2 py-0.5 rounded-md border border-[#EAE7E1] shrink-0">{{ $item['sold'] }} pcs</span>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400 text-center py-2">No item data yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div onclick="openModal('modalCategories')" class="bg-white rounded-2xl border border-[#EAE7E1] p-5 shadow-sm flex-1 flex flex-col justify-between cursor-pointer hover:border-[#244C38] transition-all group">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h2 class="font-extrabold text-[#1C2220] text-sm group-hover:text-[#244C38] transition-colors">Top Categories</h2>
                                <p class="text-[11px] text-[#7A827E] font-medium mt-0.5">Top 4 sales distribution</p>
                            </div>
                            <span class="text-[10px] font-extrabold text-[#244C38] bg-[#EAF2EE] px-2 py-1 rounded-md border border-[#C5DCD0] group-hover:bg-[#244C38] group-hover:text-white transition-all">View All ↗</span>
                        </div>

                        <div class="space-y-3">
                            @forelse($topCategories as $cat)
                            <div>
                                <div class="flex justify-between text-[11px] font-bold mb-1">
                                    <span class="text-[#1C2220]">{{ $cat['name'] }}</span>
                                    <span class="text-[#244C38] font-extrabold">{{ $cat['percentage'] }}%</span>
                                </div>
                                <div class="w-full bg-[#FAF8F5] h-1.5 rounded-full overflow-hidden border border-[#EAE7E1]">
                                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ $cat['percentage'] }}%; background-color: {{ $cat['color'] }};"></div>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400 text-center py-2">No category data yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="bg-gradient-to-r from-[#244C38] to-[#1D3D2D] text-white rounded-2xl p-6 md:p-7 shadow-lg shadow-[#244C38]/10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-[#2B5741] relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full pointer-events-none blur-xl"></div>

            <div class="relative z-10 max-w-xl">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-widest bg-white/15 text-white px-3 py-1 rounded-full backdrop-blur-sm border border-white/10">
                        <img src="{{ asset('images/uang.png') }}" class="w-3.5 h-3.5 object-contain" alt="Revenue">
                        Monthly Performance
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl font-extrabold mt-3 tracking-tight text-white">Total Monthly Revenue</h2>
            </div>

            <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/15 px-6 md:px-8 py-5 rounded-2xl text-left md:text-right w-full md:w-auto shrink-0 shadow-inner">
                <div class="text-[11px] font-bold text-[#EAE7E1] uppercase tracking-wider">
                    Current Month Total
                </div>
                <div class="text-2xl lg:text-3xl font-black text-white mt-1 tracking-tight">
                    Rp {{ number_format((float)$monthTotal, 0, ',', '.') }}
                </div>
            </div>
        </div>

    </div>
</div>

<div id="modalItems" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl border border-[#EAE7E1] w-full max-w-lg overflow-hidden shadow-2xl animate-fade-in-up flex flex-col max-h-[80vh]">
        <div class="p-6 bg-[#FAF8F5] border-b border-[#EAE7E1] flex items-center justify-between shrink-0">
            <div>
                <h3 class="font-extrabold text-lg text-[#1C2220]">Complete Most Bought Items</h3>
                <p class="text-xs text-[#7A827E] font-medium mt-0.5">Full ranking of all sold menu items</p>
            </div>
        </div>
        <div class="p-6 overflow-y-auto space-y-3 divide-y divide-[#FAF8F5]">
            @forelse($allTopItems as $index => $item)
            <div class="flex items-center justify-between pt-2.5 first:pt-0">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-[#FAF8F5] border border-[#EAE7E1] text-[#244C38] font-extrabold text-xs flex items-center justify-center shrink-0">{{ $index + 1 }}</span>
                    <span class="text-sm font-bold text-[#1C2220]">{{ $item['name'] }}</span>
                </div>
                <span class="text-xs font-extrabold bg-[#EAF2EE] text-[#244C38] px-2.5 py-1 rounded-lg border border-[#C5DCD0] shrink-0">{{ $item['sold'] }} pcs</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No item data found.</p>
            @endforelse
        </div>
        <div class="p-4 bg-[#FAF8F5] border-t border-[#EAE7E1] text-right shrink-0">
            <button onclick="closeModal('modalItems')" class="bg-[#244C38] text-white px-5 py-2 rounded-xl text-xs font-extrabold hover:bg-[#1D3D2D] transition-colors">Close</button>
        </div>
    </div>
</div>

<div id="modalCategories" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white rounded-2xl border border-[#EAE7E1] w-full max-w-lg overflow-hidden shadow-2xl animate-fade-in-up flex flex-col max-h-[80vh]">
        <div class="p-6 bg-[#FAF8F5] border-b border-[#EAE7E1] flex items-center justify-between shrink-0">
            <div>
                <h3 class="font-extrabold text-lg text-[#1C2220]">Complete Menu Categories</h3>
                <p class="text-xs text-[#7A827E] font-medium mt-0.5">Overall sales distribution across all categories</p>
            </div>
        </div>
        <div class="p-6 overflow-y-auto space-y-5">
            @forelse($allTopCategories as $cat)
            <div>
                <div class="flex justify-between text-sm font-bold mb-1.5">
                    <span class="text-[#1C2220]">{{ $cat['name'] }} <span class="text-xs font-medium text-[#7A827E]">({{ $cat['sold'] }} pcs)</span></span>
                    <span class="text-[#244C38] font-extrabold">{{ $cat['percentage'] }}%</span>
                </div>
                <div class="w-full bg-[#FAF8F5] h-2.5 rounded-full overflow-hidden border border-[#EAE7E1]">
                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ $cat['percentage'] }}%; background-color: {{ $cat['color'] }};"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No category data found.</p>
            @endforelse
        </div>
        <div class="p-4 bg-[#FAF8F5] border-t border-[#EAE7E1] text-right shrink-0">
            <button onclick="closeModal('modalCategories')" class="bg-[#244C38] text-white px-5 py-2 rounded-xl text-xs font-extrabold hover:bg-[#1D3D2D] transition-colors">Close</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        const modalItems = document.getElementById('modalItems');
        const modalCategories = document.getElementById('modalCategories');
        if (event.target === modalItems) closeModal('modalItems');
        if (event.target === modalCategories) closeModal('modalCategories');
    }

    const weeklyLabels = @json($dailyLabels);
    const weeklyAmounts = @json($dailyAmounts);
    const ctxWeekly = document.getElementById('weeklyColumnChart');
    if (ctxWeekly) {
        new Chart(ctxWeekly, {
            type: 'bar',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: weeklyAmounts,
                    backgroundColor: '#244C38',
                    hoverBackgroundColor: '#1C2220',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1C2220',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: (c) => 'Rp ' + new Intl.NumberFormat('id-ID').format(c.parsed.y || 0)
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#FAF8F5' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 10 },
                            color: '#7A827E',
                            callback: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#7A827E' }
                    }
                }
            }
        });
    }

    const monthlyLabels = @json($monthlyLabels);
    const monthlyAmounts = @json($monthlyAmounts);
    const ctxMonthly = document.getElementById('monthlyLineChart');
    if (ctxMonthly) {
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: monthlyAmounts,
                    borderColor: '#D99000',
                    backgroundColor: 'rgba(217, 144, 0, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 2,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#D99000',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1C2220',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: (c) => 'Rp ' + new Intl.NumberFormat('id-ID').format(c.parsed.y || 0)
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#FAF8F5' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 10 },
                            color: '#7A827E',
                            callback: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 9 }, color: '#7A827E', maxTicksLimit: 10 }
                    }
                }
            }
        });
    }
</script>
@endsection