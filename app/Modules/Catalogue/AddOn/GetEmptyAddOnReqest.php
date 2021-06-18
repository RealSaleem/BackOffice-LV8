<?php

namespace App\Modules\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use Auth;

class GetEmptyAddOnReqest extends BaseRequest{
   
}

class GetEmptyAddOnReqestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	$languages = Auth::User()->store->languages->toArray();

    	$brand = [
              'id' => 0,
        ];    
        foreach ($languages as $language) {
        	$brand['has_seo_'.$language['short_name']]=0;
        	$brand['title_'.$language['short_name']]='';
            $brand['description_'.$language['short_name']]='';
        	$brand['meta_title_'.$language['short_name']]='';
        	$brand['meta_keywords_'.$language['short_name']]='';
        	$brand['meta_description_'.$language['short_name']]='';
         } 
        return new Response(true, $brand);
    }
} 