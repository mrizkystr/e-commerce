<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'merchant' => $this->merchant->merchant_name,
            'price' => $this->price,
            'status' => $this->status,
            'image_url' => asset($this->image), // Menggunakan asset() untuk path absolut dari public
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
