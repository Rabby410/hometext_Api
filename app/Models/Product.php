<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
            'brand_id',
            'category_id',
            'country_id',
            'sub_category_id',
            'supplier_id',
            'created_by_id',
            'updated_by_id',
            'cost',
            'description',
            'discount_end',
            'discount_fixed',
            'discount_percent',
            'discount_start',
            'name',
            'price',
            'sku',
            'slug',
            'status',
            'stock',
    ];

    /**
     * @param array $input
     * @param int $auth_id
     * @return mixed
     */
    final public function storeProduct(array $input, int $auth_id):mixed
    {
        return self::create($this->prepareData($input, $auth_id));
    }
    /**
     * @param array $input
     * @param int $auth_id
     * @return array
     */
    private function prepareData(array $input, int $auth_id):array
    {
        return [
            'brand_id' => $input['brand_id'] ?? '',
            'category_id' => $input['category_id'] ?? '',
            'country_id' => $input['country_id'] ?? '',
            'sub_category_id' => $input['sub_category_id'] ?? '',
            'supplier_id' => $input['supplier_id'] ?? '',
            'created_by_id' => $auth_id,
            'updated_by_id' => $auth_id,
            'cost' => $input['cost'] ?? '',
            'description' => $input['description'] ?? '',
            'discount_end' => $input['discount_end'] ?? '',
            'discount_fixed' => $input['discount_fixed'] ?? '',
            'discount_percent' => $input['discount_percent'] ?? '',
            'discount_start' => $input['discount_start'] ?? '',
            'name' => $input['name'] ?? '',
            'price' => $input['price'] ?? '',
            'sku' => $input['sku'] ?? '',
            'slug' => $input['slug'] ? Str::slug($input['slug']) : '',
            'status' => $input['status'] ?? '',
            'stock' => $input['stock'] ?? '',
        ];
    }
    /**
     * @param int $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    final public function getProductById(int $id):Builder|Collection|Model|null
    {
        return self::query()->findOrFail($id);
    }
}
