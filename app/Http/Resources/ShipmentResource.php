<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;

class ShipmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'province' => $this->getProvinceName($this->province_id),
            'city' => $this->getCityName($this->city_id),
            'district' => $this->getDistrictName($this->district_id),
            'village' => $this->getVillageName($this->village_id),
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }

    private function getProvinceName($provinceId)
    {
        $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/province/{$provinceId}.json");
        return $response->json()['name'] ?? 'Unknown';
    }

    private function getCityName($cityId)
    {
        $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/city/{$cityId}.json");
        return $response->json()['name'] ?? 'Unknown';
    }

    private function getDistrictName($districtId)
    {
        $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/district/{$districtId}.json");
        return $response->json()['name'] ?? 'Unknown';
    }

    private function getVillageName($villageId)
    {
        $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/village/{$villageId}.json");
        return $response->json()['name'] ?? 'Unknown';
    }
}
