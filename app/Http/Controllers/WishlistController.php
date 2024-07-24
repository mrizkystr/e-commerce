<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Resources\WishlistResource;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Pastikan middleware otentikasi digunakan
    }

    public function index()
    {
        // Ambil semua wishlist yang terkait dengan pengguna yang sedang login
        $wishlists = Wishlist::where('users_id', auth()->id())->get();
        return WishlistResource::collection($wishlists);
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'products_id' => 'required|exists:products,id',
        ]);

        // Tambahkan users_id dari pengguna yang sedang login
        $validated['users_id'] = auth()->id();

        // Buat wishlist baru
        $wishlist = Wishlist::create($validated);

        // Kembalikan response
        return new WishlistResource($wishlist);
    }

    public function show($id)
    {
        // Ambil wishlist berdasarkan id dan pengguna yang sedang login
        $wishlist = Wishlist::where('users_id', auth()->id())->findOrFail($id);
        return new WishlistResource($wishlist);
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'products_id' => 'required|exists:products,id',
        ]);

        // Tambahkan users_id dari pengguna yang sedang login
        $validated['users_id'] = auth()->id();

        // Ambil wishlist yang akan diupdate
        $wishlist = Wishlist::where('users_id', auth()->id())->findOrFail($id);
        
        // Update wishlist
        $wishlist->update($validated);

        // Kembalikan response
        return new WishlistResource($wishlist);
    }

    public function destroy($id)
    {
        // Ambil wishlist yang akan dihapus
        $wishlist = Wishlist::where('users_id', auth()->id())->findOrFail($id);
        
        // Hapus wishlist
        $wishlist->delete();

        // Kembalikan response tanpa konten
        return response()->noContent();
    }
}
