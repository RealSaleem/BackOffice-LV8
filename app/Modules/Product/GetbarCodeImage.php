<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Milon\Barcode\DNS1D;
use Auth;

class GetbarCodeImage extends BaseRequest{
    
    public $id;
}

class GetbarCodeImageValidatosr {
    public function GetRules(){
		return [
		   'id' => 'required|numeric',
	   ];
    }
}

class GetbarCodeImageHandler {

    public function Serve($request){

       $barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG( $request->id, "UPCE");
    	
		return new Response(true, $barcode);
			
    }
} 