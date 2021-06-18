<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductSuppliers as ProductSuppliers;
use Auth;
class GetSuppliersRequest extends BaseRequest{
   
    public $id;
    
}
class GetSuppliersRequestHandler {

    public function Serve($request){
        $suppliers = ProductSuppliers::where('product_id',$request->id)->get()->toArray();
    

    	return new Response(true, $suppliers);
    }
} 