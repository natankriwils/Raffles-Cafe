@extends('layouts.app')

@section('content')
<div class="flex-1 flex overflow-hidden" 
     x-data="{
        cart: [],
        customerName: '',
        selectedCategory: 'all',
        orderType: 'dine-in',

        taxRate: 0.1,
        showPaymentModal: false,
        paymentMethod: 'cash',
        amountPaid: 0,
        
        addToCart(product) {
            let existingItem = this.cart.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.qty++;
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.base_price,
                    qty: 1
                });
            }
        },
        
        removeFromCart(index) {
            this.cart.splice(index, 1);
        },
        
        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },
        
        get tax() {
            return Math.round(this.subtotal * this.taxRate);
        },
        
        get total() {
            return this.subtotal + this.tax;
        },
        
        get change() {
            return this.amountPaid >= this.total ? this.amountPaid - this.total : 0;
        },
        
        formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        },

        submitOrder() {
            if (this.cart.length === 0) return;
            
            fetch('/kasir/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    customer_name: this.customerName,
                    order_type: this.orderType,
                    cart: this.cart,
                    payment_method: this.paymentMethod,
                    amount_paid: this.amountPaid
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Server bermasalah');
                return res.json();
            })
            .then(data => {
                if (data.snap_token) {
                    this.showPaymentModal = false;

                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result){ 
                            alert('Pembayaran Sukses!'); 
                            location.reload(); 
                        },
                        onPending: function(result){ 
                            alert('Order ID: ' + data.order_id + '\n\nSilakan cek status atau simulasikan di Midtrans Sandbox!');
                            location.reload();
                        },
                        onError: function(result){ 
                            alert('Pembayaran Gagal!'); 
                        }
                    });
                } else if (data.success) {
                    alert('Transaksi Tunai Berhasil Disimpan!');
                    location.reload();
                }
            })
            .catch(err => {
                alert('Gagal memproses transaksi: ' + err.message);
            });
        }
     }">

    <div class="w-8/12 flex flex-col h-full bg-gray-50 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kasir</h1>
                <p class="text-sm text-gray-500">Kasir: Natan Kasir | Shift: Open</p>
            </div>
            <div class="w-64">
                <input type="text" placeholder="Cari menu kopi..." class="w-full px-4 py-2 rounded-lg border border-gray-200 bg-white shadow-sm">
            </div>
        </div>

        <div class="mb-6">
            <div class="flex space-x-3 overflow-x-auto pb-2">
                <button
                    type="button"
                    class="px-5 py-2 bg-amber-800 text-white font-medium rounded-full text-sm whitespace-nowrap"
                    @click="selectedCategory = 'all'; cart=[]">
                    Semua Menu
                </button>

                @foreach($categories as $category)
                    <button
                        type="button"
                        class="px-5 py-2 bg-white text-gray-600 font-medium rounded-full border border-gray-200 text-sm whitespace-nowrap"
                        @click="selectedCategory = '{{ $category->slug }}'">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>


        <div class="grid grid-cols-3 gap-4">
@foreach($products as $product)
                @if(!isset($selectedCategory) || $selectedCategory === 'all' || $product->category->slug === $selectedCategory)

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col justify-between hover:border-amber-500 transition cursor-pointer"
                         @click="addToCart({ id: {{ $product->id }}, name: '{{ $product->name }}', base_price: {{ $product->base_price }} })">

                        <div>
                        <div class="w-full h-32
                            @if($product->category->slug == 'coffee') bg-amber-100 text-amber-800 
                            @elif($product->category->slug == 'non-coffee') bg-emerald-100 text-emerald-800 
                            @else bg-orange-100 text-orange-800 @endif">
                            <span>{{ $product->name }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $product->description }}</p>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <span class="font-bold text-amber-900 text-sm">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                        <span class="text-xs bg-green-100 text-green-700 font-medium px-2 py-1 rounded">Ready</span>
                    </div>
                </div>
                @endif
            @endforeach

        </div>
    </div>

    <div class="w-4/12 bg-white border-l border-gray-200 h-full flex flex-col justify-between shadow-lg">
        <div class="p-4 border-b border-gray-100">
            <div class="mb-3">
                <input type="text" x-model="customerName" placeholder="Nama Pelanggan" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div class="grid grid-cols-3 gap-2">
                <button class="py-2 rounded-lg text-xs font-bold" :class="orderType === 'dine-in' ? 'bg-amber-50 border border-amber-600 text-amber-800' : 'bg-gray-50 border border-gray-200 text-gray-600'" @click="orderType = 'dine-in'">Dine-In</button>
                <button class="py-2 rounded-lg text-xs font-bold" :class="orderType === 'take-away' ? 'bg-amber-50 border border-amber-600 text-amber-800' : 'bg-gray-50 border border-gray-200 text-gray-600'" @click="orderType = 'take-away'">Take-Away</button>
                <button class="py-2 rounded-lg text-xs font-bold" :class="orderType === 'delivery' ? 'bg-amber-50 border border-amber-600 text-amber-800' : 'bg-gray-50 border border-gray-200 text-gray-600'" @click="orderType = 'delivery'">Delivery</button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <template x-if="cart.length === 0">
                <div class="text-center text-gray-400 py-12">Keranjang Kosong</div>
            </template>
            <template x-for="(item, index) in cart" :key="index">
                <div class="flex items-start justify-between border-b border-gray-50 pb-3">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 text-sm" x-text="item.name"></h4>
                        <span class="text-xs font-bold text-amber-800 block mt-1" x-text="formatRupiah(item.price * item.qty)"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="w-6 h-6 bg-gray-100 rounded text-sm" @click="item.qty > 1 ? item.qty-- : removeFromCart(index)">-</button>
                        <span class="text-sm font-bold w-4 text-center" x-text="item.qty"></span>
                        <button class="w-6 h-6 bg-gray-100 rounded text-sm" @click="item.qty++">+</button>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-100 space-y-3">
            <div class="space-y-1 text-sm text-gray-600">
                <div class="flex justify-between"><span>Subtotal</span><span x-text="formatRupiah(subtotal)"></span></div>
                <div class="flex justify-between"><span>Pajak (10%)</span><span x-text="formatRupiah(tax)"></span></div>
                <div class="flex justify-between border-t border-gray-200 pt-2 text-base font-bold text-gray-800">
                    <span>Total Tagihan</span><span class="text-amber-900 font-black" x-text="formatRupiah(total)"></span>
                </div>
            </div>

            <button class="w-full py-3 bg-amber-900 text-white rounded-xl font-bold hover:bg-amber-800 shadow-md block"
                    :disabled="cart.length === 0" @click="showPaymentModal = true">
                Proses Pembayaran
            </button>
        </div>
    </div>

    <div class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" x-show="showPaymentModal" x-cloak>
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-lg font-bold text-gray-800">Pilih Metode Pembayaran</h3>
                <button class="text-gray-400 hover:text-gray-600 text-xl font-bold" @click="showPaymentModal = false">&times;</button>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button class="p-4 border rounded-xl flex flex-col items-center gap-2 font-bold"
                        :class="paymentMethod === 'cash' ? 'border-amber-600 bg-amber-50 text-amber-900' : 'border-gray-200 text-gray-600'"
                        @click="paymentMethod = 'cash'; amountPaid = 0">
                    Tunai / Cash
                </button>
                <button class="p-4 border rounded-xl flex flex-col items-center gap-2 font-bold"
                        :class="paymentMethod === 'midtrans' ? 'border-amber-600 bg-amber-50 text-amber-900' : 'border-gray-200 text-gray-600'"
                        @click="paymentMethod = 'midtrans'; amountPaid = total">
                    QRIS / E-Wallet
                </button>
            </div>

            <div x-show="paymentMethod === 'cash'" class="space-y-2">
                <label class="block text-sm font-semibold text-gray-600">Uang Tunai yang Diterima</label>
                <input type="number" x-model.number="amountPaid" class="w-full px-4 py-2 border rounded-lg text-lg font-bold focus:ring-amber-600">
                <div class="flex justify-between text-sm pt-2">
                    <span class="text-gray-500">Kembalian:</span>
                    <span class="font-bold text-green-700 text-base" x-text="formatRupiah(change)"></span>
                </div>
            </div>

            <div class="border-t pt-4">
                <button class="w-full py-3 bg-green-700 text-white font-bold rounded-xl shadow hover:bg-green-800"
                        :disabled="paymentMethod === 'cash' && amountPaid < total"
                        :class="paymentMethod === 'cash' && amountPaid < total ? 'opacity-50 cursor-not-allowed' : ''"
                        @click="submitOrder()">
                    Konfirmasi & Cetak Struk
                </button>
            </div>
        </div>
    </div>
</div>
@endsection