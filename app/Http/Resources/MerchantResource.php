<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'country_code' => $this->country_code,
            'merchant_name' => $this->merchant_name,
        ];
    }
}

