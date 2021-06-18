<?php

namespace App\Requests\Catalogue\Category;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category;
use App\Models\CategoryImage;
use App\Models\LanguageTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddCategoryRequest extends BaseRequest{

    public $id;
    public $parent_id;
    public $sort_order;
    public $pos_display;
    public $web_display;
    public $dinein_display;
    public $is_featured;
    public $category_images;

}
class AddCategoryRequestValidator{
    public function GetRules(){
        return [
            'name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where([ 'store_id' => Auth::user()->store_id , 'is_deleted' => false]);
                }),
            ],
            //'parent_id' => 'required|string|max:45',
            //'filters' => 'required',
            //'sort_order' => 'required',
            // 'page_title' => 'required',
        ];
    }
}

class AddCategoryRequestHandler {

    public function Serve($request){

        try {
            DB::beginTransaction();

            $category = new Category;
            $category->name = $request->title_en;
            if (is_null($request->parent_id)) {
                $category->parent_id = null;
            }else{
                $category->parent_id = $request->parent_id;
            }

            $category->filters = '';
            $category->sort_order = $request->sort_order;
            $category->web_display = (bool)$request->web_display;
            $category->pos_display = (bool)$request->pos_display;
            $category->dinein_display =(bool)$request->dinein_display;
            $category->is_featured = (bool)$request->is_featured;
            $category->active = ($category->web_display || $category->pos_display || $category->dinein_display);
            $category->store_id = Auth::user()->store_id;
            $category->is_deleted = 0;
            $category->save();

            if(is_array($request->category_images) && count($request->category_images) > 0){
                foreach ($request->category_images as $image) {
                    if(isset($image['size'])){
                        $this->addCategoryImage($category, $image);
                    }
                }
            }

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
            return new Response(true, $category,null,null,\Lang::get('toaster.category_added'));


        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }

    /**
     * @param Category $category
     * @param $image
     */
    public function addCategoryImage(Category $category, $image): void
    {
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
