<?php

namespace App\Helpers;

use App\Models\Messages;

class Messenger
{
    private $to;
    private $msg;

    public $response;
    public $success;
    public $language;

    public function __construct()
    {

    }

    public static function send_sms($to, $msg, $language = 'L', $pinCode = '')
    {

        $gateway_url = config('sms.gateway_url');
        $password    = config('sms.password');
        $user_name   = config('sms.user_name');
        $sender_id   = config('sms.sender_id');

        $language = preg_match('/[أ-ي]/ui', $msg) ? 'A' : 'L';
        
        $url = $gateway_url
        . '?UID=' . $user_name
        . '&P=' . $password
        . '&S=' . $sender_id
        . '&G=' . $to
        . '&M=' . urlencode($msg)
            . '&L=' . $language;

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
        $data             = null;
        $data['response'] = $output;
        $data['success']  = substr($output, 0, 4) == '00 2';
        //Messenger::saveSMSData($to, $msg, $data, $pinCode);
        return $data;
    }

    private static function saveSMSData($to, $message, $response, $pinCode)
    {
        $sms            = new Messages();
        $sms->to        = $to;
        $sms->message   = $message;
        $sms->sent_on   = date('Y-m-d h:m:s');
        $sms->response  = substr((string) $response['response'], 0, 24);
        $sms->success   = $response['success'] == true ? 1 : 0;
        $sms->pin_code  = $pinCode == null ? '' : $pinCode;
        $sms->is_active = 1;
        $sms->save();
    }
}
