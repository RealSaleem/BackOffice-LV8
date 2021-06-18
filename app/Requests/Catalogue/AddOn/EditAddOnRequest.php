<?php

namespace App\Requests\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn as AddOn;
use App\Models\AddOnTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;

class EditAddOnRequest extends BaseRequest{
   
    public $store_id;
    public $name;
    public $type;
    public $is_active;
}

class EditAddOnRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	 try {
            // dd($request->type);
            DB::beginTransaction();

            $add_on = AddOn::with('transalation')->find($request->id);

            $add_on->name = $request->name_en;
            $add_on->type = $request->type; 
            $add_on->is_active = $request->is_active;
            $add_on->save();

            AddOnTranslation::where(['add_on_id' => $add_on->id])->delete();
            $languages = Auth::user()->store->languages->toArray();

            foreach ($languages as $language) 
            {
                $name = 'name_'.$language['short_name'];

                if($language['short_name'] == Language::Primary)
                {
                    $validator = Validator::make($request->all(), [
                        $name => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        return new Response(false,null,[],$validator);
                    }

                }

                $language_translation = new AddOnTranslation;

                $language_translation->add_on_id = $add_on->id;
                $language_translation->name = $request->$name;
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