<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Hash;

class SalesManager extends Model
{
    use HasFactory;

    protected $fillable = ['bio', 'email', 'nid_photo','nid', 'password', 'photo', 'name', 'phone', 'status', 'user_id', 'shop_id'];


    public const STATUS_ACTIVE = 1;
    public const STATUS_ACTIVE_TEXT = 'Active';

    public const STATUS_INACTIVE = 0;
    public const STATUS_INACTIVE_TEXT = 'Inactive';

    public const PHOTO_WIDTH = 800;
    public const PHOTO_HEIGHT = 800;
    public const PHOTO_THUMB_WIDTH = 200;
    public const PHOTO_THUMB_HEIGHT = 200;
    public const PHOTO_UPLOAD_PATH = 'images/uploads/sales_manager/';
    public const THUMB_PHOTO_UPLOAD_PATH = 'images/uploads/sales_manager_thumb/';

     /**
     * @param array $input
     * @param $auth
     * @return array
     */
    public function prepareData(array $input, $auth):array
    {
        $sales_manager['bio'] = $input['bio'] ?? null;
        $sales_manager['email'] = $input['email'] ?? null;
        $sales_manager['name'] = $input['name'] ?? null;
        $sales_manager['phone'] = $input['phone'] ?? null;
        $sales_manager['status'] = $input['status'] ?? 0;
        $sales_manager['user_id'] = $auth->id();
        $sales_manager['shop_id'] = $input['shop_id'] ?? null;
        $sales_manager['nid'] = $input['nid'];
        $sales_manager['password'] =Hash::make($input['password']) ;
        return $sales_manager;
    }
    /**
     * @return MorphOne
     */
    final public function address():MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
