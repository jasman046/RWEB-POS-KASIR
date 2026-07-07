<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $makanan = Product::where('category', 'Makanan')->paginate(8);
        $minuman = Product::where('category', 'Minuman')->get();
        
        return view('products.index', [
            'makanan' => $makanan,
            'minuman' => $minuman,
            'category' => 'Makanan'
        ]);
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity']++;
        } else {
            $cart[$request->product_id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'message' => 'Item ditambahkan ke keranjang',
            'cart_count' => count($cart),
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if ($request->quantity <= 0) {
            unset($cart[$request->product_id]);
        } else {
            $cart[$request->product_id]['quantity'] = $request->quantity;
        }
        
        session()->put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'total' => $this->calculateTotal($cart)
        ]);
    }

        public function checkout()
        {
            $cart = session()->get('cart', []);
            
            if (empty($cart)) {
                return redirect('/')->with('error', 'Keranjang kosong');
            }
            
            return view('checkout.checkout', [
                'cart' => $cart,
                'total' => $this->calculateTotal($cart)
            ]);
        }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'order_type' => 'required|in:Dine In,Delivery',
            'table_number' => 'required_if:order_type,Dine In',
            'payment_method' => 'required|in:Credit Card,QRIS,Cash',
            'cardholder_name' => 'required_if:payment_method,Credit Card',
            'card_number' => 'required_if:payment_method,Credit Card',
            'expiration_date' => 'required_if:payment_method,Credit Card',
            'cvv' => 'required_if:payment_method,Credit Card',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong']);
        }

        // Buat order baru
        $order = Order::create([
            'order_number' => 'ORD-' . date('YmdHis') . rand(1000, 9999),
            'type' => $validated['order_type'],
            'table_number' => $validated['order_type'] === 'Dine In' ? $validated['table_number'] : null,
            'payment_method' => $validated['payment_method'],
            'total_price' => $this->calculateTotal($cart),
            'status' => 'completed'
        ]);

        // Buat order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // Clear cart dan session
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil',
            'order_id' => $order->id,
            'order_number' => $order->order_number
        ]);
    }

    public function receipt($orderId)
    {
        $order = Order::with('orderItems.product')->find($orderId);
        
        if (!$order) {
            return redirect('/')->with('error', 'Pesanan tidak ditemukan');
        }
        
        return view('receipt.show', ['order' => $order]);
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}