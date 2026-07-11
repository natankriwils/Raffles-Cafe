@extends('layouts.app')

@section('content')
<div class="flex-1 flex overflow-hidden">
    <div class="flex-1 overflow-y-auto p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h1>
                <p class="text-sm text-gray-500">Daftar transaksi kasir</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
            <form method="GET" action="{{ route('riwayat.index') }}" class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="text-sm text-gray-600 font-medium">Status Pembayaran</label>
                    <select name="status" class="mt-1 px-3 py-2 rounded-lg border border-gray-200 bg-white text-sm">
                        <option value="" {{ request('status')==='' ? 'selected' : '' }}>Semua</option>
                        <option value="success" {{ request('status')==='success' ? 'selected' : '' }}>Success</option>
                        <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status')==='failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="self-end">
                    <button type="submit" class="px-4 py-2 bg-amber-900 text-white rounded-xl font-bold hover:bg-amber-800">Filter</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-4 py-3 font-bold text-gray-700">Order #</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-700">Tanggal</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-700">Customer</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-700">Metode</th>
                            <th class="text-left px-4 py-3 font-bold text-gray-700">Status</th>
                            <th class="text-right px-4 py-3 font-bold text-gray-700">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="border-b last:border-b-0">
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $order->order_number }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->customer_name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->payment_method ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $status = $order->payment_status;
                                    @endphp
                                    @if($status === 'success')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Success</span>
                                    @elseif($status === 'pending')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Pending</span>
                                    @elseif($status === 'failed')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Failed</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">{{ $status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800">Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

