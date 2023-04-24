<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wish_lists';
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id', 'product_id', 'is_wish'];
}
