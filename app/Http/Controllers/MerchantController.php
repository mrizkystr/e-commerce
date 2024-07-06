<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{
    public function index()
    {
        return Merchant::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|unique:merchants,id',
            'country_code' => 'required|integer',
            'merchant_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $merchant = Merchant::create($request->all());

        return response()->json($merchant, 201);
    }

    public function show($id)
    {
        return Merchant::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $merchant = Merchant::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id' => 'sometimes|required|integer|unique:merchants,id,' . $id,
            'country_code' => 'sometimes|required|integer',
            'merchant_name' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $merchant->update($request->all());

        return response()->json($merchant);
    }

    public function destroy($id)
    {
        $merchant = Merchant::findOrFail($id);
        $merchant->delete();

        return response()->json(null, 204);
    }
}
