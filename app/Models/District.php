<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * @param int $division_id
     * @return Builder[]|Collection
     */
    final public function getDistrictByDivisionId(int $division_id):Builder|Collection
    {
        return self::query()->select('id', 'name')->where('division_id', $division_id)->get();
    }
}
