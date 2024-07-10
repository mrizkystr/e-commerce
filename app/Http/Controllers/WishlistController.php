<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Resources\WishlistResource;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::all();
        return WishlistResource::collection($wishlists);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'users_id' => 'required|exists:users,id',
            'products_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::create($validated);

        return new WishlistResource($wishlist);
    }

    public function show($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        return new WishlistResource($wishlist);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'users_id' => 'required|exists:users,id',
            'products_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::findOrFail($id);
        $wishlist->update($validated);

        return new WishlistResource($wishlist);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return response()->noContent();
    }
}
