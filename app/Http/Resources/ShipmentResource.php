<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'province' => $this->province,
            'city' => $this->city,
            'district' => $this->district,
            'neighborhoods' => $this->neighborhoods,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
