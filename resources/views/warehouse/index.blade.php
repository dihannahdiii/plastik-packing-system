@extends('layouts.app')

@section('title', 'Kelola Pesanan dan Stok')

@section('content')
<div class="bg-gray-300 rounded-3xl p-8">
    <div class="flex items-center gap-3 text-2xl font-bold mb-6">
        <span>🛒</span>
        <h1>Kelola Pesanan dan Stok</h1>
    </div>

    <!-- Pending Orders Section -->
    <div class="bg-white rounded-2xl p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Pesanan yang harus diproses</h2>
            <div class="bg-gray-600 text-white px-4 py-2 rounded-lg font-bold" id="pending-counter">
                {{ $pendingOrders->count() }} Pesanan
            </div>
        </div>

        @if($pendingOrders->isEmpty())
            <div class="text-center py-12 text-gray-500">
                Tidak ada pesanan yang harus diproses
            </div>
        @else
            <div class="space-y-4">
                @foreach($pendingOrders as $order)
                <div class="border border-gray-300 rounded-lg p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
                        <div>
                            <div class="text-sm text-gray-600">No Pesanan</div>
                            <div class="font-semibold">{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Nama Konsumen</div>
                            <div class="font-semibold">{{ $order->customer_name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Nomor Telepon</div>
                            <div class="font-semibold">{{ $order->customer_phone }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Alamat Pengiriman</div>
                            <div class="font-semibold">{{ $order->customer_address }}</div>
                        </div>
                    </div>

                    <table class="w-full mb-4">
                        <thead>
                            <tr class="border-b border-gray-300">
                                <th class="text-left py-2">Nama Barang</th>
                                <th class="text-center py-2">QTY</th>
                                <th class="text-center py-2">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $detail)
                            <tr>
                                <td class="py-2">{{ $detail->product->name }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-center">
                                    @foreach($detail->product->stock->where('quantity', '>', 0) as $stock)
                                        <span class="inline-block bg-gray-200 px-2 py-1 rounded text-sm mr-1">
                                            {{ $stock->location->name }} ({{ $stock->quantity }})
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-end">
                        <form action="{{ route('warehouse.orders.confirm', $order) }}" method="POST" 
                              onsubmit="return confirm('Konfirmasi pesanan ini?')">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-400 hover:bg-red-500 text-white font-bold py-2 px-6 rounded-lg transition">
                                Konfirmasi Pesanan
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Update Stock Section -->
    <div class="bg-white rounded-2xl p-6">
        <h2 class="text-xl font-bold mb-4">Update Stok</h2>
        
        <input type="text" 
               id="productSearch" 
               placeholder="Cari Nama Barang" 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 mb-4">
        <div id="searchResults"></div>

        <div id="stockForm" class="hidden">
            <form action="{{ route('warehouse.stock.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" id="selectedProductId">
                
                <div>
                    <label class="block font-semibold mb-2">Lokasi</label>
                    <select name="location_id" required 
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <option value="">Pilih Lokasi</option>
                        @foreach(App\Models\Location::all() as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-2">Jumlah Stok</label>
                    <input type="number" 
                           name="quantity" 
                           min="0"
                           required
                           class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                </div>

                <button type="submit" 
                        class="bg-red-400 hover:bg-red-500 text-white font-bold py-2 px-6 rounded-lg transition">
                    Simpan data
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('productSearch').addEventListener('input', async function(e) {
    const query = e.target.value;
    if (query.length < 2) {
        document.getElementById('searchResults').innerHTML = '';
        document.getElementById('stockForm').classList.add('hidden');
        return;
    }

    const response = await fetch(`{{ route('warehouse.products.search') }}?q=${query}`);
    const products = await response.json();
    
    let html = '<div class="bg-gray-50 rounded-lg p-2 max-h-60 overflow-y-auto mb-4">';
    products.forEach(product => {
        const totalStock = product.stock.reduce((sum, s) => sum + s.quantity, 0);
        html += `
            <div class="p-2 hover:bg-gray-200 cursor-pointer rounded" onclick="selectProduct(${product.id}, '${product.name}')">
                <div class="font-semibold">${product.name}</div>
                <div class="text-sm text-gray-600">Stok Total: ${totalStock}</div>
            </div>
        `;
    });
    html += '</div>';
    document.getElementById('searchResults').innerHTML = html;
});

function selectProduct(id, name) {
    document.getElementById('selectedProductId').value = id;
    document.getElementById('productSearch').value = name;
    document.getElementById('searchResults').innerHTML = '';
    document.getElementById('stockForm').classList.remove('hidden');
}

// Real-time order counter update
function updatePendingCounter() {
    fetch('{{ route('warehouse.pending.count') }}')
        .then(response => response.json())
        .then(data => {
            const counter = document.getElementById('pending-counter');
            const currentCount = parseInt(counter.textContent);
            const newCount = data.count;
            
            if (newCount !== currentCount) {
                counter.textContent = newCount + ' Pesanan';
                counter.classList.add('animate-pulse');
                setTimeout(() => counter.classList.remove('animate-pulse'), 1000);
                
                // Reload page if count changed (new order arrived)
                if (newCount > currentCount) {
                    setTimeout(() => location.reload(), 2000);
                }
            }
        })
        .catch(error => console.error('Error updating counter:', error));
}

// Update every 5 seconds
setInterval(updatePendingCounter, 5000);
</script>
@endsection
