@extends('layouts.app')

@section('content')
@php
    $mappedIngredients = $ingredients->map(function($item) {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'slug' => $item->slug,
            'stock' => (int) $item->stock,
            'unit' => $item->unit,
            'min_stock' => (int) $item->min_stock,
            'status' => $item->stock <= $item->min_stock ? 'low' : 'safe'
        ];
    })->values();
@endphp

<div class="flex-1 flex overflow-hidden" x-data="stockManagementApp()">

    <div class="w-full h-full p-8 overflow-y-auto bg-[#FAF8F5]">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-[#1C2220] tracking-tight">Stock &amp; Inventory</h1>
                <p class="text-xs font-semibold text-[#7A827E] tracking-wider uppercase mt-1">Monitor daily supplies, ingredients, and stock take</p>
            </div>
            
            <button @click="openAddModal()" class="px-5 py-3 bg-[#244C38] hover:bg-[#1A3829] text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md flex items-center gap-2">
                <span>+ Add New Ingredient</span>
            </button>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button @click="$el.parentElement.remove()">&times;</button>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div class="bg-white p-5 rounded-2xl border border-[#EAE7E1] flex items-center justify-between shadow-sm">
                <div>
                    <p class="text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider">Total Ingredients</p>
                    <h3 class="text-2xl font-extrabold text-[#1C2220] mt-1" x-text="ingredients.length">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[#FAF8F5] flex items-center justify-center p-2">
                    <img src="{{ asset('images/box.png') }}" alt="Total Ingredients" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-[#EAE7E1] flex items-center justify-between shadow-sm">
                <div>
                    <p class="text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider">Low Stock Alerts</p>
                    <h3 class="text-2xl font-extrabold text-rose-600 mt-1" x-text="lowStockCount">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center p-2.5">
                    <img src="{{ asset('images/warning.png') }}" alt="Low Stock Alert" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-[#EAE7E1] flex items-center justify-between shadow-sm">
                <div>
                    <p class="text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider">Optimal Condition</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1" x-text="ingredients.length - lowStockCount">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center p-2">
                    <img src="{{ asset('images/check.png') }}" alt="Optimal Condition" class="w-full h-full object-contain">
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-t-2xl border border-[#EAE7E1] border-b-0 flex flex-wrap justify-between items-center gap-4">
            <div class="flex gap-2">
                <button @click="filterStatus = 'all'" 
                        :class="filterStatus === 'all' ? 'bg-[#1C2220] text-white' : 'bg-[#FAF8F5] text-[#4A524F] hover:bg-[#EAE7E1]'"
                        class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                    All Items (<span x-text="ingredients.length"></span>)
                </button>
                <button @click="filterStatus = 'low'" 
                        :class="filterStatus === 'low' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-600 hover:bg-rose-100'"
                        class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-1.5">
                    <span>Low Stock</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px]" :class="filterStatus === 'low' ? 'bg-white/20 text-white' : 'bg-rose-200 text-rose-800'" x-text="lowStockCount"></span>
                </button>
            </div>

            <div class="w-72 relative">
                <input type="text" x-model="searchQuery" placeholder="Search ingredient name..."
                    class="w-full px-4 py-2 rounded-xl border border-[#EAE7E1] bg-[#FAF8F5] text-xs focus:outline-none focus:border-[#244C38] focus:bg-white transition-all pl-9 font-semibold">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 flex items-center justify-center pointer-events-none opacity-50">
                    <img src="{{ asset('images/search.png') }}" class="w-full h-full object-contain" alt="Search">
                </div>
                <button x-show="searchQuery.length > 0" @click="searchQuery = ''" class="absolute right-3 top-2 text-xs font-bold text-[#7A827E]">&times;</button>
            </div>
        </div>

        <div class="bg-white rounded-b-2xl border border-[#EAE7E1] overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FAF8F5] border-b border-[#EAE7E1] text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider">
                        <th class="py-4 px-6">Ingredient Name</th>
                        <th class="py-4 px-6 text-center">Current Stock</th>
                        <th class="py-4 px-6 text-center">Unit</th>
                        <th class="py-4 px-6 text-center">Min. Alert</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#FAF8F5] text-xs font-bold text-[#1C2220]">
                    <template x-for="item in filteredIngredients" :key="item.id">
                        <tr class="hover:bg-[#FAF8F5]/60 transition-colors">
                            <td class="py-4 px-6 font-extrabold text-sm" x-text="item.name"></td>
                            
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center space-x-2 bg-[#FAF8F5] px-2 py-1 rounded-xl border border-[#EAE7E1]">
                                    <button @click="quickAdjust(item, -1)" class="w-6 h-6 rounded-lg bg-white hover:bg-rose-50 hover:text-rose-600 border border-[#EAE7E1] text-xs transition-colors flex items-center justify-center shadow-sm">&minus;</button>
                                    <span class="w-10 text-center font-black text-sm" :class="item.stock <= item.min_stock ? 'text-rose-600' : 'text-[#244C38]'" x-text="item.stock"></span>
                                    <button @click="quickAdjust(item, 1)" class="w-6 h-6 rounded-lg bg-white hover:bg-emerald-50 hover:text-emerald-600 border border-[#EAE7E1] text-xs transition-colors flex items-center justify-center shadow-sm">&plus;</button>
                                </div>
                            </td>

                            <td class="py-4 px-6 text-center uppercase font-extrabold text-[#7A827E]" x-text="item.unit"></td>
                            <td class="py-4 px-6 text-center text-[#7A827E]" x-text="item.min_stock + ' ' + item.unit"></td>
                            
                            <td class="py-4 px-6 text-center">
                                <span x-show="item.stock <= item.min_stock" class="px-2.5 py-1 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-[10px] font-black uppercase tracking-wider animate-pulse inline-block">Low Stock</span>
                                <span x-show="item.stock > item.min_stock" class="px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-wider inline-block">Safe</span>
                            </td>

                            <td class="py-4 px-6 text-right space-x-1">
                                <button @click="openEditModal(item)" class="px-3 py-1.5 bg-[#FAF8F5] hover:bg-[#1C2220] hover:text-white text-[#4A524F] rounded-xl text-xs font-bold transition-all border border-[#EAE7E1]">Edit</button>
                                <button @click="confirmDelete(item)" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-xl text-xs font-bold transition-all border border-rose-100">Delete</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div x-show="filteredIngredients.length === 0" x-cloak class="flex flex-col items-center justify-center py-16 text-center">
                <div class="text-4xl mb-2">&#128237;</div>
                <h3 class="text-base font-extrabold text-[#1C2220]">No Ingredients Found</h3>
                <p class="text-xs text-[#7A827E] mt-1">Try refining your search keyword or add a new ingredient.</p>
            </div>
        </div>

    </div>

    <div class="fixed inset-0 bg-[#1C2220]/60 backdrop-blur-sm flex items-center justify-center p-4 z-50" x-show="showModal" x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-7 shadow-2xl space-y-5 border border-[#EAE7E1]" @click.away="showModal = false">
            <div class="flex justify-between items-center border-b border-[#EAE7E1] pb-4">
                <h3 class="text-lg font-extrabold text-[#1C2220]" x-text="isEdit ? 'Edit Ingredient' : 'Add New Ingredient'"></h3>
                <button @click="showModal = false" class="w-8 h-8 rounded-full bg-[#FAF8F5] text-[#7A827E] hover:text-[#1C2220] font-bold text-lg flex items-center justify-center">&times;</button>
            </div>

            <form :action="formAction" method="POST" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider mb-1">Ingredient Name</label>
                    <input type="text" name="name" x-model="formData.name" required placeholder="e.g. Arabica Coffee Beans"
                        class="w-full px-3 py-2 border border-[#EAE7E1] rounded-xl text-xs font-semibold focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider mb-1">Current Stock</label>
                        <input type="number" name="stock" x-model="formData.stock" required min="0" placeholder="0"
                            class="w-full px-3 py-2 border border-[#EAE7E1] rounded-xl text-xs font-semibold focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider mb-1">Unit Type</label>
                        <select name="unit" x-model="formData.unit" required
                            class="w-full px-3 py-2 border border-[#EAE7E1] rounded-xl text-xs font-semibold focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15 bg-white">
                            <option value="Bag (1kg)">Bag (1kg)</option>
                            <option value="Bag (500g)">Bag (500g)</option>
                            <option value="Box (1L)">Box (1L)</option>
                            <option value="Box (25s)">Box (25s)</option>
                            <option value="Pack (1kg)">Pack (1kg)</option>
                            <option value="Pack (500g)">Pack (500g)</option>
                            <option value="Bottle (750ml)">Bottle (750ml)</option>
                            <option value="Jerrycan (1L)">Jerrycan (1L)</option>
                            <option value="Sleeve (50 pcs)">Sleeve (50 pcs)</option>
                            <option value="Pcs">Pcs / Unit</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-[#7A827E] uppercase tracking-wider mb-1">Min. Alert Threshold</label>
                    <input type="number" name="min_stock" x-model="formData.min_stock" required min="0" placeholder="10"
                        class="w-full px-3 py-2 border border-[#EAE7E1] rounded-xl text-xs font-semibold focus:outline-none focus:border-[#244C38] focus:ring-2 focus:ring-[#244C38]/15">
                    <p class="text-[10px] text-[#7A827E] mt-1">System will trigger low stock alert if inventory drops below this number.</p>
                </div>

                <div class="border-t border-[#EAE7E1] pt-4 flex gap-2">
                    <button type="button" @click="showModal = false" class="w-1/3 py-3 bg-[#FAF8F5] hover:bg-[#EAE7E1] text-[#4A524F] font-bold rounded-xl text-xs uppercase tracking-wider transition-all">Cancel</button>
                    <button type="submit" class="w-2/3 py-3 bg-[#244C38] hover:bg-[#1A3829] text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-md shadow-[#244C38]/15 transition-all" x-text="isEdit ? 'Save Changes' : 'Create Ingredient'"></button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stockManagementApp', () => ({
            ingredients: @json($mappedIngredients),
            searchQuery: '',
            filterStatus: 'all',
            
            showModal: false,
            isEdit: false,
            formAction: '',
            formData: { id: null, name: '', stock: '', unit: 'Pcs', min_stock: 10 },

            get filteredIngredients() {
                return this.ingredients.filter(item => {
                    const matchesSearch = item.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                    const matchesFilter = this.filterStatus === 'all' || (this.filterStatus === 'low' && item.stock <= item.min_stock);
                    return matchesSearch && matchesFilter;
                });
            },

            get lowStockCount() {
                return this.ingredients.filter(item => item.stock <= item.min_stock).length;
            },

            openAddModal() {
                this.isEdit = false;
                this.formAction = "{{ route('kelola-stok.store') }}";
                this.formData = { id: null, name: '', stock: '', unit: 'Pcs', min_stock: 10 };
                this.showModal = true;
            },

            openEditModal(item) {
                this.isEdit = true;
                this.formAction = "/kelola-stok/" + item.id;
                this.formData = { ...item };
                this.showModal = true;
            },

            confirmDelete(item) {
                if (confirm('Are you sure you want to delete "' + item.name + '" from inventory?')) {
                    const form = document.getElementById('deleteForm');
                    form.action = "/kelola-stok/" + item.id;
                    form.submit();
                }
            },

            quickAdjust(item, amount) {
                const newStock = item.stock + amount;
                if (newStock < 0) return;

                item.stock = newStock;

                fetch('/kelola-stok/' + item.id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        _method: 'PUT',
                        name: item.name,
                        stock: newStock,
                        unit: item.unit,
                        min_stock: item.min_stock
                    })
                }).then(res => {
                    if (!res.ok) alert('Failed to update stock on server!');
                }).catch(err => {
                    alert('Network error while updating stock.');
                });
            }
        }));
    });
</script>
@endsection