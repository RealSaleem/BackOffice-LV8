<?php

namespace App\Helpers;

use App\Models\Permission;
use App\Models\Permission_role;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use App\Models\UserRole;

class Helper
{

    public static function send_sms($number, $msg)
    {
        $countryCode = '965';
        $gateway_url = config('sms.gateway_url');
        $password = config('sms.password');
        $user_name = config('sms.user_name');
        $sender_id = config('sms.sender_id');

        $url = $gateway_url
            . '?UID=' . $user_name
            . '&P=' . $password
            . '&S=' . $sender_id
            . '&G=' . $countryCode . $number
            . '&M=' . urlencode($msg);

        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        // close curl resource to free up system resources
        $output = curl_exec($ch);
        curl_close($ch);
        /*
         * if response string starts with '00 2', it means
         * sms sent successfully
         */
        return ['response' => $output, 'success' => substr($output, 0, 4) == '00 2'];
    }

    public static function send_email($to, $body, $subject, $to_name)
    {
        $str = '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Body>
                <sendEmail xmlns="http://tempuri.org/">
                  <toEmail>' . $to . '</toEmail>
                  <toEmailName>' . $to_name . '</toEmailName>
                  <emailSubject>' . $subject . '</emailSubject>
                  <emailBody>' . $body . '</emailBody>
                </sendEmail>
              </soap:Body>
            </soap:Envelope>';
        $url = 'http://pay.hadeyaa.com//PayGatewayServicev3.asmx?op=sendEmail';
        $soap_do = curl_init();
        curl_setopt($soap_do, CURLOPT_URL, $url);
        curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $str);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml; charset=utf-8',
            'Content-Length: ' . strlen($str),
        ));

        curl_setopt($soap_do, CURLOPT_HTTPHEADER, array(
            'Content-type: text/xml',
        ));

        $result = curl_exec($soap_do);

        curl_close($soap_do);
        echo htmlspecialchars($result);
    }

    public static function process_payment($totalAmount, $userName, $userEmail, $userPhone)
    {
        $merchant_code = config('payment.merchant_code');
        $merchant_username = config('payment.merchant_username');
        $merchant_password = config('payment.merchant_password');
        $post_url = config('payment.url');


        $callback_urls = config('payment.payment_callback_urls');
        $successUrl = $callback_urls['success'];
        $errorUrl = $callback_urls['error'];
        $email = ($userEmail == null) ? 'payments@ajaraty.com' : $userEmail;
        $product = "<ProductDC>
        <product_name>Ajaraty Product</product_name>
        <unitPrice>" . $totalAmount . "</unitPrice>
        <qty>1</qty>
        </ProductDC>";

        $post_string = '<?xml version="1.0" encoding="windows-1256"?>
                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                    <soap12:Body>
                    <PaymentRequest xmlns="http://tempuri.org/">
                        <req>
                            <CustomerDC>
                                <Name>' . $userName . '</Name>
                                <Email>' . $email . '</Email>
                                <Mobile>' . $userPhone . '</Mobile>
                            </CustomerDC>
                            <MerchantDC>
                                <merchant_code>' . $merchant_code . '</merchant_code>
                                <merchant_username>' . $merchant_username . '</merchant_username>
                                <merchant_password>' . $merchant_password . '</merchant_password>
                                <merchant_ReferenceID>' . time() . '</merchant_ReferenceID>
                                <payment_mode>1</payment_mode>
                                <mode>' . config('payment.payment_mode') . '</mode>
                                <ReturnURL>' . $successUrl . '</ReturnURL>
                                <merchant_error_url>' . $errorUrl . '</merchant_error_url>
                            </MerchantDC>
                            <lstProductDC>
                                ' . $product . '
                            </lstProductDC>
                            <languageDC>
                              <languageMode>EN</languageMode>
                            </languageDC>
                        </req>
                    </PaymentRequest>
                    </soap12:Body>
                    </soap12:Envelope>';

        $file_contents = Helper::perform_soap_curl($post_string, $post_url);

        if (!empty($file_contents)) {
            $doc = new \DOMDocument();
            $doc->loadXML(html_entity_decode($file_contents));
            $response = [];
            $ResponseCode = $doc->getElementsByTagName("ResponseCode");
            if (!empty($ResponseCode) && $ResponseCode != '') {
                try {
                    $response['code'] = $ResponseCode->item(0)->nodeValue;
                } catch (Exception $e) {
                    $response['success'] = false;
                    return $response;
                }
            } else {
                $response['success'] = false;
                return $response;
            }

            $paymentUrl = $doc->getElementsByTagName("paymentURL");
            $response['paymentUrl'] = $paymentUrl->item(0)->nodeValue;

            $referenceID = $doc->getElementsByTagName("referenceID");
            $response['referenceID'] = $referenceID->item(0)->nodeValue;

            $ResponseMessage = $doc->getElementsByTagName("ResponseMessage");
            $response['ResponseMessage'] = $ResponseMessage->item(0)->nodeValue;
            $response['success'] = true;
            return $response;
        } else {
            echo('<h1>Payment Services Unavailable</h1>');
            exit();

            $response['success'] = false;
            return $response;
        }
    }

    public static function get_payment_status($referenceID, $post_url)
    {
        $post_string = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                      <soap:Body>
                        <GetOrderStatusRequest xmlns="http://tempuri.org/">
                          <getOrderStatusRequestDC>
                            <merchant_code>' . config('payment.merchant_code') . '</merchant_code>
                            <merchant_username>' . config('payment.merchant_username') . '</merchant_username>
                            <merchant_password>' . config('payment.merchant_password') . '</merchant_password>
                            <referenceID>' . $referenceID . '</referenceID>
                          </getOrderStatusRequestDC>
                        </GetOrderStatusRequest>
                      </soap:Body>
                    </soap:Envelope>';
        $file_contents = Helper::perform_soap_curl($post_string, $post_url);
        if (!empty($file_contents)) {
            $doc = new \DOMDocument();
            $doc->loadXML(html_entity_decode($file_contents));
            $response = [];
            $ResponseCode = $doc->getElementsByTagName("ResponseCode");
            $ResponseCode = $ResponseCode->item(0)->nodeValue;

            $ResponseMessage = $doc->getElementsByTagName("ResponseMessage");
            $response['message'] = $ResponseMessage->item(0)->nodeValue;

            $response['code'] = $ResponseCode;
            if ($ResponseCode == 0) {

                $response['payment_mode'] = Helper::getNodeValue($doc, "Paymode");

                $response['payment_no'] = Helper::getNodeValue($doc, "PayTxnID");

                $response['track_id'] = Helper::getNodeValue($doc, "TransID");

                $response['ref_id'] = Helper::getNodeValue($doc, "RefID");

                $response['auth_id'] = Helper::getNodeValue($doc, "AuthID");

                $response['service_tracking_id'] = Helper::getNodeValue($doc, "TrackingID");

                $response['result'] = Helper::getNodeValue($doc, "result");

                $response['success'] = true;
            } else {

                $response['payment_mode'] = Helper::getNodeValue($doc, "Paymode");

                $response['payment_no'] = Helper::getNodeValue($doc, "PayTxnID");

                $response['track_id'] = Helper::getNodeValue($doc, "TransID");

                $response['ref_id'] = Helper::getNodeValue($doc, "RefID");

                $response['service_tracking_id'] = Helper::getNodeValue($doc, "TrackingID");

                $response['result'] = Helper::getNodeValue($doc, "result");

                $response['success'] = false;
            }
            return $response;
        } else {
            $response['success'] = false;
            return $response;
        }
    }

    private static function getNodeValue($doc, $nodeName)
    {
        $node = $doc->getElementsByTagName($nodeName);
        if ($node->item(0) != null) {
            return $node->item(0)->nodeValue;
        } else {
            return null;
        }
    }

    public static function perform_soap_curl($post_string, $post_url)
    {
        $user = config('payment.user');
        $password = config('payment.password');
        $url = $post_url;//config('payment.url');

        $soap_do = curl_init();
        curl_setopt($soap_do, CURLOPT_URL, $url);
        curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml; charset=utf-8',
            'Content-Length: ' . strlen($post_string),
        ));
        curl_setopt($soap_do, CURLOPT_USERPWD, $user . ":" . $password);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, array(
            'Content-type: text/xml',
        ));

        $result = curl_exec($soap_do);

        curl_close($soap_do);
        return htmlspecialchars($result);
    }

    public static function random_string($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function store()
    {
        $stores = Store::with(['subscription' => function ($q) {
            return $q->where('active', 1)->with(['package' => function ($c) {
                return $c->with('package_name');
            }, 'subscription_items' => function ($a) {
                return $a->with(['module']);
            }]);
        }])->find(\Auth::user()->store_id);

        return $stores;
    }


    public static function getRole($role)
    {
        foreach ($role as $roles) {

            $RoleName = \App\Models\Role::where('id', $roles->role_id)->first();
            $role = isset($RoleName->name) ? $RoleName->name : 'Not Assigned';
            return $role;
        }

    }


    public static function getSlug($OutletName, $id)
    {
        $name = $OutletName;
        $store = \Auth::user()->store->name;
        //        $slug = $name."-".str_replace(' ', '', $store);
        $OutletName = explode(" ", $store)[0] . '-' . str_replace(' ', '', $OutletName) . '-' . $id;
        return $OutletName;
    }





    public static function chekStatus($permission){

        $role = \Auth::user()->roles->first();
        if($role != null){
            $permission = $role->permissions->where('name',$permission)->first();
            if($permission != null){
                return true;
            }
        }
        return false;
    }
    public static function substrwords($text, $maxchar, $end='...') {
        if (strlen($text) > $maxchar || $text == '') {
            $words = preg_split('/\s/', $text);
            $output = '';
            $i      = 0;
            while (1) {
                $length = strlen($output)+strlen($words[$i]);
                if ($length > $maxchar) {
                    break;
                }
                else {
                    $output .= " " . $words[$i];
                    ++$i;
                }
            }
            $output .= $end;
        }
        else {
            $output = $text;
        }
        return $output;
    }
    public static function compressString($string_data,$count= null)
    {
        $words = "";
        if($count != null){
            $words = $count;
        }else{
            $words  = 16;
        }
        $reciept_disclaimer = $string_data;
        $des_word_array = explode(' ',$reciept_disclaimer);
        $des_word_count = count($des_word_array);
        if($des_word_count > $words){
            $des_arr = array_slice($des_word_array, 0, $words);
            $reciept_disclaimer = implode(" ",$des_arr);
            $string_data = $reciept_disclaimer.'...';
        }

       return $string_data;
    }


}

if (! function_exists('chekStatus')) {
    function chekStatus($permission){

        $role = Auth::user()->roles->first();
        if($role != null){
            $permission = $role->permissions->where('name',$permission)->first();
            if($permission != null){
                return true;
            }
        }
        return false;
    }
}
