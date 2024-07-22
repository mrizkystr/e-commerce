<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with(['user', 'product'])->get();
        return CartItemResource::collection($cartItems);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'users_id' => 'required|exists:users,id',
            'products_id' => 'required|exists:products,id',
        ]);

        $cartItem = CartItem::create($validatedData);

        return new CartItemResource($cartItem);
    }

    public function show($id)
    {
        $cartItem = CartItem::with(['user', 'product'])->findOrFail($id);
        return new CartItemResource($cartItem);
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);

        $validatedData = $request->validate([
            'users_id' => 'required|exists:users,id',
            'products_id' => 'required|exists:products,id',
        ]);

        $cartItem->update($validatedData);

        return new CartItemResource($cartItem);
    }

    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return response()->noContent();
    }
}
