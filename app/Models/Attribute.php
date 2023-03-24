<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'user_id'];

    /**
     * @return LengthAwarePaginator
     */
    final public function getAttributeList(): LengthAwarePaginator
    {
        return self::query()->with('user')->orderBy('updated_at', 'desc')->paginate(50);
    }
    /**
     * @return BelongsTo
     */
    final public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
