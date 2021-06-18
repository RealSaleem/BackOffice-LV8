<?php

namespace App\Imports;

use App\ProductImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use Maatwebsite\Excel\Concerns\WithConditionalSheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

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
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductVariant;
use App\Models\CompositeProduct;
use App\Models\ProductStock;
use App\Models\Outlet;
use App\Helpers\OrderType as OrderType;
use Session;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariantValue;
use App\Helpers\VariantStock;

class Composite implements ToCollection,WithHeadingRow
{
    private $lang;
    private $stock;
    private $user;

    public function __construct($lang, $stock,$user)
    {
        $this->lang = $lang;
        $this->stock = $stock;
        $this->user = $user;
    }


    public function collection(Collection $rows)
    {
        $products = [];
        foreach ($rows as $index => $row)
        {
            $data = [];
            foreach ($row as $key => $value) {
                if(strlen($key) > 0){
                    $data[$key] = $value;
                }
            }
            array_push($products, $data);
        }
        $res = $this->Serve($products);
        // season use for pasing the response to the import controller
        Session::put('Composite_Response',$res);
        return $res;
    }

    // public function makeRequestData($products){
    //     $import_request = new ImportProductRequest();
    //     $import_request->request = $products;
    //     $import_request->stock = $import_request->stock;

    //     $response = $this->Serve($import_request);
    //     // $response = $this->RequestExecutor->execute($import_request);
    //     return $response;
    // }
    public function Serve($request){
        try {
                DB::beginTransaction();

                if (!empty($request)) {
                    $skus = [];
                    $errors = [];

                    $composite_sheet_errors = $this->validateCompositeSheet($request);
                    if(sizeof($composite_sheet_errors)){
                        $errors['Composite Sheet'] = $composite_sheet_errors;
                    }
                    if(sizeof($errors) > 0){
                        return new Response(false,null,null,$errors);
                    }

                    $combo_groups = $this->groupArray($request, 'compositename' );
                    $cskus = [];

                    foreach ($combo_groups as $c_pro) {
                        $c_skus = [];
                        foreach ($c_pro as $cvalue) {
                            array_push($c_skus, $cvalue['sku']);
                        }
                        array_push($cskus, $c_skus);
                    }

                    foreach ($combo_groups as $key => $combo_group) {
                        if (sizeof($combo_group) > 1) {
                            $this->insertCompositeSheet($key, $combo_group, $this->stock);
                        }
                    }
                }

                DB::commit();

                $Message = 'Composite sheet has been Uploaded successfully';
                return new Response(true,null,null,null, $Message);

            } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false,null,null,$ex->getMessage());
        }
    }
    private function validateCompositeSheet($sheet)
    {
        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];
            $sku_groups = [];
            $index = 0;

            foreach($sheet as $row){

                $rows = array_slice($sheet,0,$index);

                $sku_groups = $this->getGroupedSkus($rows);

                if(is_array($sku_groups)){
                    $sss = array_filter($sku_groups,function($item) use($row){
                        if($row['compositename'] != $item['name']){
                            return $item;
                        }
                    });
                }

                $skus = array_column($sss, 'sku');
                $var_sku = ProductVariant::where([['sku',$row['variantsku']],['store_id', $this->user->store_id]])->first();
                $sku_check = [];
                if(isset($var_sku->sku)){
                    array_push($sku_check, $var_sku->sku);
                }
                $validator = Validator::make($row, [
                    'compositename'         => 'required|min:3|max:490',
                    'sku'                   => ['required','min:3','max:250', Rule::notIn($skus)],
                    'variantsku'            => ['required','min:3','max:250', Rule::in($sku_check)],
                    'category'              => 'required|string|nullable',
                    'quantityofvariants'    => 'required|integer|min:1',
                    'retailprice'           => 'required|min:0',
                    'compareprice'          => 'min:0|nullable',
                    'supplyprice'           => 'min:0|nullable',
                    'stock'                 => 'integer|nullable',
                    'reorderpoint'          => 'integer|min:0|nullable',
                    'reorderquantity'       => 'integer|min:0|nullable',
                ],[
                    'sku.not_in'    => ':attribute '.$row['sku'].' already exist in composite group',
                    'variantsku.in' => ':attribute '.$row['variantsku'].' must exist in standard sheet or system'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();

                    $error_row = [];

                    foreach ($errors->all() as $message) {
                        array_push($error_row,$message);
                    }

                    $key = 'Line '. ($index + 2);

                    $error_rows[$key] = $error_row;
                }

                $index++;
            }

            return $error_rows;
        }

        return [];
    }
    private function getGroupedSkus($rows){

        $combo_groups = $this->groupArray($rows, 'compositename' );

        $skus = [];

        foreach ($combo_groups as $key => $value) {
            array_push($skus,['name' => $value[0]['compositename'] , 'sku' => $value[0]['sku']]);
        }

       return $skus;
    }
    private function groupArray($arr, $group, $preserveGroupKey = true, $preserveSubArrays = true) {
        $temp = array();

        $index = 0;
        foreach($arr as $key => $value) {

            $groupValue = $value[$group];

            if(!$preserveGroupKey)
            {
                unset($arr[$key][$group]);
            }
            if(!array_key_exists($groupValue, $temp)) {
                $temp[$groupValue] = array();
            }

            if(!$preserveSubArrays){
                $data = count($arr[$key]) == 1? array_pop($arr[$key]) : $arr[$key];
            } else {
                $arr[$key]['line'] = $index + 2;
                $data = $arr[$key];

            }
            $temp[$groupValue][] = $data;
            $index++;
        }
        return $temp;
    }
    private function insertCompositeSheet($key,$pro_data, $stock_alert){
        if (!empty($pro_data)){
            $data = $this->ImportCompostProducts($pro_data[0], $key);
            $data['product']->save();
            $this->addLanguageTranslations($data['product'],$pro_data[0]);
            $this->attachCategoryIds($data['category_ids'], $data['product']);
            $variant = $this->addCompositeVairant($data['product'],$pro_data[0],$key);
            if (sizeof($pro_data) > 1) {
                // $productStock  = $this->AddCompositestock($data['product'],$pro_data, $variant, $stock_alert);
                $composite_pro  = $this->mapComposite($data['product'],$pro_data,$key);
            }

            //Variant stock Update i.e sum of stock of all outlet in stock field in varant table
            VariantStock::updateStock($variant->id);
        }
    }
    private function mapComposite($product, $request,$key_group){
        foreach ($request as $key => $req_val) {
                $get_stock = CompositeProduct::where([['product_id',$product->id]] )->delete();
        }
        foreach ($request as $key => $req_val) {
            $get_Varian = ProductVariant::where([['sku',$req_val['variantsku']],['store_id',$this->user->store_id]] )->first();
             if ($get_Varian != null) {
                $composite = new CompositeProduct;
                $composite->product_id              = $product->id;
                $composite->product_variant_id      = $get_Varian->id;
                $composite->quantity                = $req_val['quantityofvariants'];
                $composite->save();
            }
        }
    }
    private function getBrands($pro){
        if (isset($pro['brand'])) {
             $brand = Brand::where([['name',$pro['brand']],['store_id',$this->user->store_id]])->first();
             if($brand == null)
             {
                $brands = new Brand;
                $brands->name        = $pro['brand'];
                $brands->store_id    = $this->user->store_id;
                $brands->created    = $this->user->store_id;
                $brands->save();
               return $brands;
            }else{
                return $brand;
            }
        }
    }
    private function getCategories($pro){
        if (isset($pro['category'])) {
            $categories_arr = explode('|', $pro['category']);
            $witPcat_ids = [];
            foreach ($categories_arr as $value) {
                $cat_list_arr = explode('->', $value);
                $parent_id = 0;
                $cat_ids = [];
                foreach ($cat_list_arr as $cat_) {
                    $cat = trim($cat_);
                    $category = Category::where([['name',$cat],['store_id',$this->user->store_id]])->first();

                     if($category != null)
                     {
                        array_push($cat_ids,  $category->id);
                    }else{
                        $new_cat = new Category;
                        $new_cat->name        = $cat;
                        $new_cat->parent_id   = $parent_id;
                        $new_cat->sort_order   = 1;
                        $new_cat->pos_display   = 1;
                        $new_cat->web_display   = 1;
                        $new_cat->dinein_display   = 1;
                        $new_cat->store_id    = $this->user->store_id;
                        $new_cat->created    = $this->user->store_id;
                        $new_cat->save();
                        $parent_id             = $new_cat->id;
                       array_push($cat_ids,  $new_cat->id);
                    }
                }
                array_push($witPcat_ids,  $cat_ids);
            }
            return $witPcat_ids;
        }
    }
    private function getSuppliers($pro){
        if (isset($pro['supplier'])) {
            $sup_arr = explode(',', $pro['supplier']);
             $sup_ids = [];
            foreach ($sup_arr as  $sup_) {
                $sup = trim($sup_);
                $supplier = Supplier::where([['name',$sup],['store_id',$this->user->store_id]])->first();

                 if($supplier != null)
                 {
                    array_push($sup_ids,  $supplier->id);
                }else{
                    $new_sup = new Supplier;
                    $new_sup->name        = $sup;
                    $new_sup->store_id    = $this->user->store_id;
                    $new_sup->created    = $this->user->store_id;
                    $new_sup->save();
                   array_push($sup_ids,  $new_sup->id);
                }
            }
            return $sup_ids;
        }
    }
    private function addLanguageTranslations($product,$request)
    {
        $languages = $this->user->store->languages->where('short_name',$this->lang)->toArray();
            foreach ($languages as $language)
            {
                $has_seo          = 'has_seo_'.$language['short_name'];
                $title            = 'title_'.$language['short_name'];
                $description      = 'description_'.$language['short_name'];
                $meta_title       = 'meta_title_'.$language['short_name'];
                $meta_keywords    = 'meta_keywords_'.$language['short_name'];
                $meta_description = 'meta_description_'.$language['short_name'];

                $language_translation = new LanguageTranslation;

                $language_translation->product_id         = $product->id;
                $language_translation->title              = $product->name;
                $language_translation->description        = $request['description'];
                $language_translation->meta_title         = $request['metatitle'];
                $language_translation->meta_keywords      = $request['metakeywords'];
                $language_translation->meta_description   = $request['metadescription'];
                $language_translation->language_key       = $language['short_name'];

                $language_translation->save();
            }
            return $language_translation;
    }
    private function ImportCompostProducts($pro, $key){
        // dd($key);
        if (isset($key)) {
             $prod = Product::where([['name',$key],['store_id',$this->user->store_id]])->first();

             if($prod == null)
             {
               $product = new Product;
                    $product->store_id              =$this->user->store_id;
                    $product->created               =$this->user->id;

                    $category_ids  = $this->getCategories($pro);
                    $product->category_id           = end($category_ids[0]);

                    $product->name                  =$key;

                    $product->description           =$pro['description'];
                    $product->meta_title            =$pro['metatitle'];
                    $product->meta_keywords         =$pro['metakeywords'];
                    $product->meta_description      =$pro['metadescription'];
                    $product->is_composite  = 1;

                    if ($pro['pos'] == 'YES') {
                        $product->is_featured           =1;
                    }else{
                        $product->is_featured           =0;
                    }

                    if ($pro['catalogue'] == 'YES') {
                        $product->dinein_display        =1;
                    }else{
                        $product->dinein_display        =0;
                    }

                    if ($pro['featured'] == 'YES') {
                        $product->is_featured_web       =1;
                    }else{
                        $product->is_featured_web       =0;
                    }

                    if ($pro['top'] == 'YES') {
                        $product->top_seller_web        =1;
                    }else{
                        $product->top_seller_web        =0;
                    }

                    if ($pro['web'] == 'YES') {
                        $product->web_display        =1;
                    }else{
                        $product->web_display        =0;
                    }

                    if ($pro['active'] == 'YES') {
                        $product->active        =1;
                    }else{
                        $product->active        =0;
                    }

                    if (isset($value['sku'])) {
                        if($this->store->sku_generation == 0){
                            $product->sku_type  = 'name';
                        }else{
                            $product->sku_type  = 'number';
                        }
                    }else{
                        $product->prefix  =$pro['sku'];
                        $product->sku_type  = 'custom';
                    }
                    $obj = [
                        'category_ids' => $category_ids,
                        'product' => $product
                    ];
                    // dd($obj);
                return $obj;
            }else{
                $prod->store_id              =$this->user->store_id;
                    $prod->created               =$this->user->id;

                    $category_ids  = $this->getCategories($pro);
                    $prod->category_id           = end($category_ids[0]);

                    $prod->name                  =$key;

                    $prod->description           =$pro['description'];
                    $prod->meta_title            =$pro['metatitle'];
                    $prod->meta_keywords         =$pro['metakeywords'];
                    $prod->meta_description      =$pro['metadescription'];
                    $prod->is_composite  = 1;

                    if ($pro['pos'] == 'YES') {
                        $prod->is_featured           =1;
                    }else{
                        $prod->is_featured           =0;
                    }

                    if ($pro['catalogue'] == 'YES') {
                        $prod->dinein_display        =1;
                    }else{
                        $prod->dinein_display        =0;
                    }

                    if ($pro['featured'] == 'YES') {
                        $prod->is_featured_web       =1;
                    }else{
                        $prod->is_featured_web       =0;
                    }

                    if ($pro['top'] == 'YES') {
                        $prod->top_seller_web        =1;
                    }else{
                        $prod->top_seller_web        =0;
                    }

                    if ($pro['web'] == 'YES') {
                        $prod->web_display        =1;
                    }else{
                        $prod->web_display        =0;
                    }

                    if ($pro['active'] == 'YES') {
                        $prod->active        =1;
                    }else{
                        $prod->active        =0;
                    }

                    if (isset($value['sku'])) {
                        if($this->store->sku_generation == 0){
                            $prod->sku_type  = 'name';
                        }else{
                            $prod->sku_type  = 'number';
                        }
                    }else{
                        $prod->prefix  =$pro['sku'];
                        $prod->sku_type  = 'custom';
                    }
                    $obj = [
                        'category_ids' => $category_ids,
                        'product' => $prod
                    ];
                    // dd($obj);
                return $obj;

            }
        }
    }

    private function attachCategoryIds($category_ids,$product)
    {
        if(is_array($category_ids) && sizeof($category_ids) > 0)
        {
            $product->categories()->detach();
            foreach ($category_ids as $key) {
                $product->categories()->attach($key);
            }
        }
    }
    private function attachSupplierIds($supplier_ids,$product)
    {
        if(is_array($supplier_ids) && sizeof($supplier_ids) > 0)
        {
            $product->product_supplires()->detach();
            $product->product_supplires()->attach($supplier_ids);
        }
    }
    private function  AddCompositestock($product,$request,$variant,$stock_alert)
    {
        if ($variant != null) {
            $outlets = $this->user->store->outlets->toArray();
            foreach ($request as  $req) {
                $quantity = 0;
                $quantity = $req['quantityofvariants'] * $req['stock'];
                $this->removeProductstock($product,$req,$variant,$quantity,$stock_alert);
            }
            foreach($outlets as $value)
            {
                $outlet_id = $value['id'];

                $stock = ProductStock::where([['outlet_id', $outlet_id],['variant_id',$variant->id],['product_id' ,$product->id ]])->first();

                $old_stk = isset($stock->quantity) ? $stock->quantity : 0;

                foreach ($request as  $req) {
                    $quantity = 0;
                    $quantity = $req['quantityofvariants'] * $req['stock'];
                    $added_stock = 0;
                    $returnQ = $this->calculateCompositeQuantity($product,$req,$variant,$quantity, $outlet_id);

                    if($stock === null){
                        $product_Stock                    = new ProductStock();
                        $product_Stock->invoice_date      = date('Y-m-d H:i:s');
                        $product_Stock->cost_price        = $req['supplyprice'];
                        $product_Stock->sale_price        = $req['retailprice'];

                        if($returnQ[0] == true){
                            $product_Stock->quantity          = $returnQ[1];
                        }else{
                            $product_Stock->quantity          = $quantity;
                        }
                        $product_Stock->re_order_quantity = $req['reorderquantity'];
                        $product_Stock->re_order_point    = $req['reorderpoint'];
                        $product_Stock->product_id        = $product->id;
                        $product_Stock->variant_id        = $variant->id;
                        $product_Stock->created           = $this->user->id;
                        $product_Stock->created_at        = date('Y-m-d H:i:s');
                        $product_Stock->outlet_id         = $outlet_id;
                        $product_Stock->type              = OrderType::CompositionIn;
                        $product_Stock->is_default        = 1;
                        $product_Stock->is_remove = false;
                        $product_Stock->save();

                    }else{
                        $stock->cost_price        = $req['supplyprice'];
                        $stock->sale_price        = $req['retailprice'];
                        $stk_qty = 0;
                        if($stock_alert == 'add'){
                            $stk_qty              = $old_stk + $quantity;
                        }else{
                            $stk_qty              = $quantity;
                        }
                        $stock->quantity          = $stk_qty;
                        $stock->re_order_quantity = $req['reorderquantity'];
                        $stock->re_order_point    = $req['reorderpoint'];
                        $stock->updated           = $this->user->id;
                        $stock->updated_at        = date('Y-m-d H:i:s');
                        $stock->type              = OrderType::CompositionIn;
                        $stock->is_default        = 1;
                        $stock->is_remove = false;
                        // dd($stock);
                        $stock->save();
                    }
                }
            }
        }
    }
    private function  removeProductstock($product,$request,$variant,$quantity,$stock_alert)
    {
        // dd($product,$request,$variant,$quantity,$stock_alert);
        $var = ProductVariant::where([['sku',$request['variantsku']],['store_id',$this->user->store_id]])->first();
        if($var != null){
            $outlets = $this->user->store->outlets->toArray();
            foreach($outlets as $value)
            {
                $outlet_id = $value['id'];
                $stock = ProductStock::where([['outlet_id', $outlet_id],['variant_id',$var->id],['product_id' ,$product->id ]])->first();
                if($stock === null){
                    $product_Stock                    = new ProductStock();
                    $product_Stock->invoice_date      = date('Y-m-d H:i:s');
                    $product_Stock->cost_price        = $request['supplyprice'];
                    $product_Stock->sale_price        = $request['retailprice'];
                    $product_Stock->quantity          =  - $quantity;
                    $product_Stock->re_order_quantity = $request['reorderquantity'];
                    $product_Stock->re_order_point    = $request['reorderpoint'];
                    $product_Stock->product_id        = $product->id;
                    $product_Stock->variant_id        = $var->id;
                    $product_Stock->created           = $product_Stock->updated = $this->user->id;
                    $product_Stock->created_at        = $product_Stock->updated_at = date('Y-m-d H:i:s');
                    $product_Stock->outlet_id         = $outlet_id;
                    $product_Stock->type              = OrderType::CompositionOut;
                    $product_Stock->is_default        = 1;
                    $product_Stock->is_remove = true;
                    $product_Stock->save();
                }else{
                    $stock->cost_price        = $request['supplyprice'];
                    $stock->sale_price        = $request['retailprice'];
                    if($stock_alert == 'add'){
                        $added_stock = $stock->quantity - $quantity;
                        $stock->quantity          =   $added_stock;
                    }else{
                        // dd($quantity);
                        $stock->quantity          = - $quantity;
                    }
                    // dd('$stock');
                    $stock->re_order_quantity = $request['reorderquantity'];
                    $stock->re_order_point    = $request['reorderpoint'];
                    $stock->updated           = $this->user->id;
                    $stock->updated_at        = date('Y-m-d H:i:s');
                    $stock->type              = OrderType::CompositionOut;
                    $stock->is_default        = 1;
                    $stock->is_remove = true;
                    $stock->save();
                }
            }
        }
    }
    private function  calculateCompositeQuantity($product,$req,$variant,$quantity,$outlet_id)
    {
        $stock = ProductStock::where([['outlet_id',$outlet_id],['variant_id',$variant['id']],['product_id' ,$product->id ]])->first();
        if($stock != null){

            if($stock->quantity > $quantity){
                $avail = true;
                $stock_quantity = $stock->quantity;
                return [$avail, $stock_quantity];
            }else{
                $avail = false;
                $stock_quantity = $stock->quantity;
                return [$avail, $stock_quantity];
            }
        }
    }
    private function addCompositeVairant($product, $request,$key){
         if (isset($request)) {
             if($request != null)
             {
                $variant_edit = ProductVariant::where([['name',$key],['store_id',$this->user->store_id]])->first();
                if ($variant_edit != null) {
                $variant_edit->product_id                = $product->id;
                    $variant_edit->name                      = $product->name;
                    // $variant_edit->sku                      =  $request['sku'];
                    // $variant_edit->sku                      =  $this->getSku($request);
                    // $variant_edit->store_id                  = $this->user->store_id;
                    $variant_edit->updated                      =$this->user->id;
                    $variant_edit->attribute_value_1         = $product->name;
                    // $variant_edit->attribute_value_2      = $request->name;
                    //$variant_edit->markup                 = $request->markup;
                    $variant_edit->is_active                 = 1;
                    $variant_edit->retail_price             = $request['retailprice'];
                    if ($request['allowoutofstock'] == 'YES') {
                       $variant_edit->allow_out_of_stock          =1;
                    }else{
                        $variant_edit->allow_out_of_stock           =0;
                    }
                    $variant_edit->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $request['sku'], "C128", 1,33);
                    $variant_edit->before_discount_price    = $request['compareprice'];
                    // dd($variant_edit);
                    $variant_edit->save();
                    return $variant_edit;

                }else{
                    $variant = new ProductVariant;
                        $variant->product_id                = $product->id;
                        $variant->name                      = $product->name;
                        $variant->sku                       = $request['sku'];
                        // $variant->sku                      =  $this->getSku($request);
                        $variant->store_id                  = $this->user->store_id;
                        $variant->created                   =$this->user->id;
                        $variant->attribute_value_1         = $product->name;
                        // $variant->attribute_value_2   = $request->name;
                        //$variant->markup                 = $request->markup;
                        $variant->is_active                 = 1;
                        $variant->retail_price             = $request['retailprice'];
                        if ($request['allowoutofstock'] == 'YES') {
                           $variant->allow_out_of_stock          =1;
                        }else{
                            $variant->allow_out_of_stock           =0;
                        }
                        $variant->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $variant->sku, "C128", 1,33);
                        $variant->before_discount_price    = $request['compareprice'];
                        // dd($variant);
                        $variant->save();
                    return $variant;
                }

             }
         }
    }
    private function getSku($request,$index=null)
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

