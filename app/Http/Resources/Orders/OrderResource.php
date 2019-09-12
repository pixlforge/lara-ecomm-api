<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\Addresses\AddressResource;
use App\Http\Resources\Products\ProductVariationResource;
use App\Http\Resources\ShippingMethods\ShippingMethodResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'subtotal' => [
                'detailed' => $this->subtotal->detailed(),
                'formatted' => $this->subtotal->formatted()
            ],
            'total' => [
                'detailed' => $this->total()->detailed(),
                'formatted' => $this->total()->formatted()
            ],
            'products' => ProductVariationResource::collection($this->whenLoaded('products')),
            'address' => AddressResource::make($this->whenLoaded('address')),
            'shippingMethod' => ShippingMethodResource::make($this->whenLoaded('shippingMethod'))
        ];
    }
}
