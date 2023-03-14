<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'serial',
        'status',
        'description',
        'photo',
        'user_id'
    ];

    /**
     *@param array $input
     *@return Builder|Model
     */

    Final public function storeCategory(array $input):Builder|Model
    {
        return self::query()->create($input);
    }
}
