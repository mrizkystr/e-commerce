<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{
    // Fungsi untuk mendapatkan semua data merchant
    public function index()
    {
        return Merchant::all();
    }

    // Fungsi untuk menyimpan data merchant baru
    public function store(Request $request)
    {
        // Validasi input data
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string',
            'merchant_name' => 'required|string|max:255',
        ]);

        // Jika validasi gagal, kembalikan respon dengan error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat dan simpan data merchant baru
        $merchant = Merchant::create($request->all());

        // Kembalikan respon dengan data merchant yang baru dibuat
        return response()->json($merchant, 201);
    }

    // Fungsi untuk mendapatkan data merchant berdasarkan ID
    public function show($id)
    {
        return Merchant::findOrFail($id);
    }

    // Fungsi untuk memperbarui data merchant berdasarkan ID
    public function update(Request $request, $id)
    {
        // Cari data merchant berdasarkan ID
        $merchant = Merchant::findOrFail($id);

        // Validasi input data
        $validator = Validator::make($request->all(), [
            'country_code' => 'sometimes|required|string',
            'merchant_name' => 'sometimes|required|string|max:255',
        ]);

        // Jika validasi gagal, kembalikan respon dengan error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Perbarui data merchant
        $merchant->update($request->all());

        // Kembalikan respon dengan data merchant yang telah diperbarui
        return response()->json($merchant);
    }

    // Fungsi untuk menghapus data merchant berdasarkan ID
    public function destroy($id)
    {
        // Cari data merchant berdasarkan ID
        $merchant = Merchant::findOrFail($id);
        
        // Hapus data merchant
        $merchant->delete();

        // Kembalikan respon tanpa konten
        return response()->json(null, 204);
    }
}
