<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCatgories;
use App\Models\LanguageTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Store;
use App\Models\ProductVariant;
use App\Models\CompositeProduct;
use App\Models\ProductStock;
use App\Models\Outlet;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariantValue;
use App\Helpers\OrderType as OrderType;

use Session;
use App\Helpers\VariantStock;

class AddVarientsRequest extends BaseRequest{

    public $id;
	public $category_id;          
    public $supplier_id;             
    public $related_id;              
    public $branches;                
    public $brand_id;                
    public $active;                  
    public $name;                    
    public $handle;                  
    public $description;             
    public $product_images;          
    public $attribute_1;             
    public $attribute_2;             
    public $attribute_3;             
    public $supplier_price;          
    public $markup;                  
    public $retail_price;            
    public $sku;                     
    public $supplier_code;           
    public $sales_account_code;      
    public $purchase_account_code;   
    public $tags;                    
    public $has_variant;             
    public $variants;                
    public $is_featured;             
    public $prefix;                  
    public $is_composite;            
    public $composite_products;      
    public $sku_string;              
    public $created;                 
    public $barcode;                 
    public $dinein_display;          
    public $meta_title;              
    public $meta_keywords;           
    public $meta_description;        
    public $before_discount_price;   
    public $discounted_price;        
    public $is_featured_web;         
    public $top_seller_web;          
    public $web_display;             
    public $sku_custom;              
    public $sku_name;                
    public $sku_number;              
    public $default_sku_name;
    public $sku_type;
    public $allow_out_of_stock;
    public $is_combo;   
    public $languages;           
}

class AddVarientsRequestHandler {
    public function __construct()
    {
        $this->store = Store::with('product_variants')->find(Auth::user()->store_id);
        $this->sku_name_counter = 0;
        $this->sku_number_counter = $this->store->current_sequence_number;
    }

    public function Serve($request){

        try {
            DB::beginTransaction();
            $product     = Product::with([
                'product_stock',
                'product_verients',
            ])->find($request->id);


            $product->attribute_1 = $request->attribute_1;
            $product->attribute_2 = $request->attribute_2;
            $product->attribute_3 = $request->attribute_3;

            $product->save();

            if (sizeof($request->variants) > 0) {

                $languages = json_decode(json_encode($request->languages));
                $err = $this->AddProductVariants($product,$request);

                $this->addTranslations($product->id,$languages);
                $this->addAttrValue($product,$languages);
                 
                if($err == false){
                    return new Response(false,null,null,['SKU already exists']);
                }
                
                $vari = ProductVariant::where('product_id',$product->id )->get();
                foreach ($vari as $variu) {

                   //Variant stock Update i.e sum of stock of all outlet in stock field in varant table
                    VariantStock::updateStock($variu->id);
                }
                DB::commit();
                $product->Message ='Variant has been added successfully';
            	return new Response(true,$product);
            }else{
                return new Response(false,null,null,['No variants provided']);

            }
           

        } catch (Exception $ex) {
            
            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
    public function addAttrValue($product, $translations)
    {
        // $prod  = Product::with(['product_attributes','product_attributes.values','product_verients'])->find($product->id);

        // foreach($prod->product_verients as $vari){
        //     $att_v  = ProductVariantValue::where('variant_id',$vari->id)->delete();
        // }
        $translations = Auth::user()->store->languages->toArray();
        $rows = [];               

        if(is_array($translations) && sizeof($translations) > 0){

            $attr = 1;
            $attribute_1_default = [];
            $attribute_2_default = [];
            $attribute_3_default = [];
            $is_default_set = false;

            foreach ($translations as $key => $trans) {
                $trans['attribute_1']               = '';
                $trans['attribute_2']               = '';
                $trans['attribute_3']               = '';
                $trans['attr1_values']              = [];
                $trans['attr2_values']              = [];
                $trans['attr3_values']              = [];

                $attributes = ProductAttribute::with('values')->where([
                    ['product_id',$product->id],
                    ['language_key',$trans['short_name']]
                ])->orderBy('id')->get()->toArray();

                if(!$is_default_set){
                   $attribute_1_default = isset($attributes[0]['values']) ? array_column($attributes[0]['values'],'name') : [];
                   $attribute_2_default = isset($attributes[1]['values']) ? array_column($attributes[1]['values'],'name') : [];
                   $attribute_3_default = isset($attributes[2]['values']) ? array_column($attributes[2]['values'],'name') : [];
                   $is_default_set = true;
                }

                $index = 1;
                $name = $trans['short_name'];
                $trans = [];
                $trans['short_name'] = $name ;
                foreach ($attributes as $attribute) {
                    $trans['attribute_'.$attr]           = $attribute['name'];
                    $attribute_values = $attribute['values'];
                    $values = array_column($attribute_values,'id','name');
                    $group = [];
                    if($index == 1){
                        $trans['attr1_values']= $values;
                    }else if($index == 2){
                        $trans['attr2_values'] = $values;
                    }else{
                        $trans['attr3_values'] = $values;
                    }
                    $index++;
                    $attr++;
                }
                $attr = 1;
                array_push($rows, $trans);
            }
        }
        $pro = Product::with('product_verients')->find($product->id);

        foreach($rows as $key => $row){
            foreach ($pro->product_verients as $var) {
                if(isset($row['attr1_values']) && $row['attr1_values'] != null){
                    foreach($row['attr1_values'] as $keyval => $item){
                        $Varient_value = new ProductVariantValue;
                        $Varient_value->language_key                    = $row['short_name'];
                        $Varient_value->variant_id                      = $var->id;
                        $Varient_value->product_attribute_value_id      = $item;
                        $Varient_value->save();
                    }
                }
                if(isset($row['attr2_values']) && $row['attr2_values'] != null){
                    foreach($row['attr2_values'] as $keyval => $item){
                        $Varient_value = new ProductVariantValue;
                        $Varient_value->language_key                    = $row['short_name'];
                        $Varient_value->variant_id                      = $var->id;
                        $Varient_value->product_attribute_value_id      = $item;
                        $Varient_value->save();
                    }
                }
                if(isset($row['attr3_values']) && $row['attr3_values'] != null){
                    foreach($row['attr3_values'] as $keyval => $item){
                        $Varient_value = new ProductVariantValue;
                        $Varient_value->language_key                    = $row['short_name'];
                        $Varient_value->variant_id                      = $var->id;
                        $Varient_value->product_attribute_value_id      = $item;
                        $Varient_value->save();
                    }
                }
            }
        }
    }
   
    public function addTranslations($product_id, $translations)
    {
        if(is_array($translations) && sizeof($translations) > 0){

            foreach ($translations as $key => $trans) {
                
                if(isset($trans->attribute_1) && (is_array($trans->attr1_values) && sizeof($trans->attr1_values) > 0)){

                    $attribute = new ProductAttribute;
                    
                    $attribute->language_key = $trans->short_name;
                    $attribute->name = $trans->attribute_1;
                    $attribute->product_id = $product_id;
                    $attribute->save();

                    //$product->attribute_1 = $trans->attribute_1;

                    foreach($trans->attr1_values as $row){
                        $value = new ProductAttributeValue;
                        $value->product_attribute_id = $attribute->id;
                        $value->name = $row->value;
                        $value->save();
                    }
                }

                if(isset($trans->attribute_2) && (is_array($trans->attr2_values) && sizeof($trans->attr2_values) > 0)){

                    $attribute = new ProductAttribute;
                    
                    $attribute->language_key = $trans->short_name;
                    $attribute->name = $trans->attribute_2;
                    $attribute->product_id = $product_id;
                    $attribute->save();

                    //$product->attribute_2 = $trans->attribute_2;

                    foreach($trans->attr2_values as $row){
                        $value = new ProductAttributeValue;
                        $value->product_attribute_id = $attribute->id;
                        $value->name = $row->value;
                        $value->save();
                    }
                }

                if(isset($trans->attribute_3) && (is_array($trans->attr3_values) && sizeof($trans->attr3_values) > 0)){

                    $attribute = new ProductAttribute;
                    
                    $attribute->language_key = $trans->short_name;
                    $attribute->name = $trans->attribute_3;
                    $attribute->product_id = $product_id;
                    $attribute->save();

                    //$product->attribute_3 = $trans->attribute_3;

                    foreach($trans->attr3_values as $row){
                        $value = new ProductAttributeValue;
                        $value->product_attribute_id = $attribute->id;
                        $value->name = $row->value;
                        $value->save();
                    }
                }

                //$product->save();
            }
        }
    }

    private function verifyVariant($sku)
    {

        $variants = $this->store->product_variants->toArray();

        $skus = array_column($variants, 'sku');

        if(in_array($sku,$skus)){
            return false;
        }

        return true;
    }

    private function getSku($request,$index)
    {
        if($request->sku_custom){
            if($request->has_variant){
                return $request->variants[$index]['sku'];
            }else{

                if ($request->sku_type == 0) {
                    $request->sku  = $request->sku_custom;
                    return $request->sku;
                }else{
                     return $request->sku;
                }

            }

        }else{
            return $this->_getSku($request);
        }
    }

    private function _getSku($request)
    {
        if($request->sku_name){
            return $this->getSkuByName($request);
        }else{
            return $this->getSkuByNumber();
        }
    }  

    private function getSkuByName($request)
    {
        $this->sku_name_counter = $this->sku_name_counter + 1;
        $sku = sprintf('%s-%d',$request->default_sku_name,$this->sku_name_counter);
        return $sku;
    }

    private function getSkuByNumber()
    {
        $sku = $this->sku_number_counter;
        $this->sku_number_counter ++;
        return $sku;
    } 

    private function updateSequenceNumber()
    {
        if($this->store->current_sequence_number != $this->sku_number_counter){
            $this->store->current_sequence_number = $this->sku_number_counter;
            $this->store->save();
        }
    }
    private function addLanguageTranslations($product,$request)
    {
        $languages = Auth::user()->store->languages->toArray();
            foreach ($languages as $language) 
            {
                $has_seo          = 'has_seo_'.$language['short_name'];
                $title            = 'title_'.$language['short_name'];
                $description      = 'description_'.$language['short_name'];
                $meta_title       = 'meta_title_'.$language['short_name'];
                $meta_keywords    = 'meta_keywords_'.$language['short_name'];
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

                $language_translation->product_id         = $product->id;
                $language_translation->title              = $request->$title;
                $language_translation->description        = $request->$description;
                $language_translation->meta_title         = $request->$meta_title;
                $language_translation->meta_keywords      = $request->$meta_keywords;
                $language_translation->meta_description   = $request->$meta_description;
                $language_translation->language_key       = $language['short_name'];

                $language_translation->save();
            }
    } 
    
    private function  AddProductVariants($product,$request)
    {
         $index = 0;
            // $variants = $this->mapVariants($request->variants, $product->id,$request);

            foreach ($request->variants as $variant) {

        // // dd($variant['retail_price']);
        // dd($variant['before_discount_price']);
        // dd($variant['sku']);
        // dd($variant['outlets']);
        // dd($variant['attribute_value_1']);
            $var =  new ProductVariant();
            $var->product_id                    = $request->id;
            $var->store_id                      = Auth::user()->store_id;

            $varient_name                        =$product->name;

            $var->retail_price                  = $variant['retail_price'];
            $var->before_discount_price         = isset($variant['before_discount_price']);
            $var->sku                           = $this->getSku($request,$index);

            $err = $this-> verifyVariant( $var->sku); 
            if($err == false){
                return false;
            }

            if (array_key_exists('attribute_value_1', $variant)) {
               $var->attribute_value_1             .= $variant['attribute_value_1'];
               $varient_name                        .= ' '.$variant['attribute_value_1'];
            }
            if (array_key_exists('attribute_value_2', $variant)) {
               $var->attribute_value_2             = $variant['attribute_value_2'];
               $varient_name                        .= ' '.$variant['attribute_value_2'];
            }
            if (array_key_exists('attribute_value_3', $variant)) {
                $var->attribute_value_3             = $variant['attribute_value_3'];
                $varient_name                       .= ' '.$variant['attribute_value_3'];
            }
            $var->name                          = trim($varient_name);
            $var->barcode                       = 'data:image/png;base64,' . \DNS1D::getBarcodePNG($variant['sku'], "C128", 1,33);
            
            if ($variant['allow_out_of_stock'] == true) {
               $var->allow_out_of_stock            = 1;
            }else{
                $var->allow_out_of_stock            =0;
            }
            if ($variant['is_active'] == true) {
               $var->is_active            = 1;
            }else{
                $var->is_active            =0;
            }
            // $var->allow_out_of_stock            = $variant['allow_out_of_stock'];
            
            // $var->outlets                       = $variant['outlets'];
                // $resp = $this->verifyVariant($variant->sku);

                // if($resp){
                // dd($variant);
                    $var->save();
                    $this->AddProductstock($product,$variant,$var,$request);
            $index++;

                // }
            }
           $this->updateSequenceNumber(); 
           return true;
    }
    private function  AddProductstock($product,$variant,$var,$request)
    { 
        // $variants = ProductVariant::where('product_id' ,$product->id )->get()->toArray();
        // dd($variant);
        foreach($variant['outlets'] as $stock)
            {
           
                $productStock                    = new ProductStock();
                $productStock->invoice_date      = date('Y-m-d H:i:s');

                // $productStock->sale_price        = $stock['retail_price'];
                // $productStock->before_discount_price        = $stock['before_discount_price'];
                if (array_key_exists('quantity', $stock)) {
                    $productStock->quantity          = $stock['quantity'];
                }else{
                    $productStock->quantity = 0;
                } 
                if (array_key_exists('re_order_quantity', $stock)) {
                    $productStock->re_order_quantity = $stock['re_order_quantity'];
                }else{
                    $productStock->re_order_quantity = 0;
                }
                if (array_key_exists('re_order_point', $stock)) {
                    $productStock->re_order_point    = $stock['re_order_point'];
                }else{
                    $productStock->re_order_point = 0;
                }
                if (array_key_exists('supply_price', $stock)) {
                    $productStock->cost_price        = $stock['supply_price'];
                }else{
                    $productStock->cost_price = 0;
                }

                $productStock->margin        = $this->calculateMargin($productStock->cost_price,$productStock->retail_price);

                $productStock->product_id        = $product->id;

                $productStock->variant_id        = $var->id;

                $productStock->created           = $productStock->updated = Auth::user()->id;
                $productStock->created_at        = $productStock->updated_at = date('Y-m-d H:i:s');
                $productStock->outlet_id         = $stock['id'];
                $productStock->type              = OrderType::In;
                $productStock->is_default        = 1;

                $productStock->is_remove = false;

                $productStock->save();
        }
    }

    private function calculateMargin($cost_price,$sale_price){
        if($cost_price == 0  || $sale_price == 0){
            return 0;
        }

        $profit = $sale_price - $cost_price;
        $margin = $profit / $sale_price;
        return $margin * 100;
    }    
}
