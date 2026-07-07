<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $makanan = Product::where('category', 'Makanan')->paginate(8);
        
        // Panggil minuman tetap pakai get() karena jumlahnya mungkin sedikit
        $minuman = Product::where('category', 'Minuman')->get();
        
        // Return ke view products.index (halaman menu ASgor lo)
        return view('products.index', [
            'makanan' => $makanan,
            'minuman' => $minuman,
            'category' => 'Makanan' // Set default tab yang aktif
        ]);
    }

    public function byCategory($category)
    {
        $products = Product::where('category', $category)->get();
        
        return view('products.index', [
            'makanan' => Product::where('category', 'Makanan')->get(),
            'minuman' => Product::where('category', 'Minuman')->get(),
            'category' => $category
        ]);
    }

    public function getByCategory($category)
    {
        $products = Product::where('category', $category)->get();
        return response()->json($products);
    }
}