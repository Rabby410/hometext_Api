<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function storeOrderDetails(array $order_details, $order):void
    {
        foreach($order_details as $product){

            $order_details_data = $this->prepareData($product, $order);
            self::query()->create($order_details_data);
        }
    }

    public function prepareData($input, $order)
    {
        return [

            'order_id' => $order->id,
            'name' => $order->name,
            'brand_id' => $order->brand_id,
            'category_id' => $order->category_id,
            'cost' => $order->cost,
            'discount_end' => $order->discount_end,
            'discount_fixed' => $order->discount_fixed,
            'discount_percent' => $order->discount_percent,
            'discount_start' => $order->discount_start,
            'price' => $order->price,
            'sku' => $order->sku,
            'sub_category_id' => $order->sub_category_id,
            'supplier_id' => $order->supplier_id,
            'quantity' => $order->quantity,
            'photo' => $order->primary_photo?->photo,
        ];
    }
}
