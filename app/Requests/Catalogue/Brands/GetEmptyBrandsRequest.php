<?php

namespace App\Requests\Catalogue\Brands;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptyBrandsRequest extends BaseRequest
{
}

class GetEmptyBrandsRequestHandler {

    public function Serve($request){
    	$languages = Auth::user()->store->languages->toArray();

        $brands = [
			'id' => 0,
			"name" => 0,
			"nop_display" => 1,
			"is_featured"   => 0,
			"active"   => 0,
            "brands_images" => []
        ];

        foreach ($languages as $language)
        {
        	$brands['has_seo_'.$language['short_name']] = 0;
        	$brands['title_'.$language['short_name']] = '';
        	$brands['meta_title_'.$language['short_name']] = '';
        	$brands['meta_keywords_'.$language['short_name']] = '';
        	$brands['meta_description_'.$language['short_name']] = '';
        }

    	return new Response(true, $brands);
    }
}
