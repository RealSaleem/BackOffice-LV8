<?php

namespace App\Requests\Catalogue\Brands;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand as BrandsModel;
use App\Models\BrandImage;
use App\Models\LanguageTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddBrandsRequest extends BaseRequest{

    public $id;
    public $brand_name;
    public $title_en;
    public $nop_display;
    public $is_featured;
    public $active;
    public $images;

}
class AddBrandsRequestValidator{
    public function GetRules(){
        return [
            'name' => [
                'required',
                'string',
                'max:45',
                Rule::unique('brands')->where(function ($query) {
                    return $query->where([ 'store_id' => Auth::user()->store_id , 'is_deleted' => false]);
                }),
            ],
            //'brand_name' => 'required|string|max:45',
            //'filters' => 'required',
            //'sort_order' => 'required',
             // 'page_title' => 'required',


        ];
    }
}

class AddBrandsRequestHandler {

    public function Serve($request){
        try {
            DB::beginTransaction();
            $brands = new BrandsModel;
            $brands->name = $request->title_en;
            $brands->is_featured = (bool)$request->is_featured;
            $brands->active = (bool)$request->active;

            $brands->store_id = Auth::user()->store_id;
            $brands->is_deleted = 0;
            $brands->save();
            if(is_array($request->images) && sizeof($request->images) > 0){
                foreach ($request->images as $image) {
                    $brands->image_url = $image['path'];
                    $brands->save();
                    if(isset($image['size'])){
                        $this->UpdateBrandImage($brands, $image);
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

                // if($language['short_name'] == Language::Primary)
                // {
                //     $validator =   Validator::make($request->all(), [
                //         $title => 'required|string',
                //         // $meta_description => 'required|string',
                //     ]);

                //     if ($validator->fails()) {
                //         return new Response(false,null,[],$validator);
                //     }

                // }

                $language_translation = new LanguageTranslation;

                $language_translation->brand_id       =   $brands->id;
                $language_translation->title             =   $request->$title;
                $language_translation->meta_title        =   $request->$meta_title;
                $language_translation->meta_keywords     =   $request->$meta_keywords;
                $language_translation->meta_description  =   $request->$meta_description;
                $language_translation->language_key      =   $language['short_name'];
                $language_translation->save();
            }

            DB::commit();
            return new Response(true, $brands, null, null, \Lang::get('toaster.brand_added'));

        } catch (\Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage(), null);
        }
    }

    /**
     * @param BrandsModel $brands
     * @param $image
     */
    public function UpdateBrandImage(BrandsModel $brands, $image): void
    {
        $brands_image = new BrandImage;
        $brands_image->brand_id = $brands->id;
        $brands_image->url = $image['path'];
        $brands_image->name = $image['name'];
        $brands_image->size = $image['size'];
        $brands_image->created = Auth::user()->id;
        $brands_image->updated = Auth::user()->id;
        $brands_image->save();
    }
}
