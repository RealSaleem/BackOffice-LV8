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


class BrandTrans implements ToCollection,WithHeadingRow,WithChunkReading
{
    protected $lang;
    
    public function __construct($lang)
    {
        $this->lang = $lang;
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
        // dd($res);
        Session::put('BrandTrans_Response',$res);

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
                    $brand_sheet_errors = $this->validateBrandSheet($request);
                    if(sizeof($brand_sheet_errors)){
                        $errors['Brand ' .$this->lang] = $brand_sheet_errors;
                    }

                    if(sizeof($errors) > 0){
                        return new Response(false,null,null,$errors);
                    }

                    $res = $this->insertBrandTransSheet($request);
                }
        
                DB::commit();

                $Message = 'Brand '.$this->lang.' sheet has been Uploaded successfully';
                return new Response(true,null,null,null, $Message);

            } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false,null,null,$ex->getMessage());
        }
    }

    private function validateBrandSheet($sheet){

        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];

            $index = 0;

            foreach($sheet as $row){
               $brand = Brand::where([['name',$row['name']],['store_id', Auth::user()->store_id],['is_deleted',null]])->first();
                $name_check = [];
                if(isset($brand->name)){
                    array_push($name_check, $brand->name);
                }
                $validator = Validator::make($row, [
                    'name' => ['nullable','min:3','max:250', Rule::in($name_check)],
                ],[
                    'name.in' => ':attribute '.$row['name'].' must exist system',
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
    private function insertBrandTransSheet($request)
    {
        foreach($request as $row){
            $brand = Brand::where([['name',$row['name']],['store_id', Auth::user()->store_id],['is_deleted',null]])->first();
            // dd($brand);
                    LanguageTranslation::where([['brand_id',$brand->id],['language_key',$this->lang]])->delete();
            $lang = LanguageTranslation::where([['brand_id',$brand->id],['language_key',$this->lang]])->get();
            // dd($this->lang,$lang);
            // if(isset($lang) && $lang->count() > 0){
            //     foreach ($lang as $la) {
            //         $la->title              = $row['translated_name'];
            //         $la->meta_title         = $row['metatitle'];
            //         $la->meta_keywords      = $row['metakeywords'];
            //         $la->meta_description   = $row['metadescription'];
            //         $la->save();
            //     }

            // }else{
                $language_translation = new LanguageTranslation;

                $language_translation->brand_id           = $brand->id;
                $language_translation->title              = $row['translated_name'];
                $language_translation->meta_title         = $row['metatitle'];
                $language_translation->meta_keywords      = $row['metakeywords'];
                $language_translation->meta_description   = $row['metadescription'];
                $language_translation->language_key       = $this->lang;

                $language_translation->save();
            // }
        }
    }


}

