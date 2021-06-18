<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductRelated as ProductRelated;
use Auth;
class GetRelatedRequest extends BaseRequest{
   
    public $id;
    
}
class GetRelatedRequestHandler {

    public function Serve($request){
        $related = ProductRelated::where('product_id',$request->id)->get()->toArray();
    

    	return new Response(true, $related);
    }
} 