<?php

namespace App\Http\Controllers\web_api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class OrderDetailsController extends Controller
{

    const REGISTER_USER = 1;
    const GUEST_USER = 2;
    const RETURN_USER = 3;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['checkout', 'myorder']]);
    }


    public function myorder()
    {
        if (Auth::check()) {
            $customer = Customer::where('user_id', '=',Auth::user()->id)->firstOrFail();
            $order = [];
            if($customer){
                $order = Order::where('customer_id', '=',$customer->id )->get();
            }
            return response()->json([
                'status' => 'success',
                'user' => Auth::user(),
                'customer' =>$customer,
                'order' =>$order,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'user' => [],
            ], 200);
        }
    }
}
