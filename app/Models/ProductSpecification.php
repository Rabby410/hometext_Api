<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    /**
     * @param array $input
     * @return array
     */
    final public function ProductSpecificationData(array $input):array
    {
        $specification_data = [];
        foreach ($input as  $key =>$value){
            $data['name'] =$value ['name'];
            $data['value'] =$value ['value'];
            $specification_data[]=$data;
        }
        return $specification_data;
    }
}
