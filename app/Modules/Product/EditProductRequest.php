<?php

namespace App\Modules\Product;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\CompositeProduct;
use App\Models\Product as ProductModel;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Auth;

class EditProductRequest extends BaseRequest
{
    public $id;

    public $category_id;
    public $brand_id;
    public $supplier_id;
    public $active;

    public $name;
    public $handle;
    public $description;
    public $image;

    public $supplier_code;
    public $sales_account_code;
    public $purchase_account_code;

    public $tags;
    public $product_variants;
    public $is_featured;

    public $composite_products;

    public $remove_composite_products;
    public $removed_tags;
    public $barcode;

    public $updated;
    public $has_variant;
    public $variants;
    public $sku_string;
    public $prefix;

    public $attribute_1;
    public $attribute_2;
    public $attribute_3;

    public $meta_title;
    public $meta_keywords;
    public $meta_description;
    public $before_discount_price;

    public $is_featured_web;
    public $top_seller_web;

    public $sku_custom;
    public $sku_name;
    public $sku_number;    
    public $default_sku_name;   

    public $related_id;    
    public $product_images;
    public $is_composite;    
}

class EditProductRequestValidator
{
    public function GetRules($request)
    {
        return [
            'category_id'               => 'required|numeric',
            // 'brand_id' => 'required_if:is_composite,0|numeric',
            // 'supplier_id' => 'required_if:is_composite,0|numeric',
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('store_id', Auth::user()->store_id);
                })->ignore($request->id, 'id'),                
            ],
            'description'               => 'required|string',
            // 'handle'                    => 'required|string',
            'is_featured'               => 'boolean',
            'active'                    => 'boolean',
            //'tags'                      => 'sometimes|required|array',
            'product_variants'          => 'required_if:is_composite,false|array',
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
        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        /*update product fields*/
        $product                        = ProductModel::find($request->id);
        $product->name                  = $request->name;
        $product->handle                = $request->handle;
        $product->description           = $request->description;
        //$product->image                 = $request->image;
        $product->supplier_code         = $request->supplier_code;
        $product->sales_account_code    = $request->sales_account_code;
        $product->purchase_account_code = $request->purchase_account_code;
        $product->category_id           = $request->category_id;

        if(empty($request->is_composite)){
            $product->supplier_id           = $request->supplier_id;
            $product->brand_id              = $request->brand_id;
        }
        $product->is_featured           = $request->is_featured;
        $product->active                = $request->has_variant;
        $product->meta_title            = $request->meta_title;
        $product->meta_keywords         = $request->meta_keywords;
        $product->meta_description      = $request->meta_description;

        $product->top_seller_web        = $request->top_seller_web;
        $product->is_featured_web       = $request->is_featured_web;
        
        $product->updated               = $login_user->id;


        if ($request->attribute_1 != null) {
            $product->attribute_1 = $request->attribute_1;
        }
        if ($request->attribute_2 != null) {
            $product->attribute_2 = $request->attribute_2;
        }
        if ($request->attribute_3 != null) {
            $product->attribute_3 = $request->attribute_3;
        }

        $product->save();


        if(sizeof($request->product_images) > 0){


            ProductImage::where('product_id', $product->id)->delete();

            foreach ($request->product_images as $image) {
                if(isset($image['size'])){
                    $product_image = new ProductImage;
                    $product_image->product_id = $product->id;
                    $product_image->url = $image['path'];
                    $product_image->name = $image['name'];
                    $product_image->size = $image['size'];
                    $product_image->updated = Auth::user()->id;
                    $product_image->created = Auth::user()->id;
                    $product_image->save();
                }
            }
        }

        if(sizeof($request->related_id))
        {
            $product->related()->detach();
            $product->related()->attach($request->related_id);
        }

        $error = false;
        $duplicate_sku = null;        

        /*update product variants*/

        foreach ($request->product_variants as $v) {
            $variant                 = ProductVariant::find($v['id']);
            //$variant->supplier_price = $v['supplier_price'];
            //$variant->quantity       = $v['quantity'];
            //$variant->markup         = $v['markup'];
            $variant->sku            = $v['sku'];
            $variant->retail_price   = $v['retail_price'];
            $variant->barcode        = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $v['sku'], "C128", 1,33);//\DNS1D::getBarcodeHTML($v['sku'], "C128",2,40);
            $variant->before_discount_price    = $v['before_discount_price'];
            $variant->save();
        }

        if(sizeof($request->product_variants) > 0){
            $this->sku_name_counter = sizeof($request->product_variants);
        }


        /*Add new variants*/
        $this->sku_string = strtolower($request->prefix);
        if ($request->has_variant) {
            $variants = $this->mapVariants($request->variants, $request->id, $this->sku_string,$request);

            foreach ($variants as $variant) {
                
                $resp = $this->verifyVariant($variant->sku);

                if($resp){
                    $variant->save();

                    $this->store->product_variants->push($variant);
                    
                }else{
                    $duplicate_sku = $variant->sku;
                    $error = true;
                    break;
                }
            }                        
        }

        if($error){
            return new Response(false,null,null,[sprintf('Sku %s already exists',$duplicate_sku)]);
        } 

        

        /*update/add composite products*/
        foreach ($request->composite_products as $cp) {
            $composite_p = null;
            if (isset($cp['id'])) {
                //simple update existing
                $composite_p           = CompositeProduct::find($cp['id']);

                if(isset($composite_p)){
                    $composite_p->quantity = $cp['quantity'];
                    $composite_p->save();
                }
            } else {

                $composite_p                     = new CompositeProduct;
                $composite_p->quantity           = $cp['quantity'];
                $composite_p->product_variant_id = $cp['product_variant_id'];
                $composite_p->product_id         = $request->id;
                $composite_p->save();
            }
        }

        /*update tags*/
        foreach ($request->tags as $t) {
            if (!isset($t['product_tag_id'])) {
                // find existing tags
                $findTags = ProductTag::where('tag_id', $t['id'])->where('product_id', $request->id)->first();
                if ($findTags == null) {
                    /*add new tag*/
                    $tag             = new ProductTag;
                    $tag->product_id = $request->id;
                    $tag->tag_id     = $t['id'];
                    $tag->save();
                }
            }
        }
        

        /*remove items*/
        if (count($request->removed_tags) > 0) {
            $remove_tags_ids = [];
            foreach ($request->removed_tags as $rt) {
                $remove_tags_ids[] = $rt['product_tag_id'];
            }
            ProductTag::destroy($remove_tags_ids);
        }

        if (count($request->remove_composite_products) > 0) {
            $rcpi = [];
            foreach ($request->remove_composite_products as $rcp) {
                $rcpi[] = $rcp['id'];
            }
            CompositeProduct::destroy($rcpi);
        }

        $this->updateSequenceNumber();

        return new Response(true, array($request));
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
            return $request->variants[$index]['sku'];
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
}
