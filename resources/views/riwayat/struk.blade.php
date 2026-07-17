<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->order_number }} - Daily Coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap');
        body {
            font-family: 'Courier Prime', monospace;
            background-color: #f3f4f6;
        }
        .ticket {
            width: 58mm;
            background: white;
            padding: 5mm 4mm;
            margin: 20px auto;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            font-size: 11px;
            color: #000;
        }
        @media print {
            body { background-color: transparent; }
            .no-print { display: none !important; }
            .ticket {
                margin: 0;
                box-shadow: none;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="no-print max-w-[58mm] mx-auto mt-4 flex gap-2 justify-between">
        <button onclick="window.close()" class="bg-gray-500 text-white px-3 py-1.5 rounded text-xs font-bold hover:bg-gray-600 w-1/2">
            &larr; Close
        </button>
        <button onclick="window.print()" class="bg-[#244C38] text-white px-3 py-1.5 rounded text-xs font-bold hover:bg-[#1D3D2D] w-1/2 flex items-center justify-center gap-1">
            <span>Print</span> ⎙
        </button>
    </div>

    <div class="ticket">
        <div class="text-center mb-3">
            <h2 class="text-sm font-bold m-0 tracking-wide">DAILY COFFEE</h2>
            <p class="text-[9px] text-gray-600 m-0 leading-tight">Jl. Pramuka Komp. Rahayu Pembina 3</p>
            <p class="text-[9px] text-gray-600 m-0 leading-tight">No. 19A, Banjarmasin</p>
            <p class="text-[9px] text-gray-600 m-0 leading-tight">Tel: 0853-9223-3660</p>
        </div>
        
        <div class="border-t border-dashed border-gray-400 my-2"></div>
        
        <div class="space-y-1 text-[10px]">
            <div class="flex justify-between items-start">
                <span>Order No.</span>
                <span class="font-bold text-right">#{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span>Date &amp; Time</span>
                <span class="text-right">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span>Customer</span>
                <span class="text-right font-medium">{{ $order->customer_name ?? 'General' }}</span>
            </div>
        </div>
        
        <div class="border-t border-dashed border-gray-400 my-2"></div>
        
        <table class="w-full text-[10px]">
            <tbody>
                @foreach($order->orderDetails as $detail)
                <tr>
                    <td colspan="2" class="font-bold pt-1">
                        {{ $detail->product->name }}
                        @if($detail->variant)
                            <span class="text-gray-500 font-normal">({{ $detail->variant->name }})</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>{{ $detail->quantity }}x Rp {{ number_format($detail->price_at_transaction, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="border-t border-dashed border-gray-400 my-2"></div>
        
        <div class="space-y-1 text-[10px]">
            <div class="flex justify-between items-center font-bold text-[12px] pt-0.5">
                <span>TOTAL</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span>Payment Method</span>
                <span class="uppercase font-bold">{{ $order->payment_method }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span>Status</span>
                <span class="uppercase font-bold text-[#244C38] tracking-wider">{{ $order->status }}</span>
            </div>
        </div>
        
        <div class="border-t border-dashed border-gray-400 my-3"></div>
        
        <div class="text-center mt-2">
            <p class="text-[9px] m-0 font-bold tracking-wider">Thank You For Your Visit!</p>
            <p class="text-[8px] text-gray-500 m-0 mt-0.5">Please keep this receipt as proof of payment</p>
        </div>
    </div>

</body>
</html>