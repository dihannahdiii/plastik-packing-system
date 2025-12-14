@extends('layouts.app')

@section('title', 'Buat Pesanan Baru')

@section('content')
<div class="bg-gray-300 rounded-3xl p-8 mb-8">
    <div class="flex items-center gap-3 text-2xl font-bold mb-6">
        <span>🛒</span>
        <h1>Buat Pesanan Baru</h1>
    </div>

    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
        @csrf
        
        <!-- Search Product -->
        <div class="bg-white rounded-2xl p-6 mb-6">
            <input type="text" 
                   id="productSearch" 
                   placeholder="Cari Nama Barang" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
            <div id="searchResults" class="mt-2"></div>
        </div>

        <!-- Order Table -->
        <div class="bg-white rounded-2xl p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Rincian Pesanan</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-black">
                        <th class="text-left py-2">Nama Barang</th>
                        <th class="text-center py-2">QTY</th>
                        <th class="text-center py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody id="orderItems">
                    <tr id="emptyRow">
                        <td colspan="3" class="text-center py-8 text-gray-500">Belum ada produk yang dipilih</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Customer Info -->
        <div class="bg-white rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <span class="text-2xl">👤</span>
                <h2 class="text-xl font-bold">Informasi Konsumen</h2>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block font-semibold mb-2">Nama Konsumen</label>
                    <input type="text" 
                           name="customer_name" 
                           required
                           class="w-full px-4 py-3 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                </div>

                <div>
                    <label class="block font-semibold mb-2">Nomor Telepon</label>
                    <input type="text" 
                           name="customer_phone" 
                           required
                           class="w-full px-4 py-3 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                </div>

                <div>
                    <label class="block font-semibold mb-2">Alamat Pengiriman</label>
                    <textarea name="customer_address" 
                              required
                              rows="3"
                              class="w-full px-4 py-3 bg-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400"></textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-8">
            <button type="submit" 
                    class="bg-red-400 hover:bg-red-500 text-white font-bold py-3 px-12 rounded-lg text-lg transition">
                Simpan Pesanan
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
let selectedProducts = [];

document.getElementById('productSearch').addEventListener('input', async function(e) {
    const query = e.target.value;
    if (query.length < 2) {
        document.getElementById('searchResults').innerHTML = '';
        return;
    }

    const response = await fetch(`{{ route('admin.products.search') }}?q=${query}`);
    const products = await response.json();
    
    let html = '<div class="bg-gray-50 rounded-lg p-2 max-h-60 overflow-y-auto">';
    products.forEach(product => {
        const totalStock = product.stock.reduce((sum, s) => sum + s.quantity, 0);
        html += `
            <div class="p-2 hover:bg-gray-200 cursor-pointer rounded" onclick="addProduct(${product.id}, '${product.name}', ${totalStock})">
                <div class="font-semibold">${product.name}</div>
                <div class="text-sm text-gray-600">Stok: ${totalStock}</div>
            </div>
        `;
    });
    html += '</div>';
    document.getElementById('searchResults').innerHTML = html;
});

function addProduct(id, name, stock) {
    if (selectedProducts.find(p => p.id === id)) {
        alert('Produk sudah ditambahkan');
        return;
    }

    selectedProducts.push({id, name, quantity: 1, stock});
    renderOrderItems();
    document.getElementById('productSearch').value = '';
    document.getElementById('searchResults').innerHTML = '';
}

function updateQuantity(id, quantity) {
    const product = selectedProducts.find(p => p.id === id);
    if (product) {
        product.quantity = parseInt(quantity);
    }
}

function removeProduct(id) {
    selectedProducts = selectedProducts.filter(p => p.id !== id);
    renderOrderItems();
}

function renderOrderItems() {
    const tbody = document.getElementById('orderItems');
    if (selectedProducts.length === 0) {
        tbody.innerHTML = '<tr id="emptyRow"><td colspan="3" class="text-center py-8 text-gray-500">Belum ada produk yang dipilih</td></tr>';
        return;
    }

    let html = '';
    selectedProducts.forEach(product => {
        html += `
            <tr class="border-b">
                <td class="py-3">${product.name}</td>
                <td class="text-center">
                    <input type="number" 
                           min="1" 
                           max="${product.stock}"
                           value="${product.quantity}" 
                           onchange="updateQuantity(${product.id}, this.value)"
                           class="w-20 px-2 py-1 border rounded text-center"
                           name="products[${product.id}][quantity]">
                    <input type="hidden" name="products[${product.id}][product_id]" value="${product.id}">
                </td>
                <td class="text-center">
                    <button type="button" 
                            onclick="removeProduct(${product.id})"
                            class="text-red-500 hover:text-red-700 font-bold">
                        ✕
                    </button>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}
</script>
@endsection
