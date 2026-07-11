<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        return view('products.index', [
            'makanan' => Product::where('category', 'Makanan')->paginate(8),
            'minuman' => Product::where('category', 'Minuman')->get(),
            'category' => 'Makanan'
        ]);
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'image'      => $product->image,
                'quantity'   => 1
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'    => true,
            'message'    => 'Item ditambahkan ke keranjang',
            'cart_count' => count($cart),
            'cart'       => $cart
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
            'cart'    => $cart,
            'total'   => $this->calculateTotal($cart)
        ]);
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/')
                ->with('error', 'Keranjang kosong');
        }

        return view('checkout.checkout', [
            'cart'  => $cart,
            'total' => $this->calculateTotal($cart)
        ]);
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:100',
            'order_type'       => 'required|in:Dine In,Delivery',
            'payment_method'   => 'required|in:Credit Card,QRIS,Cash',
            'cardholder_name'  => 'required_if:payment_method,Credit Card',
            'card_number'      => 'required_if:payment_method,Credit Card',
            'expiration_date'  => 'required_if:payment_method,Credit Card',
            'cvv'              => 'required_if:payment_method,Credit Card',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong'
            ], 400);
        }

        // Card utama milik toko
        $card = Card::where('theme', 'gradient')->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Card toko tidak ditemukan.'
            ], 400);
        }

        DB::beginTransaction();

        try {

            // ==========================
            // Buat Order
            // ==========================

            $order = Order::create([
                'order_number'   => 'ORD-' . now()->format('YmdHis') . rand(1000, 9999),
                'customer_name'  => $validated['customer_name'],
                'type'           => $validated['order_type'],
                'payment_method' => $validated['payment_method'],
                'total_price'    => $this->calculateTotal($cart),
                'status'         => 'completed',
            ]);

            // ==========================
            // Simpan Detail Order
            // ==========================

            foreach ($cart as $item) {

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price']
                ]);

            }

            // ==========================
            // Simpan Riwayat Transaction
            // ==========================

          $transaction = Transaction::create([
            'card_id' => $card->id,
            'transaction_code' => $order->order_number,
            'description' => 'Pembayaran Order ' . $order->order_number,
            'type' => 'Shopping',
            'amount' => $order->total_price,
            'transaction_date' => now(),
            'category' => 'Income',
            'receipt_path' => null,
            'status' => 'Completed',
        ]);

            // ==========================
            // Tambah Saldo Card Toko
            // ==========================

            $card->balance += $order->total_price;
            $card->save();

            $pdf = Pdf::loadView('receipt.pdf', [
                'order' => $order,
            ]);

            $fileName = $order->order_number . '.pdf';

            Storage::disk('public')->put(
                'receipts/' . $fileName,
                $pdf->output()
            );

            $transaction->update([
                'receipt_path' => $fileName
            ]);

            DB::commit();

            session()->forget('cart');

            return response()->json([
                'success'      => true,
                'message'      => 'Pembayaran berhasil',
                'order_id'     => $order->id,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {

    DB::rollBack();

    throw $e;

}
    }

    public function receipt($orderId)
    {
        $order = Order::with('orderItems.product')->find($orderId);

        if (!$order) {
            return redirect('/')
                ->with('error', 'Pesanan tidak ditemukan');
        }

        return view('receipt.show', [
            'order' => $order
        ]);
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