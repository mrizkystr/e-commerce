<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Order::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'status' => 'in:pending,completed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = Order::create($request->all());
        return new OrderResource($order);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return new OrderResource($order);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'users_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
            'status' => 'in:pending,completed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $order = Order::findOrFail($id);
        $order->update($request->all());
        return new OrderResource($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->noContent();
    }
}
