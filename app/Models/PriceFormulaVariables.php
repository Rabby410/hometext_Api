<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceFormulaVariables extends Model
{
    use HasFactory;
    public const STATUS_ACTIVE = 1;

    protected $fillable = ['name','short_name','status','user_id'];
    final public function storePriceFormulaVariables(array $input): Builder|Model
    {
        return self::query()->create($input);
    }
    /**
     *@param array $input
     * @return LengthAwarePaginator
     */
    final public function getAllPriceFormulaVariables(array $input):LengthAwarePaginator
    {
        $per_page = $input['per_page'] ?? 10;
        $query = self::query();
        if (!empty($input['search'])) {
            $query->where('name', 'like', '%' . $input['search'] . '%');
        }
        if (!empty($input['order_by'])) {
            $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }
        return $query->with('user:id,name')->paginate($per_page);
    }

    /**
     *@return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    final public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
