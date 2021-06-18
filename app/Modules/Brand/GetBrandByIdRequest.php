<?php

namespace App\Modules\Catalogue\Brand;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand as Brand;
use App\Models\LanguageTranslation;
use Auth;
class GetBrandByIdRequest extends BaseRequest{
   
    public $id;
    public $languages;
    
}
class GetBrandByIdRequestHandler {

    public function Serve($request){
        $brand = Brand::with('transalation')->find($request->id);
        
        $brand_template = [
			'id' => $brand->id,
            'title_en'=>$brand->name,
            'is_featured'=>$brand->is_featured,
        ];

        $transalations  = $brand->transalation->toArray();
        $ltransalations = Auth::user()->store->languages->toArray();

        if(sizeof($transalations) == sizeof($ltransalations)){
                
            foreach ($transalations as $transalation) 
            {
                $has_seo = 'has_seo_'.$transalation['language_key'];
                $title   = 'title_'.$transalation['language_key'];
                $description   = 'description_'.$transalation['language_key'];
                $meta_title = 'meta_title_'.$transalation['language_key'];
                $meta_keywords = 'meta_keywords_'.$transalation['language_key'];
                $meta_description = 'meta_description_'.$transalation['language_key'];

                $brand_template[$title] = $transalation['title'];
                $brand_template[$description] = $transalation['description'];
                $brand_template[$meta_title] = $transalation['meta_title'];
                $brand_template[$meta_keywords] = $transalation['meta_keywords'];
                $brand_template[$meta_description] = $transalation['meta_description'];

                $brand_template[$has_seo] = $transalation['meta_title'] != '' ? 1 : 0;;
            }
            
        }else{
            foreach ($ltransalations as $transalation) 
            {
                $has_seo = 'has_seo_'.$transalation['short_name'];
                $title   = 'title_'.$transalation['short_name'];
                $description = 'description_'.$transalation['short_name'];
                $meta_title = 'meta_title_'.$transalation['short_name'];
                $meta_keywords = 'meta_keywords_'.$transalation['short_name'];
                $meta_description = 'meta_description_'.$transalation['short_name'];

                $trans = array_filter($transalations,function($item) use($transalation){
                    if($item['language_key'] == $transalation['short_name']){
                        return $item;
                    }
                });

                if(sizeof($trans) > 0){
                    $row = $trans[0];
                    
                    $brand_template[$title] = $row['title'];
                    $brand_template[$description] = $row['description'];
                    $brand_template[$meta_title] = $row['meta_title'];
                    $brand_template[$meta_keywords] = $row['meta_keywords'];
                    $brand_template[$meta_description] = $row['meta_description'];

                }else{
                    $brand_template[$title] = $brand->name;
                    $brand_template[$description] = '';
                    $brand_template[$meta_title] = '';
                    $brand_template[$meta_keywords] = '';
                    $brand_template[$meta_description] = '';
                }

                $brand_template[$has_seo] = $meta_title != '' ? 1 : 0;
            }                 
        }

        //dd($brand_template);

    	return new Response(true, $brand_template);
    }
} 