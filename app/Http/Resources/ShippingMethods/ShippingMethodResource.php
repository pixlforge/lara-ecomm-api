<?php

namespace App\Http\Resources\ShippingMethods;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingMethodResource extends JsonResource
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
            'price' => [
                'detailed' => $this->detailedPrice,
                'formatted' => $this->formattedPrice
            ]
        ];
    }
}
