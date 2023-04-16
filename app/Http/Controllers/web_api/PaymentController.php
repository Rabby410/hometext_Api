<?php

namespace App\Http\Controllers\web_api;

use App\Http\Controllers\Controller;
use App\Models\gateway\PaymentGateWay;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getpaymentdetails(Request $request)
    {
        

        $pay = [
            'user' =>PaymentGateWay::retrieveToken(),
            'urls' =>PaymentGateWay::Url(),
        ];
        return response()->json(['error' =>$pay], 401);
    }
}
