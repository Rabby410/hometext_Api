<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function storeProduct($product_data)
    {
//        return $this->prepareData($input, $auth_id);
        // return self::create($product_data);
    }
    /**
     * @param array $input
     * @param int $auth_id
     * @return array
     */
    final public function prepareData(array $input, int $auth_id):array
    {
        return [
            'brand_id' => $input['brand_id'] ?? '',
            'category_id' => $input['category_id'] ?? '',
            'cost' => $input['cost'] ?? '',
            'country_id' => $input['country_id'] ?? '',
            'description' => $input['description'] ?? '',
            'discount_end' => $input['discount_end'] ?? '',
            'discount_fixed' => $input['discount_fixed'] ?? '',
            'discount_percent' => $input['discount_percent'] ?? '',
            'discount_start' => $input['discount_start'] ?? '',
            'name' => $input['name'] ?? '',
            'sku' => $input['sku'] ?? '',
            'status' => $input['status'] ?? '',
            'stock' => $input['stock'] ?? '',
            'sub_category_id' => $input['sub_category_id'] ?? '',
            'supplier_id' => $input['supplier_id'] ?? '',
            'supplier_id' => $input['supplier_id'] ?? '',
            'created_by_id' => $auth_id,
            'updated_by_id' => $auth_id
        ];
    }
}
