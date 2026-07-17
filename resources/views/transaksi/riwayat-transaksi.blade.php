@extends('layouts.app')

@section('content')
<div class="flex-1 flex overflow-hidden">
    <div class="flex-1 overflow-y-auto p-8 bg-[#FAF8F5]">
        
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/riwayat-transaksi.png') }}" alt="Order History" class="w-10 h-10 object-contain">
                <div>
                    <h1 class="text-3xl font-extrabold text-[#1C2220] tracking-tight">Transaction History</h1>
                    <p class="text-xs font-semibold text-[#7A827E] tracking-widest uppercase mt-0.5">Manage and monitor customer order statuses</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#EAE7E1] p-5 mb-6 shadow-sm flex flex-wrap justify-between items-center gap-4">
            <form method="GET" action="{{ route('riwayat.index') }}" class="flex items-center gap-3">
                <label class="text-xs font-bold text-[#7A827E] tracking-widest uppercase whitespace-nowrap">Payment Status:</label>
                <select name="status" onchange="this.form.submit()" 
                        class="px-4 py-2 rounded-xl border border-[#EAE7E1] bg-[#FAF8F5] text-[#1C2220] text-xs font-bold uppercase tracking-wider focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15 transition-all cursor-pointer">
                    <option value="" {{ request('status')==='' ? 'selected' : '' }}>All Status</option>
                    <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status')==='failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </form>
            <span class="text-xs text-[#7A827E] font-medium whitespace-nowrap">Showing {{ $orders->count() }} records</span>
        </div>

        <div class="bg-white rounded-2xl border border-[#EAE7E1] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-[#FAF8F5] border-b border-[#EAE7E1]">
                        <tr>
                            <th class="text-left px-5 py-4 font-bold text-[#7A827E] text-[11px] tracking-widest uppercase whitespace-nowrap">Order #</th>
                            <th class="text-left px-5 py-4 font-bold text-[#7A827E] text-[11px] tracking-widest uppercase whitespace-nowrap">Date &amp; Time</th>
                            <th class="text-left px-5 py-4 font-bold text-[#7A827E] text-[11px] tracking-widest uppercase whitespace-nowrap">Customer</th>
                            <th class="text-left px-5 py-4 font-bold text-[#7A827E] text-[11px] tracking-widest uppercase whitespace-nowrap">Method</th>
                            <th class="text-left px-5 py-4 font-bold text-[#7A827E] text-[11px] tracking-widest uppercase whitespace-nowrap">Status</th>
                            <th class="text-right px-5 py-4 font-bold text-[#7A827E] text-[11px] tracking-widest uppercase whitespace-nowrap">Total Amount</th>
                            <th class="text-center px-5 py-4 font-bold text-[#7A827E] text-[11px] tracking-widest uppercase whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#FAF8F5]">
                        @forelse($orders as $order)
                            <tr class="hover:bg-[#FAF8F5]/60 transition-colors">
                                <td class="px-5 py-4 font-bold text-[#1C2220] font-mono whitespace-nowrap align-middle">{{ $order->order_number }}</td>
                                <td class="px-5 py-4 text-[#4A524F] font-medium whitespace-nowrap align-middle">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-5 py-4 text-[#1C2220] font-semibold whitespace-nowrap align-middle">{{ $order->customer_name ?? '-' }}</td>
                                <td class="px-5 py-4 text-[#4A524F] uppercase text-xs font-bold whitespace-nowrap align-middle">{{ $order->payment_method ?? '-' }}</td>
                                
                                <td class="px-5 py-4 whitespace-nowrap align-middle">
                                    @php $status = $order->status; @endphp
                                    
                                    @if($status === 'completed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-[#EAF2EE] text-[#244C38] border border-[#C5DCD0] tracking-wider uppercase">
                                            <img src="{{ asset('images/success.png') }}" class="w-3 h-3 object-contain" alt="Success">
                                            Completed
                                        </span>
                                    @elseif($status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold bg-[#FFF9E6] text-[#D99000] border border-[#FFE8A3] tracking-wider uppercase">
                                            <img src="{{ asset('images/pending.png') }}" class="w-3 h-3 object-contain" alt="Pending">
                                            Pending
                                        </span>
                                    @elseif($status === 'failed')
                                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-[#FFEBEE] text-[#C62828] border border-[#FFCDD2] tracking-wider uppercase">Failed</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-gray-100 text-gray-600 tracking-wider uppercase">{{ $status }}</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right font-extrabold text-base text-[#244C38] whitespace-nowrap align-middle">
                                    Rp {{ number_format((float)$order->total_amount, 0, ',', '.') }}
                                </td>
                                
                                <td class="px-5 py-4 text-center whitespace-nowrap align-middle">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" 
                                                onclick="openReceipt('{{ route('riwayat.struk', $order->id) }}')" 
                                                class="px-3 py-1.5 bg-[#EAF2EE] hover:bg-[#C5DCD0] text-[#244C38] rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition-colors border border-[#C5DCD0] inline-flex items-center gap-1 shadow-sm">
                                            <span>Receipt</span> ⎙
                                        </button>

                                        <form action="{{ route('transaksi.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-[#FFF5F5] hover:bg-[#FFE0E0] text-[#D9534F] rounded-lg text-[10px] font-extrabold uppercase tracking-wider transition-colors border border-[#FFE0E0] shadow-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center text-[#B0B7B4] text-xs font-bold tracking-widest uppercase">No transaction records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>

    </div>
</div>

<script>
function openReceipt(url) {
    const width = 380;
    const height = 620;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    window.open(url, 'StrukDailyCoffee', `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=no`);
}
</script>
@endsection