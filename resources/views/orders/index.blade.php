@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Daftar Pesanan</h1>
        <a href="{{ route('admin.orders.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
            + Buat Pesanan Baru
        </a>
    </div>

    <!-- Filter Status -->
    <div class="mb-6 flex gap-2">
        <button onclick="filterOrders('all')" class="filter-btn bg-gray-700 text-white px-4 py-2 rounded">
            Semua
        </button>
        <button onclick="filterOrders('pending')" class="filter-btn bg-yellow-500 text-white px-4 py-2 rounded">
            Pending
        </button>
        <button onclick="filterOrders('confirmed')" class="filter-btn bg-blue-500 text-white px-4 py-2 rounded">
            Dikonfirmasi
        </button>
        <button onclick="filterOrders('completed')" class="filter-btn bg-green-500 text-white px-4 py-2 rounded">
            Selesai
        </button>
        <button onclick="filterOrders('cancelled')" class="filter-btn bg-red-500 text-white px-4 py-2 rounded">
            Dibatalkan
        </button>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No. Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Konsumen</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Total Item</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="order-row" data-status="{{ $order->status }}">
                    <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <div>{{ $order->customer_name }}</div>
                        <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->details->count() }} produk</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->status === 'pending')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @elseif($order->status === 'confirmed')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Dikonfirmasi
                            </span>
                        @elseif($order->status === 'completed')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Dibatalkan
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="showOrderDetails({{ $order->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                            Detail
                        </button>
                        @if($order->status === 'confirmed')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="text-green-600 hover:text-green-800">
                                Selesaikan
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Order Detail Modal -->
    <div id="orderDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Detail Pesanan</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div id="orderDetailContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
let currentFilter = 'all';

function filterOrders(status) {
    currentFilter = status;
    const rows = document.querySelectorAll('.order-row');
    
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function showOrderDetails(orderId) {
    const order = @json($orders);
    const selectedOrder = order.find(o => o.id === orderId);
    
    if (!selectedOrder) return;
    
    let detailsHtml = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">No. Pesanan</p>
                    <p class="font-semibold">${selectedOrder.order_number}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal</p>
                    <p class="font-semibold">${new Date(selectedOrder.created_at).toLocaleString('id-ID')}</p>
                </div>
            </div>
            
            <div>
                <p class="text-sm text-gray-600">Konsumen</p>
                <p class="font-semibold">${selectedOrder.customer_name}</p>
                <p class="text-sm">${selectedOrder.customer_phone}</p>
                <p class="text-sm text-gray-600">${selectedOrder.customer_address}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-600 mb-2">Detail Produk</p>
                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Produk</th>
                            <th class="px-4 py-2 text-right">Qty</th>
                            <th class="px-4 py-2 text-right">Harga</th>
                            <th class="px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
    `;
    
    let total = 0;
    selectedOrder.details.forEach(detail => {
        const subtotal = detail.quantity * detail.price;
        total += subtotal;
        detailsHtml += `
            <tr class="border-t">
                <td class="px-4 py-2">${detail.product.name}</td>
                <td class="px-4 py-2 text-right">${detail.quantity}</td>
                <td class="px-4 py-2 text-right">Rp ${detail.price.toLocaleString('id-ID')}</td>
                <td class="px-4 py-2 text-right">Rp ${subtotal.toLocaleString('id-ID')}</td>
            </tr>
        `;
    });
    
    detailsHtml += `
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right">Total</td>
                            <td class="px-4 py-2 text-right">Rp ${total.toLocaleString('id-ID')}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    `;
    
    document.getElementById('orderDetailContent').innerHTML = detailsHtml;
    document.getElementById('orderDetailModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('orderDetailModal').classList.add('hidden');
}
</script>
@endsection
