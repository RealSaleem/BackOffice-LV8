<?php

namespace App\Modules\Catalogue\Brand;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Brand as Brand;
use App\Models\LanguageTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;

class EditBrandRequest extends BaseRequest{
   
    public $store_id;
    public $is_featured;
}

class EditBrandRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	 try {
            
            DB::beginTransaction();

            $brand = Brand::with('transalation')->find($request->id);

            $brand->name = $request->title_en;
            $brand->is_featured = $request->is_featured; 
            $brand->save();

            LanguageTranslation::where(['brand_id' => $brand->id])->delete();
            $languages = Auth::user()->store->languages->toArray();

            foreach ($languages as $language) 
            {
                $has_seo = 'has_seo_'.$language['short_name'];
                $title   = 'title_'.$language['short_name'];
                $description   = 'description_'.$language['short_name'];
                $meta_title = 'meta_title_'.$language['short_name'];
                $meta_keywords = 'meta_keywords_'.$language['short_name'];
                $meta_description = 'meta_description_'.$language['short_name'];

                if($language['short_name'] == Language::Primary)
                {
                    $validator = Validator::make($request->all(), [
                        $title => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        return new Response(false,null,[],$validator);
                    }

                }

                $language_translation = new LanguageTranslation;

                $language_translation->brand_id = $brand->id;
                $language_translation->title = $request->$title;
                $language_translation->description = $request->$description;
                $language_translation->meta_title = $request->$meta_title;
                $language_translation->meta_keywords = $request->$meta_keywords;
                $language_translation->meta_description = $request->$meta_description;
                $language_translation->language_key = $language['short_name'];
                $language_translation->save();
            }
        
            DB::commit();

            return new Response(true);

        } catch (Exception $ex) {
            
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    	return new Response(true, $transalation);
    }
} 