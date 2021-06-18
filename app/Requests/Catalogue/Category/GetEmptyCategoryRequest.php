<?php

namespace App\Requests\Catalogue\Category;
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
			"pos_display" => 0,
			"web_display" => 0,
			"dinein_display" => 0,
			"is_featured"   => 0,
			"active"  => '',
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
		// dd($category);

    	return new Response(true, $category);
    }
} 