<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = ['details', 'email', 'logo', 'name', 'phone', 'status', 'user_id'];



    public const STATUS_ACTIVE = 1;
    public const STATUS_ACTIVE_TEXT = 'Active';

    public const STATUS_INACTIVE = 0;
    public const STATUS_INACTIVE_TEXT = 'Inactive';

    public const LOGO_WIDTH = 800;
    public const LOGO_HEIGHT = 800;
    public const LOGO_THUMB_WIDTH = 200;
    public const LOGO_THUMB_HEIGHT = 200;
    public const IMAGE_UPLOAD_PATH = 'images/uploads/shop/';
    public const THUMB_IMAGE_UPLOAD_PATH = 'images/uploads/shop_thumb/';

     /**
     * @param array $input
     * @param $auth
     * @return array
     */
    public function prepareData(array $input, $auth):array
    {
        $shop['details'] = $input['details'] ?? '';
        $shop['email'] = $input['email'] ?? '';
        $shop['name'] = $input['name'] ?? '';
        $shop['phone'] = $input['phone'] ?? '';
        $shop['status'] = $input['status'] ?? '';
        $shop['user_id'] = $auth->id();
        return $shop;
    }

    /**
     * @return MorphOne
     */
    final public function address():MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    final public function getShopList($input)
    {
        $per_page = $input['per_page'] ??10;

        $query =self::query()->with(
            'address',
            'address.division:id,name',
            'address.district:id,name',
            'address.area:id,name',
            'user:id,name',
        );
        if(!empty($input['search'])){
            $query -> where('name', 'like', '%'.$input['search'].'%')
            ->orWhere('phone', 'like', '%'.$input['search'].'%')
            ->orWhere('email', 'like', '%'.$input['search'].'%');
        }
        if (!empty($input['order_by'])) {
            $query->orderBy($input['order_by'], $input['direction'] ?? 'asc');
        }
        return $query->paginate($per_page);
    }
    /**
     * @return BelongsTo
     */
    final public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * @param Shop $shop
     * @return int
     */
    final public function deleteAddressByShopId(Shop $shop):int
    {
        return $shop->address()->delete();
    }

    public function getShopListIdName()
    {
        return self::query()->select(['id', 'name'])->where('status', 1)->get();
    }

    public function getShopDetailsById($id)
    {
        return self::query()->with('address',
        'address.division:id,name',
        'address.district:id,name',
        'address.area:id,name')->findOrFail($id);
    }

    public function getShopIdAndName()
    {
        return self::query()->select('id as value', 'name as label')->get();
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'shop_product')
            ->withPivot('quantity')
            ->using(ShopProduct::class);
    }
}
