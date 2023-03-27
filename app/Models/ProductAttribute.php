<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    /**
     * @param array $input
     * @param Product $product
     * @return array
     */
    final public function prepareAttributeData(array $input, Product $product):array
    {
        $attribute_data = [];
       foreach ($input as  $key =>$value){
            $data['attribute_id'] =$value ['attribute_id'];
            $data['attribute_value_id'] =$value ['value_id'];
            $attribute_data[]=$data;
       }
       return $attribute_data;
    }
}
