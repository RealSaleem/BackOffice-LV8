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


class ImportProductRequest extends BaseRequest{
    public $id;
    public $data;
}
 

class ImportProductRequestHandler {

  public function __construct()
    {
        $this->store = Store::with('product_variants')->find(Auth::user()->store_id);
        $this->sku_name_counter = 0;
        $this->sku_number_counter = $this->store->current_sequence_number;
    }

    public function Serve($request){
        try {
                DB::beginTransaction();

                if (!empty($request->request)) {
                    $stock_alert = $request->stock;
            // dd($stock_alert);
                    
                    $skus = [];

                    $errors = $this->validateSheet($request->request);


                    if(sizeof($errors) > 0){

                        return new Response(false,null,null,$errors);
                    }
                   $request->request =  $request->request->toArray();

                    foreach ($request->request[0] as $pro) {
                        array_push($skus, $pro['sku']);
                    }

                    // if(count(array_unique($skus)) < count($skus))
                    // {
                    //     $result = array_intersect($skus, array_unique($skus));

                    //     return new Response(false,null,null,['Standard sheet has duplicate SKU']);
                    // }
                    // else
                    // {
                        $this->insertStandardSheet($request->request[0], $stock_alert);
                    // }

                    $combo_groups = $this->groupArray($request->request[1], 'compositename' );
                    $cskus = [];

                    foreach ($combo_groups as $c_pro) {

                        $c_skus = [];
                        foreach ($c_pro as $cvalue) {
                            array_push($c_skus, $cvalue['sku']);
                        }
                        array_push($cskus, $c_skus);
                    }
                    
                    // if(count(array_unique($c_skus)) > 1)
                    // {
                    //     return new Response(false,null,null,['Composite sheet has different SKU in one group']);
                    // }
                    // else
                    // {
                        foreach ($combo_groups as $key => $combo_group) {
                            if (sizeof($combo_group) > 1) {
                                $this->insertCompositeSheet($key, $combo_group, $stock_alert); 
                            }
                        }
                    // }

                    $img_skus = [];
                    foreach ($request->request[2] as $img_pro) {
                        array_push($img_skus, $img_pro['sku']);
                    }

                    // if(count(array_unique($img_skus)) < count($img_skus))
                    // {
                    //     return new Response(false,null,null,['Images sheet has duplicate SKU']);
                    // }
                    // else
                    // {
                        $res = $this->UploadImagesSheet($request->request[2]);
                        // if (!$res) {
                        //     return new Response(false,null,null,['SKU not found in images sheet']);
                        // }
                    // }
                }
        
                DB::commit();

                $Message = 'Products file has been Uploaded successfully';
                return new Response(true,null,null,null, $Message);

            } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false,null,null,$ex->getMessage());
        }
    }
    public function validateSheet($sheets)
    {

        $standardSheet = $sheets[0]->toArray();
        $comparedSheet = $sheets[1]->toArray();
        $imagesSheet = $sheets[2]->toArray();

        

        $standard_sheet_errors = $this->validateStandardSheet($standardSheet);

        $skus = $this->getAllSkus($standardSheet);

        $composite_sheet_errors = $this->validateCompositeSheet($comparedSheet,$skus);

        $grouped_skus = array_column($this->getGroupedSkus($comparedSheet),'sku');

        $modified_skus = array_merge($skus,$grouped_skus);

        $image_sheet_errors = $this->validateImagesSheet($imagesSheet,$modified_skus);

        $errors = [];

        if(sizeof($standard_sheet_errors)){
            $errors['Standard Sheet'] = $standard_sheet_errors;
        }

        if(sizeof($composite_sheet_errors)){
            $errors['Composite Sheet'] = $composite_sheet_errors;
        }

        if(sizeof($image_sheet_errors)){
            $errors['Image Sheet'] = $image_sheet_errors;
        }
        if(strtolower($sheets[0]->getTitle()) !== "standard"){
            $errors['Standard Sheet'] = 'Sheet name must be Standard.';
        }
        if(strtolower($sheets[1]->getTitle()) !== "composite"){
            $errors['Composite Sheet'] = 'Sheet name must be Composite.';
        }
        if(strtolower($sheets[2]->getTitle()) !== "images"){
            $errors['Image Sheet'] = 'Sheet name must be Images.';
        }

        return $errors;
    }
    private function getAllSkus($sheet)
    {
        $skus = array_column($sheet, 'sku');

        $products = Product::with('product_variants')->where('store_id',Auth::user()->store_id)->get()->toArray();

        foreach ($products as $product) {
            
            $skus = array_merge($skus,array_column($product['product_variants'],'sku'));
        }

        return $skus;
    }
    private function validateStandardSheet($sheet){
        
        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];

            $index = 0;

            foreach($sheet as $key => $new_row) {
                $new_row = array_filter($new_row,
                                    function($cell) {
                                        return !is_null($cell);
                                    }
                       );
                if (count($new_row) == 0) {
                    unset($sheet[$key]);
                }
            }
            unset ($new_row);
            foreach($sheet as $row){
                if(count($sheet) > 1){
                    $rows = array_slice($sheet,0,$index);
                }else{
                    $rows = [];
                }
               // dd( $rows);

                $skus = array_column($rows, 'sku');

                $validator = Validator::make($row, [
                    'name' => 'required|min:3',
                    'sku' => ['required','min:3', Rule::notIn($skus)],
                    'category' => 'required|string|nullable',
                    'supplier' => 'required|string|nullable',
                    'brand' => 'required|string|nullable',
                    'retailprice' => 'required|min:0',
                    'compareprice' => 'min:0|nullable',
                    'supplyprice' => 'min:0|nullable',
                    'stock' => 'integer|min:0|nullable', 
                    'reorderpoint' => 'integer|min:0|nullable',
                    'reorderquantity' => 'integer|min:0|nullable',
                    'attribute_1' => 'required_if:hasvariant,YES',
                    'attribute_2' => 'string|nullable',
                    'attribute_3' => 'string|nullable',
                    'attribute_1_value' => 'required_if:hasvariant,YES',
                ],[
                    'not_in' => ':attribute '.$row['sku'].' already exist in sheet',
                    'required_if' => ':attribute is required Has Variant is "YES"'
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
    private function validateCompositeSheet($sheet,$sku_collection)
    {
        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];

            $index = 0;

            foreach($sheet as $row){

                $rows = array_slice($sheet,0,$index);

                $sku_groups = $this->getGroupedSkus($rows);

                $sss = array_filter($sku_groups,function($item) use($row){
                    if($row['compositename'] != $item['name']){
                        return $item;
                    }
                });

                $skus = array_column($sss, 'sku');

                $validator = Validator::make($row, [
                    'compositename' => 'required|min:3',
                    'sku' => ['required','min:3', Rule::notIn($skus)],
                    'variantsku' => ['required','string','min:3',Rule::in($sku_collection)],
                    'category' => 'required|string|nullable',
                    'quantityofvariants' => 'required|integer|min:1',
                    'retailprice' => 'required|min:0',
                    'compareprice' => 'min:0|nullable',
                    'supplyprice' => 'min:0|nullable',
                    'stock' => 'integer|min:0|nullable', 
                    'reorderpoint' => 'integer|min:0|nullable',
                    'reorderquantity' => 'integer|min:0|nullable',
                ],[
                    'sku.not_in' => ':attribute '.$row['sku'].' already exist in composite group',
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
    private function validateImagesSheet($sheet,$sku_collection){

        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];

            $index = 0;

            foreach($sheet as $row){

                $validator = Validator::make($row, [
                    'sku' => ['required','min:3', Rule::in($sku_collection)],
                    'url_1' => 'required|string',
                ],[
                    'sku.in' => ':attribute '.$row['sku'].' must exist in standard or composite sheet or system',
                    'url_1.required' => 'URL 1 is required'
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
    private function insertStandardSheet($sheet, $stock_alert){
            foreach($sheet as $key => $new_row) {
                $new_row = array_filter($new_row,
                                    function($cell) {
                                        return !is_null($cell);
                                    }
                       );
                if (count($new_row) == 0) {
                    unset($sheet[$key]);
                }
            }
            unset ($new_row);
            $pro_data =$sheet;

            if (!is_null($pro_data) && sizeof($pro_data) > 0 ) {
                foreach ($pro_data as $key => $pro) {
                    if(!empty($pro['hasvariant']) && strtolower($pro['hasvariant'])== 'yes') 
                    {
                        $data = $this->ImportProducts($pro);
                        $data['product']->save();
                        $this->addLanguageTranslations($data['product'],$pro);
                        $this->attachCategoryIds($data['category_ids'], $data['product']);
                        $this->attachSupplierIds($data['supplier_ids'], $data['product']);
                        $languages = Auth::user()->store->languages->toArray();
                        $this->addTranslations($data['product'],$languages, $pro);

                        $variant = $this->mapVariants($data['product'],$pro);
                        $this->AddProductstock($data['product'],$pro, $variant, $stock_alert);

                    }else {
                        $data = $this->ImportProducts($pro);
                        $data['product']->save();
                        $this->addLanguageTranslations($data['product'],$pro);
                        $this->attachCategoryIds($data['category_ids'], $data['product']);
                        $this->attachSupplierIds($data['supplier_ids'], $data['product']);

                        $variant = $this->addSingleVairant($data['product'],$pro);
                        $this->AddProductstock($data['product'],$pro, $variant, $stock_alert);
                    }
            }
        }
    }
    private function insertCompositeSheet($key,$pro_data, $stock_alert){
        if (!empty($pro_data)){
            $data = $this->ImportCompostProducts($pro_data[0], $key);
            $data['product']->save();
            $this->addLanguageTranslations($data['product'],$pro_data[0]);
            $this->attachCategoryIds($data['category_ids'], $data['product']);
            $variant = $this->addCompositeVairant($data['product'],$pro_data[0],$key);
            if (sizeof($pro_data) > 1) {
                $productStock  = $this->AddCompositestock($data['product'],$pro_data, $variant, $stock_alert);
                $composite_pro  = $this->mapComposite($data['product'],$pro_data,$key);                       
            }
        }
    }
    private function array_values_recursive($ary)
    {
       $lst = array();
       foreach( array_keys($ary) as $k ){
          $v = $ary[$k];
          if (is_scalar($v)) {
             $lst[] = $v;
          } elseif (is_array($v)) {
             $lst = array_merge( $lst,
                array_values_recursive($v)
             );
          }
       }
       return $lst;
    }
    private function UploadImagesSheet($images_data){
        if (!empty($images_data)){
            $rows = [];
            foreach ($images_data as $key => $value) {
            $img_data =$this->array_values_recursive($value);
                $p_img_sku = array_shift($img_data);
                $product_varient = ProductVariant::where([['sku',$p_img_sku],['store_id', Auth::user()->store_id]])->first();
                if($product_varient != null){
                    $product_name = Product::where([['id',$product_varient->product_id],['store_id', Auth::user()->store_id]])->first();
                       $imgs =  ProductImage::where([['product_id',$product_name->id],['created', Auth::user()->store_id]])->delete();
                    foreach ($img_data as  $value) {
                        $p_images = new ProductImage;
                        $p_images->product_id   = $product_name->id;
                        $p_images->url          = url('public/storage/images/'.$value);
                        $p_images->created      = Auth::user()->store_id;
                        $p_images->updated      = Auth::user()->store_id;
                        $p_images->name         = $product_name->name;
                        $p_images->size         = 0;
                        $p_images->save();
                    }
                }
            }
            if($product_varient != null){
                    return true;
            }else{
                   return false;
            }
        }
    }
    private function mapComposite($product, $request,$key_group){
        foreach ($request as $key => $req_val) {
                $get_stock = CompositeProduct::where([['product_id',$product->id]] )->delete();
        }
        foreach ($request as $key => $req_val) {
            $get_Varian = ProductVariant::where([['sku',$req_val['variantsku']],['store_id',Auth::user()->store_id]] )->first();
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
             $brand = Brand::where([['name',$pro['brand']],['store_id',Auth::user()->store_id]])->first();
             if($brand == null)
             {
                $brands = new Brand;
                $brands->name        = $pro['brand'];
                $brands->store_id    = Auth::user()->store_id;
                $brands->created    = Auth::user()->store_id;
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
                    $category = Category::where([['name',$cat],['store_id',Auth::user()->store_id]])->first();

                     if($category != null)
                     {
                        array_push($cat_ids,  $category->id);
                    }else{
                        $new_cat = new Category;
                        $new_cat->name        = $cat;
                        $new_cat->parent_id   = $parent_id;
                        $new_cat->pos_display   = 1;
                        $new_cat->web_display   = 1;
                        $new_cat->dinein_display   = 1;
                        $new_cat->store_id    = Auth::user()->store_id;
                        $new_cat->created    = Auth::user()->store_id;
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
                $supplier = Supplier::where([['name',$sup],['store_id',Auth::user()->store_id]])->first();

                 if($supplier != null)
                 {
                    array_push($sup_ids,  $supplier->id);
                }else{
                    $new_sup = new Supplier;
                    $new_sup->name        = $sup;
                    $new_sup->store_id    = Auth::user()->store_id;
                    $new_sup->created    = Auth::user()->store_id;
                    $new_sup->save();
                   array_push($sup_ids,  $new_sup->id);
                }
            }
            return $sup_ids;
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

    private function ImportProducts($pro){
        
        if (isset($pro['name'])) {
             $prod = Product::where([['name',$pro['name']],['store_id',Auth::user()->store_id]])->first();

             if($prod == null)
             {
               $product = new Product;
                    $product->store_id              =Auth::user()->store_id;
                    $product->created               =Auth::user()->id;
                    if (isset($pro['brand'])) {
                    $brand  = $this->getBrands($pro);
                    $product->brand_id              =$brand->id;
                    }

                    $category_ids  = $this->getCategories($pro); 
                    $product->category_id           = end($category_ids[0]);

                    $supplier_ids  = $this->getSuppliers($pro);  
                    $product->supplier_id           =$supplier_ids[0];
                      
                    $product->name                  =$pro['name']; 

                    $product->description           =$pro['description'];   
                    $product->meta_title            =$pro['metatitle'];
                    $product->meta_keywords         =$pro['metakeywords'];
                    $product->meta_description      =$pro['metadescription'];
                    if(strtolower($pro['hasvariant'])== 'yes') 
                    {
                        $product->has_variant            = 1;
                        $product->is_composite           = 0;
                    }
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
                       'supplier_ids' => $supplier_ids,
                        'product' => $product
                    ];
                    // dd($obj);
                return $obj;

            }else{
                $prod->updated               =Auth::user()->id;
                    if (isset($pro['brand'])) {
                    $brand  = $this->getBrands($pro);
                    $prod->brand_id              =$brand->id;
                    }

                    $category_ids  = $this->getCategories($pro); 
                    $prod->category_id           = end($category_ids[0]);

                    $supplier_ids  = $this->getSuppliers($pro);  
                    $prod->supplier_id           =$supplier_ids[0];
                      
                    $prod->name                  =$pro['name']; 

                    $prod->description           =$pro['description'];   
                    $prod->meta_title            =$pro['metatitle'];
                    $prod->meta_keywords         =$pro['metakeywords'];
                    $prod->meta_description      =$pro['metadescription'];
                    if(strtolower($pro['hasvariant'])== 'yes') 
                    {
                        $prod->has_variant            = 1;
                        $prod->is_composite           = 0;
                    }
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
                    

                    // if (isset($value['sku'])) {
                    //     if($this->store->sku_generation == 0){
                    //         $prod->sku_type  = 'name';
                    //     }else{
                    //         $prod->sku_type  = 'number';
                    //     }            
                    // }else{
                    //     $prod->prefix  =$pro['sku'];
                    //     $prod->sku_type  = 'custom';
                    // }
                    $obj = [
                        'category_ids' => $category_ids,
                       'supplier_ids' => $supplier_ids,
                        'product' => $prod
                    ];
                    // dd($obj);
                return $obj;
            }
        }
    }
    private function ImportCompostProducts($pro, $key){
        // dd($key);
        if (isset($key)) {
             $prod = Product::where([['name',$key],['store_id',Auth::user()->store_id]])->first();

             if($prod == null)
             {
               $product = new Product;
                    $product->store_id              =Auth::user()->store_id;
                    $product->created               =Auth::user()->id;

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
                $prod->store_id              =Auth::user()->store_id;
                    $prod->created               =Auth::user()->id;

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
    
    private function mapVariants($product,$request){
        $get_variant = ProductVariant::where([['sku',$request['sku']],['store_id',Auth::user()->store_id]])->first();
        if ($get_variant == null) {
            $variant_pro = new ProductVariant;
            // dd($product);
            $variant_pro->product_id               = $product->id;
            $variant_pro->name                     = $product->name;
            $variant_pro->sku                      =  $request['sku'];
            // $variant_pro->sku                      =  $this->getSku($request);
            $variant_pro->attribute_value_1         = isset($request['attribute_1_value']) ? $request['attribute_1_value'] : '';
            $variant_pro->attribute_value_2         = isset($request['attribute_2_value']) ? $request['attribute_2_value'] : '';
            $variant_pro->attribute_value_3         = isset($request['attribute_3_value']) ? $request['attribute_3_value'] : '';

            $variant_pro->store_id                  = Auth::user()->store_id;
            //$variant_pro->markup                 = $request->markup;
            $variant_pro->is_active               = 1;
            $variant_pro->retail_price             = $request['retailprice'];

            if ($request['allowoutofstock'] == 'YES') {
               $variant_pro->allow_out_of_stock          =1;
            }else{
                $variant_pro->allow_out_of_stock           =0;
            }
            $variant_pro->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $variant_pro->sku, "C128", 1,33); 
            $variant_pro->before_discount_price    = $request['compareprice'];
            // dd($variant_pro);
            $variant_pro->save(); 
            return $variant_pro; 
        }else{
        // dd($get_variant);

            // dd($product);
            $get_variant->product_id               = $product->id;
            $get_variant->name                     = $product->name;
            // $get_variant->sku                      =  $request['sku'];
            // $get_variant->sku                      =  $this->getSku($request);
            $get_variant->attribute_value_1         = isset($request['attribute_1_value']) ? $request['attribute_1_value'] : '';
            $get_variant->attribute_value_2         = isset($request['attribute_2_value']) ? $request['attribute_2_value'] : '';
            $get_variant->attribute_value_3         = isset($request['attribute_3_value']) ? $request['attribute_3_value'] : '';

            $get_variant->store_id                  = Auth::user()->store_id;
            //$get_variant->markup                 = $request->markup;
            $get_variant->is_active               = 1;
            $get_variant->retail_price             = $request['retailprice'];

            if ($request['allowoutofstock'] == 'YES') {
               $get_variant->allow_out_of_stock          =1;
            }else{
                $get_variant->allow_out_of_stock           =0;
            }

            $get_variant->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG( $request['sku'], "C128", 1,33); 
            $get_variant->before_discount_price    = $request['compareprice'];
            // dd($get_variant);
            $get_variant->save();
            return $get_variant;
        }

    }

    public function addTranslations($product, $translations, $pro)
    {
        $trans = $translations[0];
        if(isset($pro['attribute_1'])){
            $attribute = new ProductAttribute;
            $attribute->language_key = $trans['short_name'];
            $attribute->name = $pro['attribute_1'];
            $attribute->product_id = $product->id;
            $attribute->save();

            $value = new ProductAttributeValue;
            $value->product_attribute_id = $attribute->id;
            $value->name = $pro['attribute_1_value'];
            $value->save();
        }

        if(isset($pro['attribute_2'])){
            $attribute = new ProductAttribute;
            $attribute->language_key = $trans['short_name'];
            $attribute->name = $pro['attribute_2'];
            $attribute->product_id = $product->id;
            $attribute->save();

            $value = new ProductAttributeValue;
            $value->product_attribute_id = $attribute->id;
            $value->name = $pro['attribute_2_value'];
            $value->save();
        }

        if(isset($pro['attribute_3'])){
            $attribute = new ProductAttribute;
            $attribute->language_key = $trans['short_name'];
            $attribute->name = $pro['attribute_3'];
            $attribute->product_id = $product->id;
            $attribute->save();

            $value = new ProductAttributeValue;
            $value->product_attribute_id = $attribute->id;
            $value->name = $pro['attribute_3_value'];
            $value->save();
            // dd($value);
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
    private function  AddProductstock($product,$request,$variant, $stock_alert)
    { 

        $outlets = Auth::user()->store->outlets->toArray();
        foreach($outlets as $value)
            {
                $outlet_id = $value['id'];

                $stock = ProductStock::where([['outlet_id', $outlet_id],['variant_id',$variant->id],['product_id' ,$product->id ]])->first();
                if($stock === null){
                    $product_Stock                    = new ProductStock();
                    $product_Stock->invoice_date      = date('Y-m-d H:i:s');
                    $product_Stock->cost_price        = $request['supplyprice'];
                    $product_Stock->sale_price        = $request['retailprice'];
                    $product_Stock->quantity          = $request['stock'];
                    $product_Stock->re_order_quantity = $request['reorderquantity'];
                    $product_Stock->re_order_point    = $request['reorderpoint'];
                    $product_Stock->product_id        = $product->id;
                    $product_Stock->variant_id        = $variant->id;
                    $product_Stock->created           = $product_Stock->updated = Auth::user()->id;
                    $product_Stock->created_at        = $product_Stock->updated_at = date('Y-m-d H:i:s');
                    $product_Stock->outlet_id         = $outlet_id;
                    $product_Stock->type              = OrderType::In;
                    $product_Stock->is_default        = 1;
                    $product_Stock->is_remove = false;
                    $product_Stock->save();

                }else{
                    $stock->cost_price        = $request['supplyprice'];
                    $stock->sale_price        = $request['retailprice'];
                    if($stock_alert == 'add'){
                        $added_stock = $stock->quantity + $request['stock'];
                        $stock->quantity          = $added_stock;
                    }else{
                        $stock->quantity          = $request['stock'];
                    }
                    $stock->re_order_quantity = $request['reorderquantity'];
                    $stock->re_order_point    = $request['reorderpoint'];
                    $stock->updated           = Auth::user()->id;
                    $stock->updated_at        = date('Y-m-d H:i:s');
                    $stock->type              = OrderType::In;
                    $stock->is_default        = 1;
                    $stock->is_remove = false;
                    $stock->save();
                }
            }
    }
    private function  AddCompositestock($product,$request,$variant,$stock_alert)
    { 
        if ($variant != null) {
            $outlets = Auth::user()->store->outlets->toArray();
            foreach ($request as  $req) {
                $quantity = '';
                $quantity = $req['quantityofvariants'] * $req['stock'];
                $this->removeProductstock($product,$req,$variant,$quantity,$stock_alert);

                foreach($outlets as $value)
                {
                    $outlet_id = $value['id'];
                    $returnQ = $this->calculateCompositeQuantity($product,$req,$variant,$quantity, $outlet_id);
                
                    $stock = ProductStock::where([['outlet_id', $outlet_id],['variant_id',$variant->id],['product_id' ,$product->id ]])->first();
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
                        $product_Stock->created           = Auth::user()->id;
                        $product_Stock->created_at        = date('Y-m-d H:i:s');
                        $product_Stock->outlet_id         = $outlet_id;
                        $product_Stock->type              = OrderType::In;
                        $product_Stock->is_default        = 1;
                        $product_Stock->is_remove = false;
                        $product_Stock->save();

                    }else{
                        $stock->cost_price        = $req['supplyprice'];
                        $stock->sale_price        = $req['retailprice'];

                        if($stock_alert == 'add'){
                            
                            $added_stock = $stock->quantity + $quantity;
                            $stock->quantity          = $added_stock;
                        }else{
                            $stock->quantity          = $quantity;
                        }
                        $stock->re_order_quantity = $req['reorderquantity'];
                        $stock->re_order_point    = $req['reorderpoint'];
                        $stock->updated           = Auth::user()->id;
                        $stock->updated_at        = date('Y-m-d H:i:s');
                        $stock->type              = OrderType::In;
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
      
        $var = ProductVariant::where([['sku',$request['variantsku']],['store_id',Auth::user()->store_id]])->first();
        $outlets = Auth::user()->store->outlets->toArray();
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
                $product_Stock->created           = $product_Stock->updated = Auth::user()->id;
                $product_Stock->created_at        = $product_Stock->updated_at = date('Y-m-d H:i:s');
                $product_Stock->outlet_id         = $outlet_id;
                $product_Stock->type              = OrderType::In;
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
                $stock->updated           = Auth::user()->id;
                $stock->updated_at        = date('Y-m-d H:i:s');
                $stock->type              = OrderType::In;
                $stock->is_default        = 1;
                $stock->is_remove = true;
                $stock->save();
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
    private function addSingleVairant($product, $request){

         if (isset($request)) {
             $prod = Product::where([['name',$request['name']],['store_id',Auth::user()->store_id]])->first();
             if($prod == null)
             {
                $variant = new ProductVariant;
                    $variant->product_id               = $product->id;
                    $variant->name                     = $product->name;
                    $variant->sku                      =  $request['sku'];
                    // $variant->sku                      =  $this->getSku($request);
                    $variant->store_id                  = Auth::user()->store_id;
                    $variant->created               =Auth::user()->id;
                    $variant->attribute_value_1   = $product->name;
                    // $variant->attribute_value_2   = $request->name;
                    //$variant->markup                 = $request->markup;
                    $variant->is_active               = 1;
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

             }else{
                $variant_edit = ProductVariant::where([['sku',$request['sku']],['product_id',$prod->id],['store_id',Auth::user()->store_id]])->first();
                if ($variant_edit == null) {
                    $variant = new ProductVariant;
                        $variant->product_id                = $product->id;
                        $variant->name                      = $product->name;
                        $variant->sku                       = $request['sku'];
                        // $variant->sku                      =  $this->getSku($request);
                        $variant->store_id                  = Auth::user()->store_id;
                        $variant->created                   =Auth::user()->id;
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
                }else{

                    $variant_edit->product_id                = $product->id;
                    $variant_edit->name                      = $product->name;
                    // $variant_edit->sku                      =  $request['sku'];
                    // $variant_edit->sku                      =  $this->getSku($request);
                    // $variant_edit->store_id                  = Auth::user()->store_id;
                    $variant_edit->updated                      =Auth::user()->id;
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
                }
             }
         }
    }
    private function addCompositeVairant($product, $request,$key){

         if (isset($request)) {
             if($request != null)
             {
                $variant_edit = ProductVariant::where([['name',$key],['store_id',Auth::user()->store_id]])->first();
                if ($variant_edit != null) {
                $variant_edit->product_id                = $product->id;
                    $variant_edit->name                      = $product->name;
                    // $variant_edit->sku                      =  $request['sku'];
                    // $variant_edit->sku                      =  $this->getSku($request);
                    // $variant_edit->store_id                  = Auth::user()->store_id;
                    $variant_edit->updated                      =Auth::user()->id;
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
                        $variant->store_id                  = Auth::user()->store_id;
                        $variant->created                   =Auth::user()->id;
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