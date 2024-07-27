<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use App\Models\CartItem;
use Midtrans\Notification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Pastikan middleware otentikasi digunakan

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
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
            Log::error('Validation failed', $validator->errors()->toArray());
            return response()->json($validator->errors(), 400);
        }

        try {
            // Ambil pengguna yang sedang login
            $user = auth()->user();

            // Tambahkan users_id dari pengguna yang sedang login
            $orderData = $request->all();
            $orderData['users_id'] = $user->id;
            $orderData['status'] = 'pending'; // Set status default menjadi pending

            // Ambil cart item yang terkait
            $cartItem = CartItem::where('id', $request->cart_items_id)
                ->where('users_id', $user->id)
                ->firstOrFail();

            // Check if product exists and has a price
            if (!$cartItem->product || !$cartItem->product->price) {
                Log::error('Product or price is missing', ['cart_item_id' => $request->cart_items_id]);
                return response()->json(['error' => 'Product or price is missing'], 400);
            }

            // Hitung total harga dari cart item yang terkait
            $totalPrice = $cartItem->product->price; // Asumsikan ada relasi product dan kolom price

            // Tambahkan total_price ke order data
            $orderData['total_price'] = $totalPrice;

            // Buat order baru
            $order = Order::create($orderData);

            return new OrderResource($order);
        } catch (\Exception $e) {
            Log::error('Error creating order', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }

        //Bejir Gabisa
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
            'cart_items_id' => 'required',
            'cart_items_id.*' => 'exists:cart_items,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Tambahkan users_id dari pengguna yang sedang login
        $orderData = $request->all();
        $orderData['users_id'] = auth()->id();
        $orderData['status'] = 'pending'; // Set status default menjadi pending

        // Hitung ulang total harga dari semua cart items yang terkait
        $totalPrice = 0;
        foreach ($request->cart_items_id as $cartItemId) {
            $cartItem = CartItem::where('id', $cartItemId)
                ->where('users_id', auth()->id())
                ->firstOrFail();
            $totalPrice += $cartItem->product->price; // Asumsikan ada relasi product dan kolom price
        }

        // Tambahkan total_price ke order data
        $orderData['total_price'] = $totalPrice;

        // Update order
        $order->update($orderData);

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

    public function createPayment($id)
    {
        // Ambil order berdasarkan id dan pengguna yang sedang login
        $order = Order::where('users_id', auth()->id())->findOrFail($id);

        // Generate unique order ID
        $uniqueOrderId = $order->id . '-' . Str::uuid();

        // Buat transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $uniqueOrderId,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'order' => new OrderResource($order),
            'snap_token' => $snapToken,
        ]);
    }

    public function handleNotification(Request $request)
    {
        $notification = new Notification();

        $order = Order::where('id', explode('-', $notification->order_id)[0])->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Perbarui status order berdasarkan notifikasi
        $order->update([
            'status' => $notification->transaction_status,
        ]);

        return response()->json(['message' => 'Notification handled successfully']);
    }
}
