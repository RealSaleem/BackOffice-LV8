<?php 
function send_sms($number, $msg){
    $countryCode = '965';
    $gateway_url = config('sms.gateway_url');
    $password = config('sms.password');
    $user_name = config('sms.user_name');
    $sender_id = config('sms.sender_id');

    $url = $gateway_url 
            . '?UID='   . $user_name 
            . '&P='     . $password 
            . '&S='     . $sender_id
            . '&G='     . $countryCode.$number
            . '&M='     . urlencode($msg);

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
    return ['response' => $output, 'success'=>substr($output, 0, 4) == '00 2'];
}

function send_email($to, $body, $subject, $to_name){
    $str = '<?xml version="1.0" encoding="utf-8"?>
            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
              <soap:Body>
                <sendEmail xmlns="http://tempuri.org/">
                  <toEmail>'. $to.'</toEmail>
                  <toEmailName>'.$to_name.'</toEmailName>
                  <emailSubject>'.$subject.'</emailSubject>
                  <emailBody>'.$body.'</emailBody>
                </sendEmail>
              </soap:Body>
            </soap:Envelope>';
    $url = 'http://pay.hadeyaa.com/paygatewayservice.asmx?op=sendEmail';
    $soap_do     = curl_init();
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
        'Content-Length: ' . strlen($str)
    ));
    
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, array(
        'Content-type: text/xml'
    ));
    
    $result = curl_exec($soap_do);
    
    curl_close($soap_do);
    echo htmlspecialchars($result);
}

function process_payment($totalAmount, $user){
    $callback_urls = config('payment.payment_callback_urls');
    $successUrl = $callback_urls['success'];
    $errorUrl = $callback_urls['error'];

    $product = "<ProductDC>
        <product_name>Hadeya's Product</product_name>
        <unitPrice>" . $totalAmount . "</unitPrice>
        <qty>1</qty>
        </ProductDC>";
      header("Content-type: text/xml");  
    echo $post_string = '<?xml version="1.0" encoding="windows-1256"?>
                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                    <soap12:Body>
                    <PaymentRequest xmlns="http://tempuri.org/">
                        <req>
                            <CustomerDC>
                                <Name>'. $user->username .'</Name>
                                <Email>'. $user->email .'</Email>
                                <Mobile>'. $user->tenant->mobile_number .'</Mobile>
                            </CustomerDC>
                            <MerchantDC>
                                <merchant_code>'.config('payment.merchant_code').'</merchant_code>
                                <merchant_username>'.config('payment.merchant_username').'</merchant_username>
                                <merchant_password>'.config('payment.merchant_password').'</merchant_password>
                                <merchant_ReferenceID>'. time() .'</merchant_ReferenceID>
                                <payment_mode>1</payment_mode>
                                <mode>TEST</mode>
                                <ReturnURL>'.$successUrl.'</ReturnURL>
                                <merchant_error_url>'.$errorUrl.'</merchant_error_url>
                            </MerchantDC>
                            <lstProductDC>
                                ' . $product . '
                            </lstProductDC>
                        </req>
                    </PaymentRequest>
                    </soap12:Body>
                    </soap12:Envelope>';
                    exit;
        $file_contents = perform_soap_curl($post_string);
        if(!empty($file_contents)){
            $doc = new \DOMDocument();
            $doc->loadXML(html_entity_decode($file_contents));
            $response = [];
            $ResponseCode = $doc->getElementsByTagName("ResponseCode");
            if(!empty($ResponseCode) && $ResponseCode!=''){
                try{  
                    $response['code'] = $ResponseCode->item(0)->nodeValue;
                }catch(Exception $e){
                    $response['success'] = false;
                    return $response;
                }
            }
            else{
                $response['success'] = false;
                return $response;
            } 
    
            $paymentUrl         = $doc->getElementsByTagName("paymentURL");
            $response['paymentUrl'] = $paymentUrl->item(0)->nodeValue;
            
            $referenceID         = $doc->getElementsByTagName("referenceID");
            $response['referenceID'] = $referenceID->item(0)->nodeValue;
            
            $ResponseMessage         = $doc->getElementsByTagName("ResponseMessage");
            $response['ResponseMessage'] = $ResponseMessage->item(0)->nodeValue;
            $response['success'] = true;
            return $response;
        }else{
            $response['success'] = false;
            return $response;
        }
}

function get_payment_status($referenceID){
    $post_string = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                      <soap:Body>
                        <GetOrderStatusRequest xmlns="http://tempuri.org/">
                          <getOrderStatusRequestDC>
                            <merchant_code>'.config('payment.merchant_code').'</merchant_code>
                            <merchant_username>'.config('payment.merchant_username').'</merchant_username>
                            <merchant_password>'.config('payment.merchant_password').'</merchant_password>
                            <referenceID>'.$referenceID.'</referenceID>
                          </getOrderStatusRequestDC>
                        </GetOrderStatusRequest>
                      </soap:Body>
                    </soap:Envelope>';
    $file_contents = perform_soap_curl($post_string);

    if(!empty($file_contents)){
        $doc = new \DOMDocument();
        $doc->loadXML(html_entity_decode($file_contents));
        $response = [];
        $ResponseCode = $doc->getElementsByTagName("ResponseCode");
        $ResponseCode = $ResponseCode->item(0)->nodeValue;
        
        $ResponseMessage = $doc->getElementsByTagName("ResponseMessage");
        $response['message'] = $ResponseMessage->item(0)->nodeValue;

        $response['code'] = $ResponseCode;
        if ($ResponseCode == 0) {
            $Paymode = $doc->getElementsByTagName("Paymode");
            $response['payment_mode'] = $Paymode->item(0)->nodeValue;
            
            $PayTxnID = $doc->getElementsByTagName("PayTxnID");
            $response['payment_no'] = $PayTxnID->item(0)->nodeValue;

            $transID = $doc->getElementsByTagName("TransID");
            $response['track_id'] = $transID->item(0)->nodeValue;

            $refID = $doc->getElementsByTagName("RefID");
            $response['ref_id'] = $refID->item(0)->nodeValue;
            $response['success'] = true;
        }else{
            $response['success'] = false;
        }
        return $response;
    }else{
        $response['success'] = false;
        return $response;
    }
}

function perform_soap_curl($post_string){
    $user = config('payment.user');
    $password = config('payment.password');
    $url = config('payment.url');

    $soap_do     = curl_init();
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
        'Content-Length: ' . strlen($post_string)
    ));
    curl_setopt($soap_do, CURLOPT_USERPWD, $user . ":" . $password);
    curl_setopt($soap_do, CURLOPT_HTTPHEADER, array(
        'Content-type: text/xml'
    ));
    
    $result = curl_exec($soap_do);
    
    curl_close($soap_do);
    return htmlspecialchars($result);
}

function random_string($length = 30){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>