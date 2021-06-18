<?php

namespace App\Requests\Backoffice\Catalogue\Brands;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brands;
use App\Models\BrandsImage;
use App\Models\LanguageTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;

class EditBrandsRequest extends BaseRequest{

    public $id;
	public $name;
	public $nop_display;
    public $is_featured;
}

class EditBrandsRequestHandler {

    public function Serve($request){

        try {
            
            DB::beginTransaction();

            $Brands = Brands::with(['transalation','images'])->find($request->id);

            $brands->name = $request->title_en;
            $brands->pos_display = $request->pos_display; 
            $brands->is_featured = $request->is_featured;  

            $Brands->save();

            if(is_array($request->brands_images) && sizeof($request->brands_images) > 0){

                BrandsImage::where('brand_id', $Brands->id)->delete();
                $brands->image = $request->brands_images['0']['path'];
                $brands->save();

                foreach ($request->brands_images as $image) {
                    if(isset($image['size'])){
                        $brands_image = new brandsImage;
                        $brands_image->brands_id = $brands->id;
                        $brands_image->url = $image['path'];
                        $brands_image->name = $image['name'];
                        $brands_image->size = $image['size'];
                        $brands_image->created = Auth::user()->id;
                        $brands_image->updated = Auth::user()->id;
                        $brands_image->save();
                    }
                }
            } 

            
            LanguageTranslation::where(['brand_id' => $Brands->id])->delete();
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

        	return new Response(true, $brands);

        } catch (Exception $ex) {
            
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
