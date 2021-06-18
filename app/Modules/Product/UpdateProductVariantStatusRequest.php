<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\ProductVariant;


class UpdateProductVariantStatusRequest extends BaseRequest{
    public $id;
    public $is_active;
}

class UpdateProductVariantStatusRequestValidator {

	public function GetRules(){
        return [
            'id' => 'required|integer',
			'is_active' => 'required|boolean'
        ];
    }


    public function IsValid($request){
        return new Response(true);
    }
}

class UpdateProductVariantStatusRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $productVariant = ProductVariant::find($request->id);
        $productVariant->is_active = $request->is_active;
        $productVariant->save();

        return new Response(true, $productVariant);
    }
} 