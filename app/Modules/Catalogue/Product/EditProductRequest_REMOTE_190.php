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
use App\Models\SalesTax;

use App\Helpers\OrderType as OrderType;
use Session;
use App\Helpers\VariantStock;

class EditProductRequest extends BaseRequest
{
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
    public $images;
    public $remove_composite_products;
    public $allow_subscription;  
    public $apply_tax;
    public $inclusive;
    public $tax_per; 
    public $unit; 
    public $add_ons;  

}

class EditProductRequestValidator
{
    public function GetRules($request)
    {
        return [
            // 'category_id'               => 'required|numeric',
            // 'brand_id' => 'required_if:is_composite,0|numeric',
            // 'supplier_id' => 'required_if:is_composite,0|numeric',
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('store_id', Auth::user()->store_id);
                })->ignore($request->id, 'id')->whereNull('deleted_at'),                
            ],
            // 'description'               => 'required|string',
            // 'handle'                    => 'required|string',
            // 'is_featured'               => 'boolean',
            // 'active'                    => 'boolean',
            //'tags'                      => 'sometimes|required|array',
            // 'product_variants'          => 'required_if:is_composite,false|array',
            // 'composite_products'        => 'required_if:is_composite,true|array',
            // 'remove_composite_products' => 'required_if:is_composite,true|array',
            // 'removed_tags'              => 'sometimes|required|array',
            // 'product_images.*.path'     => 'required|string',
            // 'product_images.*.name'     => 'required|string|unique',
        ];
    }
}

class EditProductRequestHandler
{

    public function __construct()
    {
        $this->store = Store::with('product_variants')->find(Auth::user()->store_id);
        $this->sku_name_counter = 0;
        $this->sku_number_counter = $this->store->current_sequence_number;        
    }

    public function Serve($request)
    {
        // dd($request);
        $product     = Product::with([
            'product_categories',
            'product_suppliers',
            'product_ralated',
            'product_images',
            'product_stock',
            'product_verients',
            'composite_products',
            'transalation'
        ])->find($request->id);

        $this->addLanguageTranslations($product,$request);

        $this->mapProduct($product,$request);
        
        $product->save();

        if ($product->is_composite == 1) 
        {
            $this->addProductImages($product,$request);
            $this->attachCategoryIds($product,$request);
            $variant = $this->addSingleVeirant($product,$request);
            if(isset($variant->name) != true || $variant == false){
                return new Response(false,null,null,['SKU already exists']);
            }
               
            if($this->store->product_type == null || $this->store->product_type != 2){
                $this->AddProductstock($product,$request);
                $this->removeProductstock($product,$request,$variant);
            }

            $this->AddComboProducts($product,$request);
            $this->updateSequenceNumber();
        }

        if($product->is_composite == 0) 
        {
            if ($product->has_variant) 
            {
                $this->addProductImages($product,$request);
                $this->attachRelatedIds($product,$request);                
                $this->attachAddOnIds($product,$request);
                $this->attachCategoryIds($product,$request);
                $this->attachSupplierIds($product,$request);  
            }else{
                $this->addProductImages($product,$request);
                $this->attachCategoryIds($product,$request);
                $this->attachSupplierIds($product,$request);  
                $this->attachRelatedIds($product,$request); 
                $this->attachAddOnIds($product,$request);

                $variant = $this->addSingleVeirant($product,$request);
                if(isset($variant->name) != true || $variant == false){
                    return new Response(false,null,null,['SKU already exists']);
                }
                $this->AddProductstock($product,$request);
                // dd('asdsad');
                $this->updateSequenceNumber();
            }
        }
        $vari = ProductVariant::where('product_id',$product->id )->get();
        foreach ($vari as $variu) {

           //Variant stock Update i.e sum of stock of all outlet in stock field in varant table
            VariantStock::updateStock($variu->id);
        }
        
        return new Response(true, array($request));
    }


    private function mapProduct($product, $request){

        $product->store_id              = Auth::user()->store_id;
        $product->category_id           =$request->category_id[0];
           
        $product->active                =$request->active;    
        $product->name                  =$request->title_en; 

        $product->description           =$request->description_en;   
        $product->meta_title            =$request->meta_title_en;
        $product->meta_keywords         =$request->meta_keywords_en;
        $product->meta_description      =$request->meta_description_en;

        $product->supplier_code         =$request->supplier_code; 
        $product->sales_account_code    =$request->sales_account_code;    
        $product->purchase_account_code =$request->purchase_account_code; 
        $product->is_featured           =$request->is_featured;   
        $product->created               =Auth::user()->id;   
        $product->dinein_display        =$request->dinein_display;

        $product->is_featured_web       =$request->is_featured_web;   
        $product->top_seller_web        =$request->top_seller_web;    
        $product->web_display           =$request->web_display;
        $product->is_composite          =$request->is_combo;  
        $product->allow_subscription    = $request->allow_subscription;  

        if($request->is_combo != 1){

            $product->brand_id              = $request->brand_id;
            $product->supplier_id           =$request->supplier_id[0];
        }
        if($request->active){
            $product->active = $request->active;
        }
        else{
            $product->active = false;
        }

        if(!is_null($request->unit)){
            $product->unit    = $request->unit;
        } 

        if ($request->sku_type == 1) {
            if($this->store->sku_generation == 0){
                $product->prefix  = $request->sku;
                $product->sku_type  = 'name';
            }else{
                $product->prefix  = $request->sku;
                $product->sku_type  = 'number';
            }            
        }elseif ($request->sku_type == 0){
            $product->prefix  = $request->sku;
            $product->sku_type  = 'custom';
        }

        $salestax = SalesTax::where([['store_id', Auth::user()->store_id],['status', 1]])->first();
        if($salestax != null){
            if ($request->apply_tax == 1) {
                $product->inc_or_exc        = $request->inclusive;  
                $product->tax_per           = $request->tax_per;  
            }else{
                $product->inc_or_exc        = 0;  
                $product->tax_per           = 0;
            }
        }


        return $product;
    }
    
    private function mapVariants($variants, $product_id, $sku_string,$request)
    {
        $mappedVariants = array();
        $index = 0;
        foreach ($variants as $v) {
            $variant                    = new ProductVariant;
            $variant->product_id        = $product_id;
            //$variant->quantity          = $v['quantity'];
            //$variant->supplier_price    = $v['supplier_price'];
            //$variant->sku               = $sku_string . "-" . $v['sku'];
            $variant->sku               = $this->getSku($request,$index);
            $variant->attribute_value_1 = isset($v['attribute_value_1']) ? $v['attribute_value_1'] : '';
            $variant->attribute_value_2 = isset($v['attribute_value_2']) ? $v['attribute_value_2'] : '';
            $variant->attribute_value_3 = isset($v['attribute_value_3']) ? $v['attribute_value_3'] : '';
            //$variant->markup            = $v['markup'];
            $variant->retail_price      = $v['retail_price'];
            //TODO: image
            $variant->image = ' ';
            $variant->barcode = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $variant->sku, "C128", 1,33);//\DNS1D::getBarcodeHTML($variant->sku, "C128",2,40);
            $variant->before_discount_price  = $v['before_discount_price'];

            $mappedVariants[] = $variant;
            $index++;
        }
        return $mappedVariants;
    }

    private function verifyVariant($req_sku,$verient_sku)
    {
        $err = false;
        $variants = $this->store->product_variants->toArray();

        $skus = array_column($variants, 'sku');
        if($req_sku != $verient_sku ){
            if(in_array($req_sku,$skus)){
                return $err = true;
            }else{
                // dd('3');
                return $err;
            }

        }else{
            return $err;
        }
        
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
    private function attachAddOnIds($product,$request)
    {
        $product->product_add_on()->detach();
        if(is_array($request->add_ons) && sizeof($request->add_ons) > 0)
        {
            $product->product_add_on()->attach($request->add_ons);
        }
    }
    private function attachRelatedIds($product,$request)
    {
        $product->related()->detach();
        if(is_array($request->related_id) && sizeof($request->related_id) > 0)
        {
            $product->related()->attach($request->related_id);
        }
    }
    private function attachCategoryIds($product,$request)
    {
        if(is_array($request->category_id) && sizeof($request->category_id) > 0)
        {
            $product->categories()->detach();
            $product->categories()->attach($request->category_id);
        }
    }
    private function attachSupplierIds($product,$request)
    {
        if(is_array($request->supplier_id) && sizeof($request->supplier_id) > 0)
        {
            $product->product_supplires()->detach();
            $product->product_supplires()->attach($request->supplier_id);
        }
    }
    private function addProductImages($product,$request)
    {
        // dd($product->id);
            ProductImage::where('product_id', $product->id)->delete();
        if(is_array($request->images) && sizeof($request->images) > 0){
           
                foreach ($request->images as $image) {
                    // dd($request->images);
                    if(isset($image['size'])){
                        $product_image = new ProductImage;
                        $product_image->product_id = $product->id;
                        $product_image->url = $image['path'];
                        $product_image->name = $image['name'];
                        $product_image->size = $image['size'];
                        $product_image->created = Auth::user()->id;
                        $product_image->updated = Auth::user()->id;
                        $product_image->save();
                    }
                }
            }
    }
    private function  addSingleVeirant($product,$request)
    {
        $veriants = ProductVariant::where('product_id',$request->id)->get();

        foreach ($veriants as $verient) {
            // $verientariant                 = ProductVariant::find($v['id']);
            // dd($verient);
            $verient->name                      = $product->name;
            $verient->allow_out_of_stock        = $request->allow_out_of_stock;
            $verient->retail_price              = $request->retail_price;
            $verient->store_id                  = Auth::user()->store_id;
            $verient->attribute_value_1     = $product->name;

             if ($request->sku_type == 1) {
                $err = $this->verifyVariant($request->sku,$verient->sku);
                if($err == false){
                    $verient->sku                   =  $request->sku;
                }else{
                    return false;
                }
            }elseif ($request->sku_type == 0){
                   $err = $this->verifyVariant($request->sku_custom,$verient->sku);
                if($err == false){
                    $verient->sku                   =  $request->sku_custom;
                }else{
                    return false;
                }
            }

            $verient->retail_price              = $request->retail_price;
            $verient->barcode                   = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $verient->sku, "C128", 1,33);
            $verient->before_discount_price     = $request->before_discount_price;

            $verient->save();
        }
        return $verient;

    }
    private function addLanguageTranslations($product,$request)
    {
        LanguageTranslation::where(['product_id' => $product->id])->delete();

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

    private function AddProductstock($product,$request)
    { 
        $variants = ProductVariant::where('product_id' ,$product->id )->get()->toArray();
        
        foreach($request->branches as $outlet => $value)
            {
                if ($product->is_composite == 1) 
                {
                    $productStock = ProductStock::where([['outlet_id',$value['id']],['variant_id',$variants[0]['id']],['type',OrderType::CompositionIn]])->first();
                }else{
                   $productStock = ProductStock::where([['outlet_id',$value['id']],['variant_id',$variants[0]['id']],['type',OrderType::In]])->first();
                }
                
                if(!is_null($productStock)){
                    

                    $outlet_id = $outlet;
                    
                    // $productStock                    = new ProductStock();
                    $productStock->invoice_date      = date('Y-m-d H:i:s');
                    // $productStock->invoice_number    = $request->invoice_number;
                    // $productStock->purchase_order    = $request->purchase_order;
                    $productStock->cost_price        = $value['supply_price'];
                    $productStock->sale_price        = $request->retail_price;
                    $productStock->margin            = $this->calculateMargin($productStock->cost_price,$request->retail_price);
                    // $productStock->notes             = $request->notes;
                    $productStock->quantity          = $value['quantity'] < 0 ? 0 : $value['quantity'];
                    $productStock->re_order_quantity = $value['re_order_quantity'];
                    $productStock->re_order_point    = $value['re_order_point'];
                    $productStock->product_id        = $product->id;
                    
                    $productStock->variant_id        = $variants[0]['id'];
                    
                    $productStock->updated             = Auth::user()->id;
                    $productStock->updated_at              = date('Y-m-d H:i:s');
                    $productStock->outlet_id         = $outlet_id;
                    if ($product->is_composite == 1) 
                    {
                    $productStock->type              = OrderType::CompositionIn;
                    }else{
                    $productStock->type              = OrderType::In;
                    }
                    $productStock->is_default        = 1;
                    
                    $productStock->is_remove = false;
                    
                    $productStock->save();
                }
        }
    }
    private function  removeProductstock($product,$request,$variant)
    { 
        foreach ($request->composite_products as $composite) {
            $v = ProductVariant::find($composite['id'] );
            foreach($request->branches as $outlet => $value)
            {

            }
        }
        foreach ($request->composite_products as $composite) {
           
            $var = ProductVariant::find($composite['id'] );

            foreach($request->branches as $outlet => $value)
                {
                    $productStock = ProductStock::where([['outlet_id',$value['id']],['variant_id',$var['id']],['type',OrderType::CompositionOut]])->first();
                  $outlet_id = $outlet;
                if($productStock == null){
                    $productStock                    = new ProductStock();
                    $productStock->invoice_date      = date('Y-m-d H:i:s');
                    // $productStock->invoice_number    = $request->invoice_number;
                    // $productStock->purchase_order    = $request->purchase_order;
                    $productStock->sale_price        = $request->retail_price;
                    $qty = 0;

                    $stock_qty = ProductStock::where([
                                'variant_id' => $variant->id , 
                                'outlet_id' => $outlet_id
                            ])->sum('quantity');

                    if($stock_qty > 0 || $var->allow_out_of_stock == 1){
                        if(isset($value['quantity']) && $value['quantity'] >= 0  ){
                            $qty = $value['quantity'];
                        }

                        if($qty > 0 ){
                            $qty = $qty * $composite['quantity'];
                        }else{
                            $qty = $composite['quantity'];
                        }
                    }

                    // dd( $qty);
                    // $productStock->notes             = $request->notes;
                    $productStock->quantity          = - $qty;
                    $productStock->re_order_quantity = isset($value['re_order_quantity']) ? $value['re_order_quantity'] : 0; 
                    $productStock->re_order_point    = isset($value['re_order_point']) ? $value['re_order_point'] : 0; 
                    $productStock->cost_price        = isset($value['supply_price']) ? $value['supply_price'] : 0; 

                    $productStock->margin            = $this->calculateMargin($productStock->cost_price,$productStock->sale_price);

                    $productStock->product_id        = $var->product_id;

                    $productStock->variant_id        = $composite['id'];

                    $productStock->updated = Auth::user()->id;
                    $productStock->updated_at = date('Y-m-d H:i:s');
                    $productStock->outlet_id         = $outlet_id;
                    $productStock->type              = OrderType::CompositionOut;
                    
                    $productStock->is_default        = 1;
                    $productStock->is_remove        = 1;

                    $productStock->save();

                  }else{

                    // $productStock                    = new ProductStock();
                    $productStock->invoice_date      = date('Y-m-d H:i:s');
                    // $productStock->invoice_number    = $request->invoice_number;
                    // $productStock->purchase_order    = $request->purchase_order;
                    // $productStock->sale_price        = $request->retail_price;
                    $qty = 0;

                    $stock_qty = ProductStock::where([
                                'variant_id' => $variant->id , 
                                'outlet_id' => $outlet_id
                            ])->sum('quantity');

                    if($stock_qty > 0 || $var->allow_out_of_stock == 1){
                        if(isset($value['quantity']) && $value['quantity'] >= 0  ){
                            $qty = $value['quantity'];
                        }

                        if($qty > 0 ){
                            $qty = $qty * $composite['quantity'];
                        }else{
                            $qty = $composite['quantity'];
                        }
                    }

                    // dd( $qty);
                    // $productStock->notes             = $request->notes;
                    $productStock->quantity          = - $qty;
                    $productStock->re_order_quantity = isset($value['re_order_quantity']) ? $value['re_order_quantity'] : 0; 
                    $productStock->re_order_point    = isset($value['re_order_point']) ? $value['re_order_point'] : 0; 
                    $productStock->cost_price        = isset($value['supply_price']) ? $value['supply_price'] : 0; 

                    $productStock->margin            = $this->calculateMargin($productStock->cost_price,$productStock->sale_price);

                    $productStock->product_id        = $var->product_id;

                    $productStock->variant_id        = $composite['id'];

                    $productStock->updated = Auth::user()->id;
                    $productStock->updated_at = date('Y-m-d H:i:s');
                    $productStock->outlet_id         = $outlet_id;
                    $productStock->type              = OrderType::CompositionOut;
                    
                    $productStock->is_default        = 1;
                    $productStock->is_remove        = 1;

                    $productStock->save();
                  }

            }
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

    private function  AddComboProducts($product,$request)
    {
        if(!is_null($request->composite_products)){
            // $combosi->get();
            foreach($request->composite_products as $combosite)
                {
                    $combo = CompositeProduct::where([['product_id',$request->id],['product_variant_id',$combosite['id']]])->first();
               
                    if($combo != null){
                        // $combo                           = new CompositeProduct();
                        // $combo->product_id            = $product->id;
                        // $combo->product_variant_id    = $combosite['id'];
                        $combo->quantity              = $combosite['quantity'];
                        $combo->save();
                    }else{
                        $compos                        = new CompositeProduct();
                        $compos->product_id            = $product->id;
                        $compos->product_variant_id    = $combosite['id'];
                        $compos->quantity              = $combosite['quantity'];
                        $compos->save();
                    }
            }
        }
    }
}
