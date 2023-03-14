<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     *@param AuthRequest $request
     *@return \Illuminate\Http\JsonResponse
     */

    final public function login(AuthRequest $request):JsonResponse
    {
        $user = (new User())->getUserEmailOrPhone($request->all());

        if($user && Hash::check($request->input('password'), $user->password)){
            $user_data['token'] = $user->createToken($user->email)->plainTextToken;
            $user_data['name'] = $user->name;
            $user_data['phone'] = $user->phone;
            $user_data['photo'] = $user->photo;
            $user_data['email'] = $user->email;
            $user_data['role_id'] = $user->role_id;
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
