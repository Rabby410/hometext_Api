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
            'user' => PaymentGateWay::retrieveToken(),
            'urls' => PaymentGateWay::Url(),
        ];

        $return_data['is_success'] = false;
        $return_data['response_url'] = '';
        $return_data['status'] = 400;
        if ($pay['user']) {
            $getWay = [
                'buyer_name' => 'buyer_name',
                'buyer_email' => 'admin@gmail.com',
                'buyer_address' => 'buyer_address',
                'buyer_contact_number' => '01290112233',
                'amount' => '1',
                'order_details' => 'APP:order_details',
                'client_id' => 3,
                'order_id_of_merchant' => time() . rand(0, 100),
                'currency_of_transaction' => 'BDT',
                'callback_success_url' => 'http://localhost:3000/onlinepayment/paysuccess',
                'callback_fail_url' => 'http://localhost:3000/onlinepayment/payfaile',
                'callback_cancel_url' => 'http://localhost:3000/onlinepayment/paycancel',
                'callback_ipn_url' => 'http://localhost:3000/onlinepayment/payipn',
                'expected_response_type' => 'JSON',
                'cus_city' => 'dhaka',
                'cus_state' => 'dhaka',
                'cus_postcode' => 'dhaka',
                'cus_country' => 'dhaka',
                'currency' => 'BDT',
                'custom_1' => 'custom_1',
                'custom_2' => 'custom_2',
                'custom_3' => 'custom_3',
                'custom_4' => 'custom_4',
            ];
            $data = $this->getRedirectUrl(token: $pay['user'], gateWayInfo: $getWay);
            if ($data['error_code'] == 00) {
                $return_data['is_success'] = true;
                $return_data['response_url'] = $data['expected_response'];
                $return_data['status'] = 200;
            }
        }
        return response()->json(['response' => $return_data],  $return_data['status']);
    }
    // 
    /**
     * get redirect url 
     */
    private function getRedirectUrl(string $token, array $gateWayInfo)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PaymentGateWay::SUBMIT_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($gateWayInfo));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        // For certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // Set HTTP Header for POST request
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json;charset=UTF-8',
                'Accept: application/json',
                'X-Requested-With: XMLHttpRequest',
                'Authorization: Bearer ' . $token,
            )
        );
        $response = curl_exec($ch);
        curl_close($ch);
        $content = json_decode((string) $response, true);
        return  $content;
    }
}
