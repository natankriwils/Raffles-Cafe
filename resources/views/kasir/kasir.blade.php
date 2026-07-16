@extends('layouts.app')

@section('content')
@php
    $mappedProducts = $products;
@endphp

<div class="flex-1 flex overflow-hidden" x-data="kasirApp()">

    <div class="w-9/12 flex flex-col h-full bg-[#FAF8F5] p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-[#1C2220] tracking-tight">Select Menu Items</h1>
                <p class="text-xs font-semibold text-[#7A827E] tracking-wider uppercase mt-1">Click items below to add them to the cart</p>
            </div>
            
            <div class="w-72 relative">
                <input type="text" x-model="searchQuery" placeholder="Search menu items..."
                    class="w-full px-4 py-2.5 rounded-xl border border-[#EAE7E1] bg-white text-sm focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15 transition-all shadow-sm pl-9">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 flex items-center justify-center pointer-events-none opacity-60">
                    <img src="{{ asset('images/search.png') }}" class="w-full h-full object-contain" alt="Search">
                </div>
                <button x-show="searchQuery.length > 0" @click="searchQuery = ''" class="absolute right-3 top-2.5 text-xs font-bold text-[#7A827E] hover:text-[#1C2220]">&times;</button>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button @click="selectedCategory = 'all'" 
                        :class="selectedCategory === 'all' ? 'bg-[#244C38] text-white' : 'bg-white text-gray-700 border border-[#EAE7E1]'"
                        class="px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all uppercase tracking-wider shadow-sm">
                    All Menu
                </button>
                
                @foreach($categories as $cat)
                <button @click="selectedCategory = '{{ $cat->slug }}'" 
                        :class="selectedCategory === '{{ $cat->slug }}' ? 'bg-[#244C38] text-white' : 'bg-white text-gray-700 border border-[#EAE7E1]'"
                        class="px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all uppercase tracking-wider shadow-sm">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-3 xl:grid-cols-4 gap-5 pb-6">
            <template x-for="product in filteredProducts" :key="product.id">
                <div class="bg-white rounded-2xl border p-4 flex flex-col justify-between transition-all duration-200 group relative"
                    :class="product.is_available ? 'border-[#EAE7E1] hover:border-[#244C38] hover:shadow-lg cursor-pointer' : 'border-rose-100 bg-gray-50/50 opacity-65 cursor-not-allowed'"
                    @click="product.is_available ? addToCart(product) : null">

                    <div>
                        <div class="w-full h-32 rounded-xl mb-3 flex items-center justify-center p-4 border bg-[#FAF8F5]">
                            <span class="font-extrabold text-[#1C2220]" x-text="product.name.substring(0,2)"></span>
                        </div>
                        <h3 class="font-bold text-sm md:text-base leading-snug text-[#1C2220]" 
                            x-text="product.name"></h3> 
                        
                        <p class="text-[11px] text-[#7A827E] mt-1 line-clamp-2" x-text="product.description"></p>
                    </div>

                    <div class="flex justify-between items-center mt-4 pt-3 border-t border-[#FAF8F5]">
                        <span class="font-extrabold text-sm md:text-base text-[#244C38]" 
                            x-text="formatRupiah(product.base_price)"></span>
                        
                        <span :class="product.is_available ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200'"
                            class="text-[9px] font-extrabold uppercase tracking-wider px-2 py-1 rounded-md border"
                            x-text="product.is_available ? 'Ready' : 'Empty'"></span>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="filteredProducts.length === 0" x-cloak class="flex flex-col items-center justify-center py-20 text-center">
            <img src="{{ asset('images/keranjang.png') }}" alt="Not Found" class="w-16 h-16 object-contain opacity-40 mb-3 grayscale">
            <h3 class="text-lg font-extrabold text-[#1C2220]">Menu Item Not Found</h3>
            <p class="text-xs text-[#7A827E] mt-1 max-w-xs">We couldn't find any item matching <span class="font-bold text-[#1C2220]" x-text="'\"' + searchQuery + '\"'"></span> in this category.</p>
            <button @click="searchQuery = ''; selectedCategory = 'all'" class="mt-4 px-4 py-2 bg-[#244C38] text-white rounded-xl text-xs font-bold uppercase tracking-wider">Reset Filter &amp; Search</button>
        </div>
    </div>

    <div class="w-3/12 bg-white border-l border-[#EAE7E1] h-full flex flex-col justify-between shadow-xl z-10">

        <div class="p-5 border-b border-[#EAE7E1] bg-[#FAF8F5]/50">
            <div class="mb-3">
                <label class="block text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider mb-1">Customer / Table Name</label>
                <input type="text" x-model="customerName" placeholder="Enter Customer Name..."
                    class="w-full px-3 py-2 border border-[#EAE7E1] rounded-xl text-xs bg-white focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15 transition-all font-semibold">
            </div>
            <div>
                <label class="block text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider mb-1">Order Type</label>
                <div class="grid grid-cols-3 gap-1.5">
                    <button class="py-1.5 rounded-lg text-[11px] font-bold transition-all"
                        :class="orderType === 'dine-in' ? 'bg-[#1C2220] text-white shadow-sm' : 'bg-white border border-[#EAE7E1] text-[#4A524F] hover:border-[#244C38]'"
                        @click="orderType = 'dine-in'">Dine-In</button>
                    <button class="py-1.5 rounded-lg text-[11px] font-bold transition-all"
                        :class="orderType === 'take-away' ? 'bg-[#1C2220] text-white shadow-sm' : 'bg-white border border-[#EAE7E1] text-[#4A524F] hover:border-[#244C38]'"
                        @click="orderType = 'take-away'">Take-Away</button>
                    <button class="py-1.5 rounded-lg text-[11px] font-bold transition-all"
                        :class="orderType === 'delivery' ? 'bg-[#1C2220] text-white shadow-sm' : 'bg-white border border-[#EAE7E1] text-[#4A524F] hover:border-[#244C38]'"
                        @click="orderType = 'delivery'">Delivery</button>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-5 space-y-3 divide-y divide-[#FAF8F5]">
            <template x-if="cart.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-center">
                    <img src="{{ asset('images/keranjang.png') }}" alt="Empty Cart" class="w-14 h-14 object-contain opacity-40 mb-2">
                    <p class="font-bold text-[#7A827E] text-xs">Cart is Currently Empty</p>
                    <p class="text-[11px] text-[#B0B7B4] mt-0.5">Select items from the catalog on the left.</p>
                </div>
            </template>
            <template x-for="(item, index) in cart" :key="index">
                <div class="pt-3 first:pt-0 flex items-start justify-between">
                    <div class="flex-1 pr-2">
                        <h4 class="font-bold text-[#1C2220] text-xs leading-snug" x-text="item.name"></h4>
                        <span class="text-[11px] font-extrabold text-[#244C38] block mt-0.5" x-text="formatRupiah(item.price * item.qty)"></span>
                    </div>
                    <div class="flex items-center space-x-1 bg-[#FAF8F5] p-1 rounded-lg border border-[#EAE7E1]">
                        <button class="w-6 h-6 bg-white hover:bg-[#1C2220] hover:text-white text-[#4A524F] rounded-md text-xs font-bold transition-colors flex items-center justify-center shadow-sm"
                            @click="item.qty > 1 ? item.qty-- : removeFromCart(index)">-</button>
                        <span class="text-xs font-extrabold w-4 text-center text-[#1C2220]" x-text="item.qty"></span>
                        <button class="w-6 h-6 bg-white hover:bg-[#1C2220] hover:text-white text-[#4A524F] rounded-md text-xs font-bold transition-colors flex items-center justify-center shadow-sm"
                            @click="item.qty++">+</button>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-5 bg-[#FAF8F5] border-t border-[#EAE7E1] space-y-3">
            <div class="space-y-1.5 text-[11px] font-medium text-[#4A524F]">
                <div class="flex justify-between"><span>Subtotal</span><span class="font-bold text-[#1C2220]" x-text="formatRupiah(subtotal)"></span></div>
                <div class="flex justify-between"><span>Tax (10%)</span><span class="font-bold text-[#1C2220]" x-text="formatRupiah(tax)"></span></div>
                <div class="flex justify-between border-t border-[#EAE7E1] pt-2.5 text-sm font-bold text-[#1C2220]">
                    <span>Total Amount</span>
                    <span class="font-extrabold text-lg text-[#244C38]" x-text="formatRupiah(total)"></span>
                </div>
            </div>

            <button class="w-full py-3 bg-[#244C38] hover:bg-[#1A3829] text-white rounded-xl font-bold transition-all tracking-wider text-xs uppercase shadow-md shadow-[#244C38]/15 block disabled:opacity-40 disabled:cursor-not-allowed"
                    :disabled="cart.length === 0" @click="showPaymentModal = true">
                Process Payment
            </button>
        </div>
    </div>

    <div class="fixed inset-0 bg-[#1C2220]/60 backdrop-blur-sm flex items-center justify-center p-4 z-50" x-show="showPaymentModal" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-7 shadow-2xl space-y-6 border border-[#EAE7E1]" @click.away="showPaymentModal = false">

            <div class="flex justify-between items-center border-b border-[#EAE7E1] pb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-[#1C2220]">Order Payment</h3>
                    <p class="text-xs text-[#7A827E] font-semibold mt-0.5">Select preferred payment method</p>
                </div>
                <button class="w-8 h-8 rounded-full bg-[#FAF8F5] text-[#7A827E] hover:text-[#1C2220] hover:bg-[#EAE7E1] text-lg font-bold transition-all flex items-center justify-center" @click="showPaymentModal = false">&times;</button>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button class="p-4 border rounded-2xl flex flex-col items-center gap-2 font-bold text-xs uppercase tracking-wider transition-all"
                        :class="paymentMethod === 'cash' ? 'border-[#244C38] bg-[#244C38] text-white shadow-md' : 'border-[#EAE7E1] bg-[#FAF8F5]/50 text-[#4A524F] hover:border-[#244C38]'"
                        @click="paymentMethod = 'cash'; amountPaid = 0">
                    <img src="{{ asset('images/uang.png') }}" alt="Cash" class="w-8 h-8 object-contain">
                    <span>Cash Payment</span>
                </button>
                <button class="p-4 border rounded-2xl flex flex-col items-center gap-2 font-bold text-xs uppercase tracking-wider transition-all"
                        :class="paymentMethod === 'midtrans' ? 'border-[#244C38] bg-[#244C38] text-white shadow-md' : 'border-[#EAE7E1] bg-[#FAF8F5]/50 text-[#4A524F] hover:border-[#244C38]'"
                        @click="paymentMethod = 'midtrans'; amountPaid = total">
                    <img src="{{ asset('images/e-wallet.png') }}" alt="E-Wallet" class="w-8 h-8 object-contain">
                    <span>QRIS / E-Wallet</span>
                </button>
            </div>

            <div x-show="paymentMethod === 'cash'" class="space-y-3 bg-[#FAF8F5] p-4 rounded-2xl border border-[#EAE7E1]">
                <label class="block text-xs font-bold text-[#4A524F] uppercase tracking-wider">Cash Received</label>
                <input type="number" x-model.number="amountPaid"
                    class="w-full px-4 py-3 border border-[#EAE7E1] rounded-xl text-lg font-bold text-[#1C2220] focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15 bg-white transition-all">
                <div class="flex justify-between text-xs pt-1">
                    <span class="text-[#7A827E] font-medium">Customer Change:</span>
                    <span class="font-extrabold text-[#244C38] text-base" x-text="formatRupiah(change)"></span>
                </div>
            </div>

            <div class="border-t border-[#EAE7E1] pt-4">
                <button class="w-full py-3.5 bg-[#1C2220] hover:bg-[#2C3431] text-white font-bold rounded-xl transition-all tracking-wider text-xs uppercase shadow-md shadow-[#1C2220]/15 disabled:opacity-40 disabled:cursor-not-allowed"
                        :disabled="paymentMethod === 'cash' && amountPaid < total"
                        @click="submitOrder()">
                    Confirm &amp; Print Receipt
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('kasirApp', () => ({
            products: @json($mappedProducts),
            cart: [],
            customerName: '',
            selectedCategory: 'all',
            searchQuery: '',
            orderType: 'dine-in',

            taxRate: 0.1,
            showPaymentModal: false,
            paymentMethod: 'cash',
            amountPaid: 0,

            get filteredProducts() {
                return this.products.filter(item => {
                    const matchesCat = (this.selectedCategory === 'all') || (item.category_slug === this.selectedCategory);
                                       
                    const matchesSearch = item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || (item.description && item.description.toLowerCase().includes(this.searchQuery.toLowerCase()));
                    return matchesCat && matchesSearch;
                });
            },

            addToCart(product) {
                let existingItem = this.cart.find(item => item.id === product.id);
                if (existingItem) {
                    existingItem.qty++;
                } else {
                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
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
                    if (!res.ok) throw new Error('Server Error');
                    return res.json();
                })
                .then(data => {
                    if (data.snap_token) {
                        this.showPaymentModal = false;

                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result){
                                alert('Payment Successful!');
                                location.reload();
                            },
                            onPending: function(result){
                                alert('Order ID: ' + data.order_id + '\n\nPlease check status or simulate payment in Midtrans Sandbox.');
                                location.reload();
                            },
                            onError: function(result){
                                alert('Payment Failed!');
                            }
                        });
                    } else if (data.success) {
                        alert('Cash Transaction Saved Successfully!');
                        location.reload();
                    }
                })
                .catch(err => {
                    alert('Transaction Failed: ' + err.message);
                });
            }
        }));
    });
</script>
@endsection