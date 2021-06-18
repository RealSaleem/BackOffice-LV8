<?php

namespace App\Modules\Supplier;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Supplier;
use Auth;

class GetAllSupplierRequest extends BaseRequest{

}

class GetAllSupplierRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	$login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        $Suppliers = Supplier::where('store_id',$login_user->store_id)->withCount('products')->orderBy('id','DESC')->get();

    	return new Response(true, $Suppliers);
    }
}
