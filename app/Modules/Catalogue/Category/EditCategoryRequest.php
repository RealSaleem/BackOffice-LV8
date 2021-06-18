<?php

namespace App\Modules\Catalogue\Category;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category;
use App\Models\CategoryImage;
use App\Models\LanguageTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;

class EditCategoryRequest extends BaseRequest{

    public $id;
	public $parent_id;
	public $sort_order;
	public $pos_display;
	public $web_display;
	public $dinein_display;
    public $category_images;
    public $is_featured;
    public $display_in_menu;
    
}

class EditCategoryRequestHandler {

    public function Serve($request){

        try {
            
            DB::beginTransaction();

            $category = Category::with(['transalation','images'])->find($request->id);

            $category->name = $request->title_en;
            $category->parent_id = $request->parent_id; 
            $category->sort_order = $request->sort_order < 1 ? 1 : $request->sort_order; 
            $category->web_display = $request->web_display; 
            $category->pos_display = $request->pos_display; 
            $category->dinein_display = $request->dinein_display;
            $category->is_featured = $request->is_featured; 
            $category->display_in_menu = $request->display_in_menu; 

            $category->save();

            if(is_array($request->category_images) && sizeof($request->category_images) > 0){

                CategoryImage::where('category_id', $category->id)->delete();
                $category->image = $request->category_images['0']['path'];
                $category->save();

                foreach ($request->category_images as $image) {
                    if(isset($image['size'])){
                        $category_image = new CategoryImage;
                        $category_image->category_id = $category->id;
                        $category_image->url = $image['path'];
                        $category_image->name = $image['name'];
                        $category_image->size = $image['size'];
                        $category_image->created = Auth::user()->id;
                        $category_image->updated = Auth::user()->id;
                        $category_image->save();
                    }
                }
            } 

            
            LanguageTranslation::where(['category_id' => $category->id])->delete();
            $languages = Auth::user()->store->languages->toArray();

            foreach ($languages as $language) 
            {
                $has_seo            =    'has_seo_'.$language['short_name'];
                $title              =    'title_'.$language['short_name'];
                $meta_title         =    'meta_title_'.$language['short_name'];
                $meta_keywords      =    'meta_keywords_'.$language['short_name'];
                $meta_description   =    'meta_description_'.$language['short_name'];

                if($language['short_name'] == Language::Primary)
                {
                    $validator =   Validator::make($request->all(), [
                        $title => 'required|string',
                        // $meta_description => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        return new Response(false,null,[],$validator);
                    }

                }

                $language_translation = new LanguageTranslation;

                $language_translation->category_id       =   $category->id;
                $language_translation->title             =   $request->$title;
                $language_translation->meta_title        =   $request->$meta_title;
                $language_translation->meta_keywords     =   $request->$meta_keywords;
                $language_translation->meta_description  =   $request->$meta_description;
                $language_translation->language_key      =   $language['short_name'];
                $language_translation->save();
            }
  		
            DB::commit();

        	return new Response(true, $category);

        } catch (Exception $ex) {
            
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
