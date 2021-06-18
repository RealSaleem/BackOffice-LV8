<?php
namespace App\Helpers;

use GuzzleHttp\Client;

class PaymentApi
{

    public static function process_payment($totalAmount, $user, $knet)
    {

        $payment_url        = config('payment.payment_api_url');
        $payment_alias      = config('payment.payment_api_alias');
        $payment_secret_key = config('payment.payment_api_secret_key');
        $payment_mode       = config('payment.payment_api_mode');
        
        if (!is_null($knet)){
            $payment_alias      = $knet->api_payment_alias;
            $payment_secret_key = $knet->api_payment_secret;
        }
   
        $callback_urls = config('payment.payment_callback_urls');
        $successUrl    = $callback_urls['success'];
        $errorUrl      = $callback_urls['error'];

        $client = new Client(['base_uri' => $payment_url, 'verify' => false]);
        $res = $client->request('POST', 'init_payment', [
            'form_params' => [

                'alias'         => $payment_alias, 
                'secret_key'    => $payment_secret_key,
                'mode'          => $payment_mode,
                'language'      => 'USA', //AR for arabic
                //'success_url'   => 'https://ajaraty.com/knet/knet/buy3success.php',
                //'error_url'     => 'https://ajaraty.com/knet/knet/buy3error.php',
                'success_url'   => $successUrl, //'http://localhost/knet_project/knet_live/buy3success.php',
                'error_url'     => $errorUrl, //'http://localhost/knet_project/knet_live/buy3error.php',
                'amount'        => $totalAmount,
                //'track_id'      =>  mt_rand(100000, 999999),
                'udf1'          => 'Ajaraty',
                'udf2'          => 'Ajaraty Udf 2',
                'udf3'          => 'Ajaraty',
                'udf4'          => 'Ajaraty Udf 4',
                'udf5'          => 'Ajaraty Udf 5'
            ]
        ]);
        
        if($res->getStatusCode() == 200){
            $response = json_decode($res->getBody()); 
            /*echo "<pre>";
            print_r($response);
            exit;*/
            $response_result = [];
            if($response->IsValid){    
                $result = $response->Payload;  

                $payURL   = $result->gateway_payment_page;
                //$payID    = $result->gateway_payment_id;

                $track_id = $result->track_id;
                //$response_result['paymentUrl'] = $payURL."?PaymentID=".$payID;
                $response_result['paymentUrl'] = $payURL;
                $response_result['referenceID'] = $track_id;
                $response_result['ResponseMessage'] = 'Success';
                $response_result['success']         = true;

                return $response_result;
            } else {
                $response_result['success'] = false;
                return $response_result;
            }

        } else {
            echo('<h1>Payment Services Unavailable</h1>');
            exit();
        }
    }

    public static function get_payment_status($track_id)
    {
        $payment_url = config('payment.payment_api_url');

        $client = new Client(['base_uri' => $payment_url, 'verify' => false]);
        $res = $client->request('GET', 'verify_payment?track_id='.$track_id);
        
        if($res->getStatusCode() == 200){
            $response = json_decode($res->getBody()); 

            $response_result = [];
            if($response->IsValid){ 
                $result = $response->Payload; 

                $response_result['payment_no'] = $result->gateway_payment_id;
                $response_result['track_id']   = $result->response_tran_id;
                $response_result['ref_id']     = $result->response_ref; 
                $response_result['service_tracking_id'] = $result->response_track_id;
                $response_result['result']     = $result->response_result;

                if($result->response_result == 'CAPTURED'){
                    $response_result['auth_id']     = $result->response_auth;
                    $response_result['success'] = true;
                } else {
                    $response_result['success'] = false;
                }      

                return $response_result;
            } else {
                $response_result['success'] = false;
                return $response_result;
            }

        } else {
            $response_result['success'] = false;
            return $response_result;
        }  
    }

}
