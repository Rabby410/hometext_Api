<?php

namespace App\Http\Controllers\web_api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class EcomUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['signup', 'myorder','registration']]);
    }

    // E-com user signup
    public function signup(Request $request)
    {
        $fields['password'] = 'required';
        $fields['username'] = 'required';    
        $validator = Validator::make($request->all(), $fields);
        if ($validator->fails()) {
            return response()->json(['status'=>400,'message'=>'validation_err','error' => $validator->errors()], 400);
        }
        // check valid user 
        $is_valid = Auth::attempt(['email' => $request->username, 'password' => $request->password]);
        if($is_valid){
            return response()->json(['status'=>200,'message'=>'success','token' => $is_valid], 200);
        }else {
            $validator->errors()->add('password', 'Login credential is not valid.');
            return response()->json(['status'=>400,'message'=>'validation_err','error' => $validator->errors()], 400);
        }
    }

    // registration
    
    public function registration(Request $request){
        $fields['password'] = 'required';
        $fields['username'] = 'required';    
        $fields['conf_password'] = 'required';    
        $fields['first_name'] = 'required';    
        $fields['email'] = 'required'; 
        $fields['phone'] = 'required'; 
        // $fields['is_subscribe'] = 'required'; 
        $validator = Validator::make($request->all(), $fields);

        if ($validator->fails()) {
            return response()->json(['status'=>400,'message'=>'validation_err','error' => $validator->errors()], 400);
        }
    }

    // // Sign out 
    // public function signout(){

    // }
}
