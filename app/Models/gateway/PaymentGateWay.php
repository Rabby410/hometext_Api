<?php

namespace App\Models\gateway;

use Exception;
use Illuminate\Database\Eloquent\Model;

class PaymentGateWay extends Model
{
    /*
     * Please change it as per your integrated site
     */
    const BASE_URL = 'https://payment.hometexbd.ltd/';
    // const BASE_URL = 'http://localhost:3000/onlinepayment/';

    /*
	 *  Application URL
	 */
    const SUBMIT_URL = self::BASE_URL . 'api/v1.0/pay';
    const TOKEN_URL = self::BASE_URL . 'api/v1.0/login';
    const CLIENT_ID = 3;  // Required


    /** this is success url, after payment success  will redirect this link
     * THIS LINK IS REQUIRED
     * */
    const SUCCESS_URL = 'paysuccess';

    /** this is fail url, after payment success  will redirect this link
     * THIS LINK IS REQUIRED
     * */
    const FAIL_URL = 'payfail';

    /** this is cancel url, after payment success  will redirect this link
     * THIS LINK IS REQUIRED
     * */
    const CANCEL_URL =  'paycancel';

    /** this is ipn url, after payment success  will redirect this link
     * THIS LINK IS REQUIRED
     * THIS IS OPTIONAL , IF YOU USE IT YOU PAYMENT THEN IT'S REQUIRED
     * */
    const IPN_URL = 'payipn';

    const CLIENT_USER_NAME = 'info@deshiattire.com'; // Required
    const CLIENT_PASSWORD = 'Open@123'; // Required

    /**
     * @desc Get `access_token` for HTTP authorization
     */
    public static function retrieveToken()
    {
        try {
            $requestParams = [
                'email' => self::CLIENT_USER_NAME,
                'password' => self::CLIENT_PASSWORD
            ];

            $payload = json_encode($requestParams);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::TOKEN_URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            // For certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            // Set HTTP Header for POST request
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($payload)
                )
            );

            $response = curl_exec($ch);
            curl_close($ch);
            $content = json_decode((string) $response, true);
            if ($content['status'] != "success") {
                echo $content['message'];
                return '';
            }
            return $content['data']['token'];
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /// Success, Fail, Cancel URL
    public static function Url()
    {
        $url = [
            'callback_success_url' => self::BASE_URL . self::SUCCESS_URL,
            'callback_fail_url' => self::BASE_URL . self::FAIL_URL,
            'callback_cancel_url' => self::BASE_URL . self::CANCEL_URL,
            'callback_ipn_url' => self::BASE_URL . self::IPN_URL
        ];
        return $url;
    }

    // public function showCallbackResponse($data)
    // {
    //     if (isset($data['payment_status'])) {
    //         if ($data['payment_status'] == 'Successful') {
    //             echo "Success!!!";
    //             echo "<br />" . "";
    //         } else {
    //             echo "Sorry your Transection was Failed !";
    //             echo "<br />" . "";
    //         }

    //         echo "<br />" . "Amount#: {$data['amount']}";
    //         echo "<br />" . "OrderId#: {$data['merchant_txnid']}";
    //         echo "<br />" . "TransactionId#: {$data['psz_txnid']}";

    //         if (isset($data['remarks']) && !empty($data['remarks'])) {
    //             $paymentInfo = json_decode($data['remarks']);
    //             echo "<br />" . "Card#: {$paymentInfo->card_no}";
    //             echo "<br />" . "Card Holder Name: {$paymentInfo->card_holder_name}";
    //             echo "<br />" . "Card Type: {$paymentInfo->card_type}";
    //         }
    //     } else {
    //         echo "Invalid response";
    //     }
    // }
}
