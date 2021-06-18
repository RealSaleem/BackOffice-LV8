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


class Images implements ToCollection,WithHeadingRow
{

    private $user;

    public function __construct($user)
    {
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
        Session::put('Images_Response',$res);

        return $res;
    }
   
    public function Serve($request){
        try {
                DB::beginTransaction();

                if (!empty($request)) {
                    $skus = [];
                    $errors = [];

                    $image_sheet_errors = $this->validateImagesSheet($request);
                    if(sizeof($image_sheet_errors)){
                        $errors['Image Sheet'] = $image_sheet_errors;
                    }

                    if(sizeof($errors) > 0){
                        return new Response(false,null,null,$errors);
                    }

                    $res = $this->UploadImagesSheet($request);
                }
        
                DB::commit();

                $Message = 'Images sheet has been Uploaded successfully';
                return new Response(true,null,null,null, $Message);

            } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false,null,null,$ex->getMessage());
        }
    }

    private function validateImagesSheet($sheet){

        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];

            $index = 0;

            foreach($sheet as $row){
                $var_sku = ProductVariant::where([['sku',$row['sku']],['store_id', $this->user->store_id]])->first();
                $sku_img = [];
                if(isset($var_sku->sku)){
                    array_push($sku_img, $var_sku->sku);
                }
                $validator = Validator::make($row, [
                    'sku' => ['required','min:3','max:250', Rule::in($sku_img)],
                    'url_1' => 'required|string|max:490',
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
                $product_varient = ProductVariant::where([['sku',$p_img_sku],['store_id', $this->user->store_id]])->first();
                if($product_varient != null){
                    $product_name = Product::where([['id',$product_varient->product_id],['store_id', $this->user->store_id]])->first();
                    if(isset($product_name)){
                        $imgs =  ProductImage::where([['product_id',$product_name->id],['created', $this->user->store_id]])->delete();
                        foreach ($img_data as  $value) {
                            $p_images = new ProductImage;
                            $p_images->product_id   = $product_name->id;
                            $p_images->url          = url('public/storage/images/'.$value);
                            $p_images->created      = $this->user->store_id;
                            $p_images->updated      = $this->user->store_id;
                            $p_images->name         = $product_name->name;
                            $p_images->size         = 0;
                            $p_images->save();
                        }
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
}

