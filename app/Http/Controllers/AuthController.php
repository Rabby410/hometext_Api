<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\ShopListResource;
use App\Models\SalesManager;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public const ADMIN_USER = 1;
    public const SALES_MANAGER = 2;
    /**
     *@param AuthRequest $request
     *@return \Illuminate\Http\JsonResponse
     */

    final public function login(AuthRequest $request):JsonResponse
    {
        if($request->input('user_type') == self::ADMIN_USER){
            $user = (new User())->getUserEmailOrPhone($request->all());
            $role = self::ADMIN_USER;
        }else{
            $user= (new SalesManager())->getUserEmailOrPhone($request->all());
            $role = self::SALES_MANAGER;
        }
        if($user && Hash::check($request->input('password'), $user->password)){
            $branch = null;
            if($role == self::SALES_MANAGER){
                $branch = (new Shop())->getShopDetailsById($user->shop_id);
            }
            $user_data['token'] = $user->createToken($user->email)->plainTextToken;
            $user_data['name'] = $user->name;
            $user_data['phone'] = $user->phone;
            $user_data['photo'] = $user->photo;
            $user_data['email'] = $user->email;
            $user_data['role'] = $role;
            $user_data['branch'] = new ShopListResource($branch);
            return response()->json($user_data);
        }
        throw ValidationException::withMessages([
            'email'=> ['The Provided credentials are incorrect']
        ]);
    }

     /**
     *@return \Illuminate\Http\JsonResponse
     */
    public function logout():JsonResponse
    {
        Auth::user()->tokens->each(function($token) {
            $token->delete();
        });
        return response()->json(['msg'=> "You have successfully logout"]);
    }
}
