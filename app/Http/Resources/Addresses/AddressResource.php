<?php

namespace App\Http\Resources\Addresses;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Countries\CountryResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address_1' => $this->address_1,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'country' => new CountryResource($this->country),
            'default' => $this->default
        ];
    }
}
