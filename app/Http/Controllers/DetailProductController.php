<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailProduct;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\DetailProductResource;
use Illuminate\Support\Facades\Log;

class DetailProductController extends Controller
{
    public function index () 
    {
        $details = DetailProduct::with('product')->get();
        return DetailProductResource::Collection($details);
    }

    public function store (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->error(), 422);
        }

        $existingDetail = DetailProduct::where('product_id', $request->product_id)->first();

        if ($existingDetail) {
            return response()->json([
                'error' => 'Detail Product with the same product_id alredy exists'
            ], 400);
        }

        $detail = DetailProduct::create($request->all());

        return response()->json([
            'detail_product' => new DetailProductResource($detail)
        ]);

    }

    public function show($id)
    {
        $detail = DetailProduct::with('product')->findOrFail($id);
        return new DetailProductResource($detail);
    }

    public function update(Request $request, $id) 
{
    $detail = DetailProduct::findOrFail($id);

    // Log::info('Existing Detail:', $detail->toArray());
    // Log::info('Update Request Data:', $request->all());

    $validator = Validator::make($request->all(), [
        'description' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $detail->update($request->only('description'));
    
    // Log::info('Updated Detail:', $detail->toArray());

    $detail->refresh();

    // Log::info('Refreshed Detail:', $detail->toArray());

    return response()->json(new DetailProductResource($detail));
}



    public function destroy ($id)
    {
        $detail = DetailProduct::findOrFail($id);
        $detail->delete();

        return response()->json(['message' => 'Detail Product Deleted'], 200);
    }
}
