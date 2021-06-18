<?php

namespace App\Requests\Outlets;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptyOutletsRequest extends BaseRequest
{
}

class GetEmptyOutletsRequestHandler {

    public function Serve($request){

        $outlets = [
            'id'        => 0,
            "name"      => '',
            "phone"     => '',
            'email'     => '',
            "address"   => '',
            "latitude"  => '',
            "longitude" => '',
            "image"     => '',
            "registers" => [],
            "facebook"  => '',
            "twitter"   =>'',
            "instagram" => '',
            "snap_chat" => '',
            "allow_online_order" => '',
            "enable_business_hours" =>''
        ];

        return new Response(true, $outlets);
    }
} 