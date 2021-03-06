<?php

namespace App\Http\Resources\Products;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Collection) {
            return ProductVariationResource::collection($this->resource);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->name,
            'price' => [
                'detailed' => $this->detailedPrice,
                'formatted' => $this->formattedPrice,
            ],
            'price_varies' => $this->priceVaries(),
            'in_stock' => $this->inStock(),
            'stock_count' => $this->stockCount(),
            'base_product' => ProductIndexResource::make($this->product)
        ];
    }
}
