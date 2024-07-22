<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Pastikan middleware otentikasi digunakan
    }

    public function index()
    {
        // Ambil semua item di keranjang yang terkait dengan pengguna yang sedang login
        $cartItems = CartItem::where('users_id', auth()->id())->with(['user', 'product'])->get();
        return CartItemResource::collection($cartItems);
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            'products_id' => 'required|exists:products,id',
        ]);

        // Tambahkan users_id dari pengguna yang sedang login
        $validatedData['users_id'] = auth()->id();

        // Periksa apakah item keranjang sudah ada untuk produk dan pengguna yang sama
        $existingCartItem = CartItem::where('users_id', auth()->id())
                                    ->where('products_id', $validatedData['products_id'])
                                    ->first();

        if (!$existingCartItem) {
            // Jika item keranjang belum ada, buat item keranjang baru
            $cartItem = CartItem::create($validatedData);
        } else {
            // Jika item sudah ada, Anda bisa memilih untuk tidak melakukan apa-apa atau menangani duplikasi
            return response()->json(['message' => 'Product is already in the cart'], 200);
        }

        // Kembalikan response
        return new CartItemResource($cartItem);
    }

    public function show($id)
    {
        // Ambil item keranjang berdasarkan id dan pengguna yang sedang login
        $cartItem = CartItem::where('users_id', auth()->id())->with(['user', 'product'])->findOrFail($id);
        return new CartItemResource($cartItem);
    }

    public function update(Request $request, $id)
    {
        // Ambil item keranjang yang akan diupdate
        $cartItem = CartItem::where('users_id', auth()->id())->findOrFail($id);

        // Validasi data yang diterima
        $validatedData = $request->validate([
            'products_id' => 'required|exists:products,id',
        ]);

        // Tambahkan users_id dari pengguna yang sedang login
        $validatedData['users_id'] = auth()->id();

        // Update item keranjang
        $cartItem->update($validatedData);

        // Kembalikan response
        return new CartItemResource($cartItem);
    }

    public function destroy($id)
    {
        // Ambil item keranjang yang akan dihapus
        $cartItem = CartItem::where('users_id', auth()->id())->findOrFail($id);
        // Hapus item keranjang
        $cartItem->delete();

        // Kembalikan response tanpa konten
        return response()->noContent();
    }
}
