<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Area extends Model
{
    use HasFactory;
    protected $guarded = [];

     /**
     * @param int $division_id
     * @return Builder[]|Collection
     */
    final public function getAreaByDistrictId(int $district_id):Builder|Collection
    {
        return self::query()->select('id as value', 'name as label')->where('district_id', $district_id)->get();
    }
}
