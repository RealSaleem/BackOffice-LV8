<?php

namespace App\Imports\Translations;

use App\ProductImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use Maatwebsite\Excel\Concerns\WithConditionalSheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use App\Requests\Catalogue\Import\ImportProductRequest;

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

class ProductTrans implements ToCollection,WithHeadingRow,WithChunkReading
{
    protected $lang;
    
    public function __construct($lang)
    {
        $this->lang = $lang;
    }
    public function collection(Collection $rows)
    {
        // dd($rows);
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
        // dd($res);
        Session::put('ProductTrans_Response',$res);
        // if(!$res->IsValid){
        // }
        // dd($res);
        return $res;
    }
    public function chunkSize(): int
    {
        return 2;
    }
    public function Serve($request){
        try {
                DB::beginTransaction();

                if (!empty($request)) {
                    $errors = [];

                    $standard_sheet_errors = $this->validateProductSheet($request);
            // dd('234',$request);

                    if(sizeof($standard_sheet_errors)){
                        $errors['Product '.$this->lang] = $standard_sheet_errors;
                    }

                    if(sizeof($errors) > 0){
                        return new Response(false,null,null,$errors);
                    }

                    $res = $this->insertProductTransSheet($request);
                }
        
                DB::commit();

                $Message = 'Product '.$this->lang.' sheet has been Uploaded successfully';
                return new Response(true,null,null,null, $Message);

            } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false,null,null,$ex->getMessage());
        }
    }

    private function validateProductSheet($sheet){

        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];

            $index = 0;

            foreach($sheet as $key => $new_row) {
                if(is_array($new_row)){
                    $new_row = array_filter($new_row,
                        function($cell) {
                            return !is_null($cell);
                        }
                    );
                    if (count($new_row) == 0) {
                        unset($sheet[$key]);
                    }
                }
            }

            unset ($new_row);
            foreach($sheet as $row){
                if(count($sheet) > 1){
                    $rows = array_slice($sheet,0,$index);
                }else{
                    $rows = [];
                }

                $skus = array_column($rows, 'sku');
                
                $var_sku = ProductVariant::where([['sku',$row['sku']],['store_id', Auth::user()->store_id]])->first();
                $sku_check = [];
                if(isset($var_sku->sku)){
                    array_push($sku_check, $var_sku->sku);
                }
                $validator = Validator::make($row, [
                    'translated_name' => 'required|min:3|max:490',
                    'sku' => ['required','min:3','max:250', Rule::notIn($skus), Rule::in($sku_check)],
                    'attribute_1' => 'required_if:hasvariant,YES|max:295',
                    'attribute_2' => 'string|nullable|max:295',
                    'attribute_3' => 'string|nullable|max:295',
                    'attribute_1_value' => 'required_if:hasvariant,YES|max:295',
                    'attribute_2_value' => 'max:295',
                    'attribute_3_value' => 'max:295',
                ],[
                    'not_in' => ':attribute '.$row['sku'].' already exist in sheet',
                    'required_if' => ':attribute is required Has Variant is "YES"',
                    'sku.in' => ':attribute '.$row['sku'].' must exist in system'
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

    private function insertProductTransSheet($sheet)
    {
        $pro_data =$sheet;

        if (!is_null($pro_data) && sizeof($pro_data) > 0 ) {
            foreach ($pro_data as $key => $pro) {
                if(!empty($pro['hasvariant']) && strtolower($pro['hasvariant'])== 'yes') 
                {
                    $var_pro = ProductVariant::with('product')->where([['sku',$pro['sku']],['store_id', Auth::user()->store_id]])->first();
                    
                    $attri = ProductAttribute::with('values')->where([['product_id' , $var_pro->product->id],['language_key',$this->lang]])->get();
                    if(isset($attri) && $attri->count() > 0){
                        foreach ($attri as $attrb) {
                            ProductAttributeValue::where('product_attribute_id' , $attrb->id)->delete();
                        }
                        ProductAttribute::with('values')->where([['product_id' , $var_pro->product->id],['language_key',$this->lang]])->delete();
                    }
                }
            }
            foreach ($pro_data as $key => $pro) {
                $var_pro = ProductVariant::with('product')->where([['sku',$pro['sku']],['store_id', Auth::user()->store_id]])->first();
                if(isset($var_pro->product)){
                    $this->addLanguageTranslations($var_pro->product,$pro);
                    
                    if(!empty($pro['hasvariant']) && strtolower($pro['hasvariant'])== 'yes') 
                    {
                        $this->addTranslations($var_pro->product, $pro);
                        $this->editAttrValue($var_pro->product);
                    }
                }
            }
        }
    }

    public function editAttrValue($product)
    {
        $prod  = Product::with(['product_attributes','product_attributes.values','product_verients'])->find($product->id);

        foreach($prod->product_verients as $vari){
            $att_v  = ProductVariantValue::where([['variant_id',$vari->id],['language_key',$this->lang]])->delete();
        }
        $translations = Auth::user()->store->languages->where('short_name',$this->lang)->toArray();
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

        foreach($rows as $key => $row){
            foreach ($product->product_verients as $var) {
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
                    foreach($row['attr2_values'] as $keyval2 => $item2){
                        $Varient_value2 = new ProductVariantValue;
                        $Varient_value2->language_key                    = $row['short_name'];
                        $Varient_value2->variant_id                      = $var->id;
                        $Varient_value2->product_attribute_value_id      = $item2;
                        $Varient_value2->save();
                    }
                }
                if(isset($row['attr3_values']) && $row['attr3_values'] != null){
                    foreach($row['attr3_values'] as $keyval3 => $item3){
                        $Varient_value3 = new ProductVariantValue;
                        $Varient_value3->language_key                    = $row['short_name'];
                        $Varient_value3->variant_id                      = $var->id;
                        $Varient_value3->product_attribute_value_id      = $item3;
                        $Varient_value3->save();
                    }
                }
            }
        }
    }
    private function addLanguageTranslations($product,$request)
    {
        // $language_translations = LanguageTranslation::where([['product_id', $product->id],['language_key',$this->lang]])->get();
        // foreach ($language_translations as $language_translation) 
        // {
        //     $language_translation->title              = $request['translated_name'];
        //     $language_translation->description        = $request['description'];
        //     $language_translation->meta_title         = $request['metatitle'];
        //     $language_translation->meta_keywords      = $request['metakeywords'];
        //     $language_translation->meta_description   = $request['metadescription'];
        //     $language_translation->save();
        // }
        $languages = Auth::user()->store->languages->where('short_name',$this->lang)->toArray();
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
                $language_translation->title              = $request['translated_name'];
                $language_translation->description        = $request['description'];
                $language_translation->meta_title         = $request['metatitle'];
                $language_translation->meta_keywords      = $request['metakeywords'];
                $language_translation->meta_description   = $request['metadescription'];
                $language_translation->language_key       = $language['short_name'];

                $language_translation->save();
            }
            return $language_translation;
    }
    public function addTranslations($product, $pro)
    {

        if(isset($pro['attribute_1'])){
            $attribute = new ProductAttribute;
            $attribute->language_key = $this->lang;
            $attribute->name = $pro['attribute_1'];
            $attribute->product_id = $product->id;
            $attribute->save();

            // $product->attribute_1 = $pro['attribute_1'];

            $value = new ProductAttributeValue;
            $value->product_attribute_id = $attribute->id;
            $value->name = $pro['attribute_1_value'];
            $value->save();
        }

        if(isset($pro['attribute_2'])){
            $attribute2 = new ProductAttribute;
            $attribute2->language_key = $this->lang;
            $attribute2->name = $pro['attribute_2'];
            $attribute2->product_id = $product->id;
            $attribute2->save();

            // $product->attribute_2 = $pro['attribute_2'];

            $value2 = new ProductAttributeValue;
            $value2->product_attribute_id = $attribute2->id;
            $value2->name = $pro['attribute_2_value'];
            $value2->save();
        }

        if(isset($pro['attribute_3'])){
            $attribute3 = new ProductAttribute;
            $attribute3->language_key = $this->lang;
            $attribute3->name = $pro['attribute_3'];
            $attribute3->product_id = $product->id;
            $attribute3->save();

            // $product->attribute_3 = $pro['attribute_3'];

            $value3 = new ProductAttributeValue;
            $value3->product_attribute_id = $attribute3->id;
            $value3->name = $pro['attribute_3_value'];
            $value3->save();
        }
        // $product->save();
    }
}

