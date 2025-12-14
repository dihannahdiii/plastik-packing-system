<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::where('status', 'pending')
            ->with(['details.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('warehouse.index', compact('pendingOrders'));
    }

    public function show(Order $order)
    {
        $order->load(['details.product.stock.location', 'user']);
        return view('warehouse.show', compact('order'));
    }

    public function confirm(Order $order)
    {
        DB::beginTransaction();
        try {
            // Deduct stock
            foreach ($order->details as $detail) {
                $remainingQty = $detail->quantity;
                $stocks = Stock::where('product_id', $detail->product_id)
                    ->where('quantity', '>', 0)
                    ->orderBy('quantity', 'desc')
                    ->get();

                foreach ($stocks as $stock) {
                    if ($remainingQty <= 0) break;
                    
                    $deductQty = min($stock->quantity, $remainingQty);
                    $stock->decrement('quantity', $deductQty);
                    $remainingQty -= $deductQty;
                }

                if ($remainingQty > 0) {
                    throw new \Exception("Stok tidak cukup untuk produk: {$detail->product->name}");
                }
            }

            $order->update(['status' => 'confirmed']);
            DB::commit();

            return redirect()->route('warehouse.index')
                ->with('success', 'Pesanan berhasil dikonfirmasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function stock()
    {
        $products = Product::with('stock.location')->get();
        return view('warehouse.stock', compact('products'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $stock = Stock::firstOrNew([
            'product_id' => $request->product_id,
            'location_id' => $request->location_id,
        ]);

        $stock->quantity = $request->quantity;
        $stock->save();

        return redirect()->route('warehouse.stock')
            ->with('success', 'Stok berhasil diupdate!');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        $products = Product::where('name', 'like', "%{$query}%")
            ->with('stock.location')
            ->take(10)
            ->get();
        
        return response()->json($products);
    }

    public function getPendingCount()
    {
        $count = Order::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }
}
