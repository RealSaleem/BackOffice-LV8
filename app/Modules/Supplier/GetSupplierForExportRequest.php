<?php

namespace App\Modules\Supplier;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier;
use Auth;

class GetSupplierForExportRequest extends BaseRequest{
    
}

class GetSupplierForExportRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $Suppliers = Supplier::where('store_id',Auth::user()->store_id)->withCount('products')->orderBy('id','DESC')->get();
    	return new Response(true, $Suppliers);
    }
} 