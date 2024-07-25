<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Pastikan middleware otentikasi digunakan
    }

    public function index()
    {
        // Ambil semua order yang terkait dengan pengguna yang sedang login
        $orders = Order::where('users_id', auth()->id())->get();
        return OrderResource::collection($orders);
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
            'cart_items_id' => 'required|exists:cart_items,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Tambahkan users_id dari pengguna yang sedang login
        $orderData = $request->all();
        $orderData['users_id'] = auth()->id();
        $orderData['status'] = 'pending'; // Set status default menjadi pending

        // Hitung total harga dari cart items yang terkait
        $cartItems = CartItem::where('users_id', auth()->id())->get();
        $totalPrice = 0;

        foreach ($cartItems as $cartItem) {
            $productPrice = (float) $cartItem->product->price;
            Log::info("Product ID: {$cartItem->product->id}, Price: {$productPrice}");
            $totalPrice += $productPrice;
        }

        // Tambahkan total_price ke order data
        $orderData['total_price'] = $totalPrice;

        // Buat order baru
        $order = Order::create($orderData);

        // Kembalikan response
        return new OrderResource($order);
    }

    public function show($id)
    {
        // Ambil order berdasarkan id dan pengguna yang sedang login
        $order = Order::where('users_id', auth()->id())->findOrFail($id);
        return new OrderResource($order);
    }

    public function update(Request $request, $id)
    {
        // Ambil order yang akan diupdate
        $order = Order::where('users_id', auth()->id())->findOrFail($id);

        // Validasi data yang diterima
        $validator = Validator::make($request->all(), [
            'cart_items_id' => 'required|exists:cart_items,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Tambahkan users_id dari pengguna yang sedang login
        $orderData = $request->all();
        $orderData['users_id'] = auth()->id();
        $orderData['status'] = 'pending'; // Set status default menjadi pending

        // Hitung ulang total harga dari cart items yang terkait
        $cartItems = CartItem::where('users_id', auth()->id())->get();
        $totalPrice = 0;

        foreach ($cartItems as $cartItem) {
            $productPrice = (float) $cartItem->product->price;
            Log::info("Product ID: {$cartItem->product->id}, Price: {$productPrice}");
            $totalPrice += $productPrice;
        }

        // Tambahkan total_price ke order data
        $orderData['total_price'] = $totalPrice;

        // Update order
        $order->update($orderData);

        // Kembalikan response
        return new OrderResource($order);
    }

    public function destroy($id)
    {
        // Ambil order yang akan dihapus
        $order = Order::where('users_id', auth()->id())->findOrFail($id);
        // Hapus order
        $order->delete();

        // Kembalikan response tanpa konten
        return response()->noContent();
    }
}
