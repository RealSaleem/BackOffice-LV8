<?php

namespace App\Requests\Backoffice\Catalogue\StockControl;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\StockControl;
use App\Models\StockControlImage;
use App\Models\LanguageTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddStockControlRequest extends BaseRequest{
   
    public $id;
    public $name;
    public $order_type;
    public $date;
    public $delivery_due;
    public $order_no;
    public $outlet;
    public $supplier;
    public $status;
    public $action;

}
class AddStockControlRequestValidator{
    public function GetRules(){
        return [
            'name' => [
                 'required',
                'string',
                'max:45',
                Rule::unique('public $name;')->where(function ($query) {
                    return $query->where([ 'store_id' => Auth::user()->store_id , 'is_deleted' => false]);
                }),                
            ],            
            //'stockcontrol_name' => 'required|string|max:45',
            //'filters' => 'required',
            //'sort_order' => 'required',
             // 'page_title' => 'required',


        ];
    }
}

class AddStockControlRequestHandler {

    public function Serve($request){

        try {
            DB::beginTransaction();

            $stockcontrol = new stockcontrol;
            $stockcontrol->name = $request->title_en;
            if (is_null($request->stockcontrol_name)) {
                $stockcontrol->stockcontrol_name = null;
            }else{
                $stockcontrol->stockcontrol_name = $request->stockcontrol_name;
            }

         
            $stockcontrol->nop_display = $request->nop_display;
            $stockcontrol->store_id = Auth::user()->store_id; 
            $stockcontrol->is_deleted = 0;

            // dd($stockcontrol);
            $stockcontrol->save();

            if(is_array($request->stockcontrol_images) && sizeof($request->stockcontrol_images) > 0){
                foreach ($request->stockcontrol_images as $image) {
                    if(isset($image['size'])){
                        $stockcontrol_image = new stockcontrolImage;
                        $stockcontrol_image->stockcontrol_id = $stockcontrol->id;
                        $stockcontrol_image->url = $image['path'];
                        $stockcontrol_image->name = $image['name'];
                        $stockcontrol_image->size = $image['size'];
                        $stockcontrol_image->created = Auth::user()->id;
                        $stockcontrol_image->updated = Auth::user()->id;
                        $stockcontrol_image->save();
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

                $language_translation->stockcontrol_id       =   $category->id;
                $language_translation->title             =   $request->$title;
                $language_translation->meta_title        =   $request->$meta_title;
                $language_translation->meta_keywords     =   $request->$meta_keywords;
                $language_translation->meta_description  =   $request->$meta_description;
                $language_translation->language_key      =   $language['short_name'];
                $language_translation->save();
            }
        
            DB::commit();
            return new Response(true, $stockcontrols);

        } catch (Exception $ex) {
            
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}