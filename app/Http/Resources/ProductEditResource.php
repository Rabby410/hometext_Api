<?php

namespace App\Http\Resources;

use App\Manager\ImageUploadManager;
use App\Manager\PriceManager;
use App\Models\Product;
use App\Models\ProductPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'cost'=>$this->cost . PriceManager::CURRENCY_SYMBOL,
            'price'=>number_format($this->price) . PriceManager::CURRENCY_SYMBOL,
            'original_price'=>$this->price,
            'price_formula'=>$this->price_formula,
            'field_limit'=>$this->field_limit,
            'sell_price'=>PriceManager::calculate_sell_price($this->price, $this->discount_percent, $this->discount_fixed, $this->discount_start, $this->discount_end ),
            'sku'=>$this->sku,
            'stock'=>$this->stock,
            'isFeatured'=>$this->isFeatured,
            'isNew'=>$this->isNew,
            'isTrending'=>$this->isTrending,
            'status'=>$this->status == Product::STATUS_ACTIVE ? 'Active':'Inactive',
            'discount_fixed'=>$this->discount_fixed . PriceManager::CURRENCY_SYMBOL,
            'discount_percent'=>$this->discount_percent . '%',
            'description'=>$this->description,
            'discount_start'=>$this->discount_start != null ? Carbon::create($this->discount_start) ->toDayDateTimeString(): null,
            'discount_end'=>$this->discount_end != null ? Carbon::create($this->discount_end)->toDayDateTimeString():null,

            'brand'=>$this->brand?->name,
            'category'=>$this->category?->name,
            'sub_category'=>$this->sub_category?->name,
            'child_sub_category'=>$this->child_sub_category?->name,
            'supplier'=>$this->supplier ? $this->supplier?->name . ' ' . $this->supplier?->phone : null,
            'country'=>$this->country?->name,
            'updated_by'=>$this->updated_by?->name,
            'primary_photo_preview'=>ImageUploadManager::prepareImageUrl(ProductPhoto::THUMB_PHOTO_UPLOAD_PATH, $this->primary_photo?->photo),

            'attributes'=>ProductAttributeListResource::collection($this->product_attributes),
        ];
    }
}
