<?php

namespace App\Modules\Catalogue\Category;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category as Category;
use Auth;
class GetCategoryByIdRequest extends BaseRequest{
   
    public $id;
    
}
class GetCategoryByIdRequestHandler {

    public function Serve($request){
        $category = Category::with(['transalation','images'])->find($request->id);

        $category_template = [
			'id' => $category->id,
			"parent_id" => $category->parent_id,
			"sort_order" => $category->sort_order,
			"pos_display" => $category->pos_display,
			"web_display" => $category->web_display,
			"dinein_display" => $category->dinein_display,
            "is_featured"    => $category->is_featured,
            "display_in_menu"    => $category->display_in_menu,
            "category_images" => $category->images->toArray()
        ];

        $transalations = $category->transalation->toArray();
        $ltransalations = Auth::user()->store->languages->toArray();

        if(sizeof($transalations) == sizeof($ltransalations)){

            foreach ($transalations as $transalation) 
            {
                $has_seo = 'has_seo_'.$transalation['language_key'];
                $title   = 'title_'.$transalation['language_key'];
                $meta_title = 'meta_title_'.$transalation['language_key'];
                $meta_keywords = 'meta_keywords_'.$transalation['language_key'];
                $meta_description = 'meta_description_'.$transalation['language_key'];

                $category_template[$title] = $transalation['title'];
                $category_template[$meta_title] = $transalation['meta_title'];
                $category_template[$meta_keywords] = $transalation['meta_keywords'];
                $category_template[$meta_description] = $transalation['meta_description'];

                $category_template[$has_seo] = $transalation['meta_title'] != '' ? 1 : 0;;
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

                if(sizeof($trans) > 0){
                    $row = $trans[0];
                    
                    $category_template[$title] = $row['title'];
                    $category_template[$meta_title] = $row['meta_title'];
                    $category_template[$meta_keywords] = $row['meta_keywords'];
                    $category_template[$meta_description] = $row['meta_description'];

                }else{

                    $category_template[$title] = $category->name;
                    $category_template[$meta_title] = '';
                    $category_template[$meta_keywords] = '';
                    $category_template[$meta_description] = '';
                }

                $category_template[$has_seo] = $meta_title != '' ? 1 : 0;
            }                
        }

    	return new Response(true, $category_template);
    }
} 