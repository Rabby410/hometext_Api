<?php

namespace App\Http\Controllers\web_api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CheckOutController extends Controller
{

    const REGISTER_USER = 1;
    const GUEST_USER = 2;
    const RETURN_USER = 3;
    /**
     * @param int $division_id
     * @return JsonResponse
     */
    final public function checkout(Request $request)
    {


        // http://127.0.0.1:8000/api/check-out

        // {
        //     "user_type": "1",
        //     "pd_first_name":"F_Name",
        //     "pd_last_name":"L_Name",
        //     "pd_email":"admin@admin.com", 
        //     "pd_phone":"", 
        //     "pd_fax":"", 
        //     "username":"admin@hometexbd.ltd2", 
        //     "password":"123", 
        //     "billing_company":"", 
        //     "billing_address_1":"", 
        //     "billing_address_2":"", 
        //     "billing_post_code":"", 
        //     "billing_country":"", 
        //     "billing_district":"", 
        //     "shipping_frist_name":"", 
        //     "shipping_last_name":"", 
        //     "shipping_company":"", 
        //     "shipping_address_1":"", 
        //     "shipping_address_2":"", 
        //     "shipping_post_code":"", 
        //     "shipping_country":"", 
        //     "shipping_district":"", 
        //     "payment_method":"", 
        //     "shipping_method":"", 
        //     "coupon_code":"", 
        //     "voucher_code":"", 
        //     "product_details": "" 
        // }



        $post  = $request->post();
        if ($request->user_type == self::RETURN_USER) {
            $fields = [
                'password' => 'required',
                'username' => 'required',
            ];
        } else {
            $fields = [
                'pd_first_name' => 'required',
                // 'pd_last_name' => 'required',
                // 'pd_email' => 'required|email',
                'pd_phone' => 'required|numeric|digits:11',
                // 'billing_address_1' => 'required',
                // 'billing_country' => 'required',
                // 'billing_district' => 'required',
            ];
        }
        if ($request->user_type == self::REGISTER_USER) {           

            $fields['password'] = 'required';
            $fields['username'] = 'required'; 

        } else if ($request->user_type == self::GUEST_USER) {
        }

         
        $validator = Validator::make($request->all(), $fields);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        // check user already exist or not
        if ($request->user_type == self::REGISTER_USER) {
            $check_is_user_exist = User::where('email', '=', $request->username)->count();
            if ($check_is_user_exist) {
                $validator->errors()->add('username', 'Username already exist.');
                return response()->json(['error' => $validator->errors()], 401);
            }
        }

        try {
            $user = new User();
            $user->password = Hash::make($request->password);
            $user->email =  $request->username;
            $user->name =  $request->pd_first_name;
            $user->phone =  $request->pd_phone;
            $user->shop_id = 3;
            $user->save();


            $success['name'] = $user->name;
            $success['user_id'] = $user->id;
            return response()->json(['success' => $success], 200);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        print_r($post);
        echo 'Sanjib';
        //JsonResponse
        // $areas = (new Area())->getAreaByDistrictId($district_id);
        // return response()->json($areas);
    }
}
