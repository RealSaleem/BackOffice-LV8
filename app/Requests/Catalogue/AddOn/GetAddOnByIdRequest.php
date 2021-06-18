<?php

namespace App\Requests\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn as AddOn;
use App\Models\LanguageTranslation;
use App\Models\Product;
use Auth;
class GetAddOnByIdRequest extends BaseRequest{

    public $id;
    public $languages;

}
class GetAddOnByIdRequestHandler {

    public function Serve($request){
       $add_ons = AddOn::with(['items' => function($q){
        	$q->orderBy('id');
        }])->where(['identifier' => $request->id])->orderBy('id')->get()->toArray();
    	return new Response(true, $add_ons);
    }
}
