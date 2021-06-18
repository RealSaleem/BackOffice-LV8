<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as ProductModel;
use App\Models\ProductVariant;
use App\Models\ProductTag;
use App\Models\CompositeProduct;
use Illuminate\Validation\Rule;
use App\Models\ProductImage;
use Auth;
use Storage;
use App\Models\Store;
use DB;

class AddProductRequest extends BaseRequest{

    public $category_id;
    public $brand_id;
    public $supplier_id;
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
    public $branches;

    public $sku_string;
    //public $quantity;

    public $created;
    public $id;
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

    public $related_id;
}

class AddProductRequestValidator {
    public function GetRules(){
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('store_id', Auth::user()->store_id);
                }),
            ],
            //'handle' => 'required|string',
            'description' => 'required|string',
            'active'      => 'boolean',
            'category_id' => 'required|numeric',
            'brand_id' => 'required_if:is_composite,0|numeric',
            'supplier_id' => 'required_if:is_composite,0|numeric',
            //'supplier_price' => 'required|numeric|min:1',
            //'markup' => 'required|numeric',
            'retail_price' => 'required_if:has_variant,true|numeric',
            'sku' => 'required',
            //'attribute_1' => 'required|string',
            //'attribute_2' => 'required_if:|string',
            //'attribute_3' => 'required_if:|string',
            // 'supplier_code' => 'string',
            // 'sales_account_code' => 'string',
            // 'purchase_account_code' => 'string',
            'has_variant' => 'boolean',
            'tags' => 'sometimes|array',
            'is_featured' => 'boolean',
            'is_composite' => 'boolean',
            'variants' => 'required_if:has_variant,true|array',
            // 'variants.sku' => 'required_if:has_variant,true|array',
            // 'variants.retail_price' => 'required_if:has_variant,true|array',
            // 'variants.before_discount_price' => 'required_if:has_variant,true|array',
            'composite_products' => 'required_if:is_composite,true|array',
        ];
    }

    public function GetMessages(){
        return [
            'variants.sku.required_if'    => 'The Variant Sku field is required',
            'variants.retail_price.required_if'    => 'The Variant Retail Price field is required',
            'variants.before_discount_price.required_if'    => 'The Variant Discount Price field is required'
        ];
    }
}


class AddProductRequestHandler {

    public function __construct()
    {
        $this->store = Store::with('product_variants')->find(Auth::user()->store_id);
        $this->sku_name_counter = 0;
        $this->sku_number_counter = $this->store->current_sequence_number;
    }

    public function Serve($request)
    {
        try{

            DB::beginTransaction();

            $product = $this->mapProduct($request);
            $product->save();

            if(sizeof($request->product_images) > 0){

                foreach ($request->product_images as $image) {
                    if(isset($image['size'])){
                        $product_image              = new ProductImage;
                        $product_image->product_id  = $product->id;
                        $product_image->url         = $image['path'];
                        $product_image->name        = $image['name'];
                        $product_image->size        = $image['size'];
                        $product_image->created     = Auth::user()->id;
                        $product_image->updated     = Auth::user()->id;
                        $product_image->save();
                    }
                }
            }

            if(sizeof($request->related_id))
            {
                $product->related()->attach($request->related_id);
            }

            $error = false;
            $duplicate_sku = null;

            if($request->is_composite){
                //add rows in composite product table
                $composite_rows = $this->mapComposite($request->composite_products, $product->id);

                //add one variant for composite product
                $variant = $this->addSingleVairant($request, $product->id);

                $resp = $this->verifyVariant($variant->sku);

                if($resp){
                    $variant->save();
                }
            }else{

                if ($request->has_variant)
                {
                    $variants = $this->mapVariants($request->variants, $product->id,$request);

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
                else{
                    //add one variant for simple  product
                    $variant = $this->addSingleVairant($request, $product->id);

                    $resp = $this->verifyVariant($variant->sku);

                    if($resp){
                        $variant->save();
                    }
                }
            }

            if($error){
                return new Response(false,null,null,[sprintf('Sku %s already exists',$duplicate_sku)]);
            }

            $tags = $this->mapProductTags($request->tags, $product->id);

            $this->updateSequenceNumber();

            DB::commit();

            return new Response(true, array($request));

        }catch(Exception $ex){
            DB::rollback();
            return new Response(false, null,$ex->getMessage());
        }
    }

    /*
    * This function create product object
    * from product data in request
    */
    private function mapProduct($request){
        $login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
        $newProduct = new ProductModel();
        if(!$request->is_composite){
            $newProduct->brand_id = $request->brand_id;
            $newProduct->supplier_id = $request->supplier_id;
        }
        if($request->active){
            $newProduct->active = $request->active;
        }
        else
            $newProduct->active = false;
        $newProduct->category_id = $request->category_id;
        $newProduct->name = $request->name;
        $newProduct->handle = $request->handle;
        // $newProduct->prefix = $request->prefix;
        $this->sku_string = strtolower($request->prefix);


        $newProduct->supplier_code = $request->supplier_code;
        $newProduct->purchase_account_code = $request->purchase_account_code;
        $newProduct->sales_account_code = $request->sales_account_code;


        $newProduct->store_id = $login_user->store_id;
        $newProduct->description = $request->description;
        $newProduct->is_featured = $request->is_featured;

        $newProduct->meta_title            = $request->meta_title;
        $newProduct->meta_keywords         = $request->meta_keywords;
        $newProduct->meta_description      = $request->meta_description;
        $newProduct->top_seller_web        = $request->top_seller_web;
        $newProduct->is_featured_web       = $request->is_featured_web;

        if($request->sku_custom){
            $newProduct->sku_type  = 'custom';
        }

        if($request->sku_name){
            $newProduct->sku_type  = 'name';
        }

        if($request->sku_number){
            $newProduct->sku_type  = 'number';
        }

        $newProduct->created = $login_user->id;

        //only non composite products can have attributes
        if(!$request->is_composite){
            $newProduct->attribute_1 = isset($request->attribute_1) ? $request->attribute_1 : '';
            $newProduct->attribute_2 = isset($request->attribute_2) ? $request->attribute_2 : '';
            $newProduct->attribute_3 = isset($request->attribute_3) ? $request->attribute_3 : '';
        }
        $newProduct->is_composite = $request->is_composite;
        return $newProduct;
    }

    /*
    * This function create array of objects of ProductVariant
    * from variant data in request
    */
    private function mapVariants($variants, $product_id,$request){
        $mappedVariants = array();

        $index = 0;
        foreach ($variants as $v) {
            $variant = new ProductVariant;
            $variant->product_id = $product_id;
            //$variant->quantity = $v['quantity'];
            //$variant->supplier_price = $v['supplier_price'];
            //$variant->sku =  $this->sku_string."-".$v['sku'];
            $variant->sku =  $this->getSku($request,$index);

            $variant->attribute_value_1 = isset($v['attribute_value_1']) ? $v['attribute_value_1'] : '';
            $variant->attribute_value_2 = isset($v['attribute_value_2']) ? $v['attribute_value_2'] : '';
            $variant->attribute_value_3 = isset($v['attribute_value_3']) ? $v['attribute_value_3'] : '';
            //$variant->markup = $v['markup'];
            $variant->retail_price = $v['retail_price'];

            $variant->barcode = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $v['sku'], "C128", 1,33);//\DNS1D::getBarcodeHTML($variant->id, "C39");
            $variant->before_discount_price    = $v['before_discount_price'];

            $mappedVariants[] = $variant;
            $index++;
        }

        return $mappedVariants;
    }

    /*
    * This function create array of objects of ProductTag
    * from tags data in request
    */
    private function mapProductTags($tags, $product_id){
        $mappedTags = array();
        foreach ($tags as $t) {
            $tag = new ProductTag;
            $tag->product_id =  $product_id;
            $tag->tag_id = $t['id'];
            $tag->save();
            $mappedTags[] = $tag;
        }

        return $mappedTags;
    }

    /*
    * This function created array of objects of CompositeProduct
    * from composite data in request
    */
    private function mapComposite($composite_products, $product_id){
        $mappedComposites = array();
        foreach ($composite_products as $cp) {
            $composite = new CompositeProduct;
            $composite->product_id = $product_id;
            $composite->product_variant_id = $cp['product_variant_id'];
            $composite->quantity = $cp['quantity'];
            $composite->save();
            $mappedComposites[] = $composite;
        }
        return $mappedComposites;
    }

    /*
    * This function adds one variant for composite products
    * without any attributes
    */
    private function addSingleVairant($request, $product_id){
        $variant = new ProductVariant;
        $variant->product_id = $product_id;
        //$variant->supplier_price = $request->supplier_price;
        $variant->sku =  $this->getSku($request);
        //$variant->markup = $request->markup;
        //$variant->quantity = $request->quantity;
        $variant->retail_price = $request->retail_price;

        $variant->barcode = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $variant->sku, "C128", 1,33); //\DNS1D::getBarcodeHTML($variant->id, "C39");
        $variant->before_discount_price    = $request->before_discount_price;

        return $variant;
        //$variant->save();
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

    private function getSku($request,$index=null)
    {
        if($request->sku_custom){

            if($request->has_variant){
                return $request->variants[$index]['sku'];
            }else{
                return $request->sku;
            }

        }else{
            return $this->_getSku($request);
        }
    }

    private function _getSku($request)
    {
        if($this->store->sku_generation == 0){
            return $this->getSkuByName($request);
        }else{
            return $this->getSkuByNumber();
        }
    }

    private function getSkuByName($request)
    {
        $this->sku_name_counter = $this->sku_name_counter + 1;
        return sprintf('%s-%d',$request->default_sku_name,$this->sku_name_counter);
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

