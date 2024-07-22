<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartItemController extends Controller
{
    public function index()
    {
        return CartItemResource::collection(CartItem::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_id' => 'required|exists:users,id',
            'products_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cartItem = CartItem::create($request->all());
        return new CartItemResource($cartItem);
    }

    public function show($id)
    {
        $cartItem = CartItem::findOrFail($id);
        return new CartItemResource($cartItem);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'users_id' => 'required|exists:users,id',
            'products_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cartItem = CartItem::findOrFail($id);
        $cartItem->update($request->all());
        return new CartItemResource($cartItem);
    }

    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();
        return response()->noContent();
    }
}
