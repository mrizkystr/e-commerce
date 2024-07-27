<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'description' => $this->description,
            // 'product' => $this->whenLoaded('products', function () {
            //     return new ProductResource($this->product);
            // }),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }

    // public function with($request)
    // {
    //     return [
    //         'detail_Products' => $this->toArray($request)
    //     ];
    // }
}
