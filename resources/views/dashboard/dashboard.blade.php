@extends('layouts.app')

@section('content')
<div class="flex-1 flex overflow-hidden">
    <div class="flex-1 overflow-y-auto p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-sm text-gray-500">Ringkasan penjualan cafe Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="text-sm text-gray-500">Pendapatan Hari Ini (Success)</div>
                <div class="text-2xl font-bold text-amber-900 mt-2">
                    Rp {{ number_format((float)$todayTotal, 0, ',', '.') }}
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="text-sm text-gray-500">Transaksi Sukses (Hari Ini)</div>
                <div class="text-2xl font-bold text-green-700 mt-2">{{ $todayCountSuccess }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="text-sm text-gray-500">Transaksi Pending (Hari Ini)</div>
                <div class="text-2xl font-bold text-amber-700 mt-2">{{ $todayCountPending }}</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-gray-800">Grafik Pendapatan Harian</h2>
                    <p class="text-sm text-gray-500">7 hari terakhir (berdasarkan payment_status = success)</p>
                </div>
                <div class="text-xs text-gray-400">Update otomatis saat transaksi tersimpan</div>
            </div>

            <div class="h-72">
                <canvas id="dailyRevenueChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h2 class="font-bold text-gray-800">Total Pendapatan Bulan Ini</h2>
                <div class="text-3xl font-extrabold text-amber-900 mt-2">
                    Rp {{ number_format((float)$monthTotal, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500 mt-1">Menggunakan payment_status = success</div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h2 class="font-bold text-gray-800">Catatan</h2>
                <ul class="text-sm text-gray-600 mt-2 space-y-2 list-disc pl-4">
                    <li>Pending akan masuk setelah callback Midtrans atau simulasi status sukses.</li>
                    <li>Jika Anda ingin pakai data pending, tinggal ubah query di controller.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const labels = @json($dailyLabels);
    const amounts = @json($dailyAmounts);

    const ctx = document.getElementById('dailyRevenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: amounts,
                    borderColor: '#92400e',
                    backgroundColor: 'rgba(146, 64, 14, 0.15)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const v = context.parsed.y || 0;
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endsection

