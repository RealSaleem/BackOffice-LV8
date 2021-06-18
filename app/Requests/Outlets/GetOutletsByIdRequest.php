<?php

namespace App\Requests\Outlets;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Outlet;
use Auth;

class GetOutletsByIdRequest extends BaseRequest{

    public $id;

}
class GetOutletsByIdRequestHandler {

    public function Serve($request){
$ImageArray = [];
        $outlets = Outlet::with('registers')->find($request->id);

        array_push($ImageArray, ['name' => $outlets->name, 'url' => $outlets->image, 'size' => 0]);


        $outlets_template = [
            "id"                => $outlets->id,
            "name"              => $outlets->name,
            "phone"             => $outlets->phone,
            "email"             => $outlets->email,
            "street_1"          => $outlets->street_1,
            "latitude"          => $outlets->latitude,
            "longitude"         => $outlets->longitude,
            "is_active"         => $outlets->is_active,
            "enable_zone"       => $outlets->enable_zone,
            "pickup"            => $outlets->pickup,
            'min_order_value'   => $outlets->min_order_value,
            'registers'         => $outlets->registers->toArray(),
            'images'             => $outlets->image,
            "facebook"          => $outlets->facebook,
            "twitter"           => $outlets->twitter,
            "instagram"         => $outlets->instagram,
            "snap_chat"         => $outlets->snap_chat,
            "allow_online_order"  => $outlets->allow_online_order,
            "ImageArray"        => $ImageArray,

            "enable_business_hours" => $outlets->enable_business_hours
        ];

        return new Response(true, $outlets_template);
    }
}
