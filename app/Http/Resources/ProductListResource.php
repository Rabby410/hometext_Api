<?php

namespace App\Http\Resources;

use App\Manager\ImageUploadManager;
use App\Models\Product;
use App\Models\ProductPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
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
            'slug'=>$this->sluge,
            'cost'=>$this->cost,
            'price'=>$this->price,
            'sku'=>$this->sku,
            'stock'=>$this->stock,
            'status'=>$this->status == Product::STATUS_ACTIVE ? 'Active':'Inactive',
            'discount_fixed'=>$this->discount_fixed,
            'discount_percent'=>$this->discount_percent,
            'description'=>$this->description,
            'created_at'=>$this->created_at->toDayDateTimeString(),
            'updated_at'=>$this->updated_at == $this->updated_at ? 'Not Updated' : $this->updated_at->toDayDateTimeString(),
            'discount_start'=>$this->discount_start != null ? Carbon::create($this->discount_start) ->toDayDateTimeString(): null,
            'discount_end'=>$this->discount_end != null ? Carbon::create($this->discount_end)->toDayDateTimeString():null,

            'brand'=>$this->brand?->name,
            'category'=>$this->category?->name,
            'sub_category'=>$this->sub_category?->name,
            'supplier'=>$this->supplier ? $this->supplier?->name . ' ' . $this->supplier?->phone : null,
            'country'=>$this->country?->name,
            'created_by'=>$this->created_by?->name,
            'updated_by'=>$this->updated_by?->name,
            'primary_photo'=>ImageUploadManager::prepareImageUrl(ProductPhoto::THUMB_PHOTO_UPLOAD_PATH, $this->primary_photo?->photo),

            'attributes'=>$this->product_attributes;
        ];
    }
}
