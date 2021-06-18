<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductAddOn;
use Auth;
class GetRelatedAddOnRequest extends BaseRequest{
   
    public $id;
    
}
class GetRelatedAddOnRequestHandler {

    public function Serve($request){
        $related = ProductAddOn::where('product_id',$request->id)->get()->toArray();
    	return new Response(true, $related);
    }
} 