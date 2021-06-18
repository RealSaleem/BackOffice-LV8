<?php

namespace App\Modules\Catalogue\Category;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use Auth;

class GetEmptyCategoryRequest extends BaseRequest
{
}

class GetEmptyCategoryRequestHandler {

    public function Serve($request){
    	$languages = Auth::user()->store->languages->toArray();

        $category = [
			'id' => 0,
			"parent_id" => 0,
			"sort_order" => 1,
			"pos_display" => 1,
			"web_display" => 1,
			"dinein_display" => 1,
            "is_featured"   => 0,
            "display_in_menu"   => 0,
            "category_images" => []
        ];

        foreach ($languages as $language) 
        {
        	$category['has_seo_'.$language['short_name']] = 0;
        	$category['title_'.$language['short_name']] = '';
        	$category['meta_title_'.$language['short_name']] = '';
        	$category['meta_keywords_'.$language['short_name']] = '';
        	$category['meta_description_'.$language['short_name']] = '';
        }

    	return new Response(true, $category);
    }
} 