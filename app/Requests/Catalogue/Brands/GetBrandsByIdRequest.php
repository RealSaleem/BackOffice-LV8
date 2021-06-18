<?php

namespace App\Requests\Catalogue\Brands;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand as Brands;
use Auth;

class GetBrandsByIdRequest extends BaseRequest{
   
    public $id;
    
}
class GetBrandsByIdRequestHandler {

    public function Serve($request){
        $brands = Brands::with(['transalation','images'])->find($request->id);

        $brands_template = [
			'id' => $brands->id,
			"name" => $brands->name,
			"nop_display" => $brands->nop_display,
            "is_featured"    => $brands->is_featured,
            "active"    => $brands->active,
            "brands_images" => $brands->images->toArray()
        ];

        $transalations = $brands->transalation->toArray();
        $ltransalations = Auth::user()->store->languages->toArray();

        if(sizeof($transalations) == sizeof($ltransalations)){

            foreach ($transalations as $transalation) 
            {
                $has_seo = 'has_seo_'.$transalation['language_key'];
                $title   = 'title_'.$transalation['language_key'];
                $meta_title = 'meta_title_'.$transalation['language_key'];
                $meta_keywords = 'meta_keywords_'.$transalation['language_key'];
                $meta_description = 'meta_description_'.$transalation['language_key'];

                $brands_template[$title] = $transalation['title'];
                $brands_template[$meta_title] = $transalation['meta_title'];
                $brands_template[$meta_keywords] = $transalation['meta_keywords'];
                $brands_template[$meta_description] = $transalation['meta_description'];

                $brands_template[$has_seo] = $transalation['meta_title'] != '' ? 1 : 0;;
            }

        }else{
            
            foreach ($ltransalations as $transalation) 
            {
                $has_seo = 'has_seo_'.$transalation['short_name'];
                $title   = 'title_'.$transalation['short_name'];
                $meta_title = 'meta_title_'.$transalation['short_name'];
                $meta_keywords = 'meta_keywords_'.$transalation['short_name'];
                $meta_description = 'meta_description_'.$transalation['short_name'];

                $trans = array_filter($transalations,function($item) use($transalation){
                    if($item['language_key'] == $transalation['short_name']){
                        return $item;
                    }
                });

                if(count($trans) > 0 && isset($trans[0])){
                    $row = $trans[0];
                    
                    $brands_template[$title] = $row['title'];
                    $brands_template[$meta_title] = $row['meta_title'];
                    $brands_template[$meta_keywords] = $row['meta_keywords'];
                    $brands_template[$meta_description] = $row['meta_description'];

                }else{

                    $brands_template[$title] = $brands->name;
                    $brands_template[$meta_title] = '';
                    $brands_template[$meta_keywords] = '';
                    $brands_template[$meta_description] = '';
                }

                $brands_template[$has_seo] = $meta_title != '' ? 1 : 0;
            }                
        }

    	return new Response(true, $brands_template);
    }
} 