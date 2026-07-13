@extends('layouts.app')

@section('content')
<div class="flex-1 flex overflow-hidden" x-data="{ openAddModal: false, openEditModal: false, currentProduct: {} }">
    <div class="flex-1 overflow-y-auto p-8 bg-[#FAF8F5]">
        
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="p-2.5 bg-white rounded-2xl border border-[#EAE7E1] shadow-sm">
                    <img src="{{ asset('images/editmenu.png') }}" alt="Menu Management" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-[#1C2220] tracking-tight">Menu Management</h1>
                    <p class="text-xs font-semibold text-[#7A827E] tracking-widest uppercase mt-0.5">Create, edit, or remove menu items and categories</p>
                </div>
            </div>
            
            <button @click="openAddModal = true" class="bg-[#244C38] text-white px-5 py-3 rounded-xl text-xs font-extrabold hover:bg-[#1D3D2D] transition-all shadow-md shadow-[#244C38]/15 uppercase tracking-wider flex items-center gap-2">
                <span>+ Add New Product</span>
            </button>
        </div>

        @if(session('success'))
            <div class="bg-[#EAF2EE] border border-[#C5DCD0] text-[#244C38] text-xs font-bold p-4 rounded-xl mb-6">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-[#FFF5F5] border border-[#FFE0E0] text-[#D9534F] text-xs font-bold p-4 rounded-xl mb-6">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="bg-white rounded-2xl border border-[#EAE7E1] p-6 shadow-sm lg:col-span-2 flex flex-col justify-between">
                <div>
                    <h2 class="font-extrabold text-[#1C2220] text-base mb-4">All Coffee & Menu Products</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="bg-[#FAF8F5] text-[#7A827E] font-bold uppercase tracking-wider border-b border-[#EAE7E1]">
                                <tr>
                                    <th class="py-3 px-3">Name</th>
                                    <th class="py-3 px-3">Category</th>
                                    <th class="py-3 px-3">Price</th>
                                    <th class="py-3 px-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#FAF8F5] font-medium text-[#1C2220]">
                                @forelse($products as $product)
                                <tr class="hover:bg-[#FAF8F5]/50 transition-colors">
                                    <td class="py-3 px-3 font-bold">{{ $product->name }}</td>
                                    <td class="py-3 px-3"><span class="bg-[#FAF8F5] px-2 py-0.5 rounded border border-[#EAE7E1] text-[#4A524F]">{{ $product->category->name ?? 'Uncategorized' }}</span></td>
                                    <td class="py-3 px-3 font-extrabold text-[#244C38]">Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-3 text-center flex items-center justify-center gap-2">
                                        <button @click="currentProduct = {{ json_encode($product) }}; openEditModal = true" class="text-xs font-bold text-[#244C38] hover:underline bg-[#EAF2EE] px-2.5 py-1 rounded-md border border-[#C5DCD0]">Edit</button>
                                        <form action="{{ route('kelola-menu.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-[#D9534F] hover:underline bg-[#FFF5F5] px-2.5 py-1 rounded-md border border-[#FFE0E0]">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400">Belum ada produk terdaftar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-1">
                <div class="bg-white rounded-2xl border border-[#EAE7E1] p-5 shadow-sm">
                    <h2 class="font-extrabold text-[#1C2220] text-sm mb-3">Add Category</h2>
                    <form action="{{ route('kelola-menu.storeCategory') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="name" placeholder="e.g., Signature Coffee" required class="w-full text-xs font-semibold px-3 py-2.5 bg-[#FAF8F5] border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38]">
                        <button type="submit" class="w-full bg-[#244C38] text-white py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-[#1D3D2D]">Save Category</button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl border border-[#EAE7E1] p-5 shadow-sm">
                    <h2 class="font-extrabold text-[#1C2220] text-sm mb-3">Active Categories</h2>
                    <div class="space-y-2">
                        @foreach($categories as $cat)
                        <div class="flex items-center justify-between p-2.5 rounded-xl border border-[#EAE7E1] bg-[#FAF8F5]">
                            <div>
                                <span class="text-xs font-bold text-[#1C2220]">{{ $cat->name }}</span>
                                <span class="text-[10px] text-[#7A827E] font-medium block">{{ $cat->products_count }} Products</span>
                            </div>
                            <form action="{{ route('kelola-menu.destroyCategory', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[10px] font-bold text-[#D9534F] hover:bg-red-50 p-1 rounded-md">&times; Delete</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div x-show="openAddModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-2xl border border-[#EAE7E1] w-full max-w-md overflow-hidden shadow-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-extrabold text-lg text-[#1C2220]">Add New Product</h3>
                <button @click="openAddModal = false" class="text-xl font-bold text-gray-400 hover:text-black">&times;</button>
            </div>
            <form action="{{ route('kelola-menu.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#7A827E] uppercase mb-1">Product Name</label>
                    <input type="text" name="name" required class="w-full text-xs font-semibold px-3 py-2.5 bg-[#FAF8F5] border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#7A827E] uppercase mb-1">Category</label>
                    <select name="category_id" required class="w-full text-xs font-semibold px-3 py-2.5 bg-[#FAF8F5] border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38]">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#7A827E] uppercase mb-1">Price (Rp)</label>
                    <input type="number" name="price" required class="w-full text-xs font-semibold px-3 py-2.5 bg-[#FAF8F5] border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38]">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 text-xs font-bold text-[#7A827E] bg-gray-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#244C38] rounded-xl hover:bg-[#1D3D2D]">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEditModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-2xl border border-[#EAE7E1] w-full max-w-md overflow-hidden shadow-2xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-extrabold text-lg text-[#1C2220]">Edit Product</h3>
                <button @click="openEditModal = false" class="text-xl font-bold text-gray-400 hover:text-black">&times;</button>
            </div>
            <form :action="'/kelola-menu/' + currentProduct.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-[#7A827E] uppercase mb-1">Product Name</label>
                    <input type="text" name="name" :value="currentProduct.name" required class="w-full text-xs font-semibold px-3 py-2.5 bg-[#FAF8F5] border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#7A827E] uppercase mb-1">Category</label>
                    <select name="category_id" class="w-full text-xs font-semibold px-3 py-2.5 bg-[#FAF8F5] border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38]">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" :selected="currentProduct.category_id == {{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-[#7A827E] uppercase mb-1">Price (Rp)</label>
                    <input type="number" name="price" :value="currentProduct.base_price" required class="w-full text-xs font-semibold px-3 py-2.5 bg-[#FAF8F5] border border-[#EAE7E1] rounded-xl focus:outline-none focus:border-[#244C38]">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 text-xs font-bold text-[#7A827E] bg-gray-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#244C38] rounded-xl hover:bg-[#1D3D2D]">Update Product</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection