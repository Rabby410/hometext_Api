<?php

namespace App\Http\Resources;

use App\Manager\PriceManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListForBarCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Filter active attributes and include their IDs, names, and values
        $activeAttributes = $this->product_attributes->filter(function ($attribute) {
            return $attribute->attributes->status == 1;
        })->map(function ($attribute) {
            return [
                'id' => $attribute->attributes->id,
                'name' => $attribute->attributes->name,
                'values' => [
                    'id' => $attribute->attribute_value->id,
                    'name' => $attribute->attribute_value->name,
                ],
            ];
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand?->name,
            'price' => number_format($this->price) . PriceManager::CURRENCY_SYMBOL,
            'sell_price' => PriceManager::calculate_sell_price($this->price, $this->discount_percent, $this->discount_fixed, $this->discount_start, $this->discount_end),
            'sku' => $this->sku,
            'attributes' => $activeAttributes,
        ];
    }
}
