<?php

namespace App\Modules\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use App\Models\AddOnItems;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\LanguageTranslation;
use App\Models\ProductVariant;

class UpdateAddOnRequest extends BaseRequest{
    
    public $store_id;
    public $name;
    public $type;
    public $is_active;
    public $min;
    public $max;
    public $id;


}

class UpdateAddOnRequestValidator{
    public function GetRules(){
        return [
            // 'description' => 'string|max:45',
            //  'name' => [
            //     'required',
            //     'string',
            //     'min:3',
            //     Rule::unique('add_on')->where(function ($query) {
            //         return $query->where('store_id', Auth::user()->store_id);
            //     }),                
            // ],
        ];
    }
} 


class UpdateAddOnRequestHandler {

    public function __construct(){

    }

    public function Serve($request){
        try {

            
            DB::beginTransaction();

            $params = $request->all();
            //dd($params);

            $addons = AddOn::with('items')->where(['identifier' => $request->id])->get()->toArray();

            $languages = Auth::User()->store->languages->toArray();

            $prices = [];

            $counter = 0;

            $products = [];

            foreach ($languages as $language){
                
                $addon = array_filter($addons,function($item) use($language){
                    if($item['language_key'] == $language['short_name']){
                        return $item;
                    }
                });

                $name = sprintf('%s_name',$language['short_name']);
                $iitems = sprintf('%s_items',$language['short_name']);

                $products[$language['short_name']] = array_column($params[$language['short_name'].'_items'],'name');

                $addon = array_values($addon);

                if(sizeof($addon) > 0){

                    $item = $addon[0];

                    $data = [
                        'name' => $params[$name],
                        'type' => $request->type,
                        'min' => $request->min,
                        'max' => $request->max,
                    ];

                    if(isset($request->is_active)){
                        $data['is_active'] = true;
                    }else{
                        $data['is_active'] = false;
                    }

                    $where = ['identifier' => $request->id , 'language_key' => $language['short_name']];


                    $modified_addon = AddOn::where($where)->first();

                    $modified_addon->update($data);

                    $modified_addon->items()->delete();

                    $items = $params[$iitems];

                    $addon_items = [];
                    $price = 0;

                    foreach ($items as $add_on_item) {

                        if(isset($add_on_item['price'])){
                            $price = $add_on_item['price'];
                            array_push($prices,$add_on_item['price']);
                        }else{
                            $price = $prices[$counter];
                        }

                        $item_data = [
                            'name' => $add_on_item['name'], 
                            'language_key' => $language['short_name'],
                            'add_on_id' => $modified_addon->id,
                            'price' => $price
                        ];

                        AddOnItems::create($item_data);
                        $counter++;
                    }

                    $counter = 0;
                }
            }

            $this->checkOrCreate($products,$params);

            DB::commit();

            return new Response(true);

        } catch (Exception $ex) {
            
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }

    public function checkOrCreate($products,$params){
        $items = $products['en'];

        $keys = array_keys($products);

        $index = 0;

        foreach ($items as $item){
            $p = Product::where(['name' => $item])->first();

            if(is_null($p)){
                $product = new Product;
                $product->store_id              = Auth::user()->store_id;
                $product->category_id           = 0;
                $product->active                = 0;    
                $product->is_featured           = 0;   
                $product->created               = Auth::user()->id;   
                $product->dinein_display        = 0;
                $product->name = $product->handle = $product->prefix = $product->attribute_1 = $product->attribute_2 = $product->attribute_3 = $item;

                $product->is_featured_web       = 0;   
                $product->top_seller_web        = 0;    
                $product->web_display           = 0;
                $product->is_composite          = 0;
                $product->has_variant           = 0;
                $product->unit                  = 'Number';
                $product->is_item               = 1;
                $product->save();

                $variant = new ProductVariant;
                $variant->product_id                = $product->id;
                $variant->name                      = $item;
                $variant->supplier_price            = 0;
                $variant->sku                       = '';
                $variant->store_id                  = Auth::user()->store_id;
                $variant->attribute_value_1         = $item;
                $variant->markup                    = 0;
                $variant->retail_price              = $params['en_items'][$index]['price'];
                $variant->allow_out_of_stock        = 1;
                $variant->image                     = ''; 
                $variant->is_active                 = 1; 

                $variant->save();  

                foreach ($keys as $key){
                    $translation = new LanguageTranslation;
                    $translation->product_id         = $product->id;
                    $translation->title              = $products[$key][$index];
                    $translation->language_key       = $key;
                    $translation->save();                
                }     
            }

            $index++;
        }
    }    
} 