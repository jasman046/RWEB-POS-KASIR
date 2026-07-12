<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ===========================
    // Kasir: Tampilan Menu Produk
    // ===========================

    public function index()
    {
        $makanan = Product::where('category', 'Makanan')->paginate(8);
        $minuman = Product::where('category', 'Minuman')->get();

        return view('products.index', [
            'makanan'  => $makanan,
            'minuman'  => $minuman,
            'category' => 'Makanan',
        ]);
    }

    public function byCategory($category)
    {
        return view('products.index', [
            'makanan'  => Product::where('category', 'Makanan')->get(),
            'minuman'  => Product::where('category', 'Minuman')->get(),
            'category' => $category,
        ]);
    }

    public function getByCategory($category)
    {
        $products = Product::where('category', $category)->get();
        return response()->json($products);
    }

    // ===========================
    // Admin: CRUD Produk
    // ===========================

    /**
     * Tampilkan daftar produk untuk Admin.
     */
    public function adminIndex()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Form tambah produk baru.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Makanan,Minuman',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $file               = $request->file('image');
            $fileName           = 'product_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $fileName, 'public');
            // Simpan path lengkap agar konsisten dengan Storage::url()
            $validated['image'] = 'products/' . $fileName;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Form edit produk.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update data produk.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Makanan,Minuman',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Jika ada gambar baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('image')) {
            // Hapus file lama dari storage (image sudah berisi path lengkap)
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $file               = $request->file('image');
            $fileName           = 'product_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $fileName, 'public');
            // Simpan path lengkap agar konsisten dengan Storage::url()
            $validated['image'] = 'products/' . $fileName;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui!');
    }

    /**
     * Hapus produk beserta gambarnya.
     */
    public function destroy(Product $product)
    {
        // Hapus file gambar dari storage (image berisi path lengkap: 'products/filename.jpg')
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $productName = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "' . $productName . '" berhasil dihapus.');
    }
}