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


class Stock implements ToCollection,WithHeadingRow
{
    private $stock;
    private $user;

    public function __construct($stock,$user)
    {
        $this->stock = strtolower($stock);
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
        Session::put('Stock_Response',$res);

        return $res;
    }
    
    public function Serve($request){
        try {
                DB::beginTransaction();

                if (!empty($request)) {
                    $skus = [];
                    $errors = [];
                    // $request = array_slice($request[0], 0,1000);

                    $stock_sheet_errors = $this->validateStockSheet($request);
                    if(sizeof($stock_sheet_errors)){
                        $errors['Stock Sheet'] = $stock_sheet_errors;
                    }

                    if(sizeof($errors) > 0){
                        return new Response(false,null,null,$errors);
                    }
                    $res = $this->addStockSheet($request);
                }
        
                DB::commit();

                $Message = 'Stock sheet has been Uploaded successfully';
                return new Response(true,null,null,null, $Message);

            } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false,null,null,$ex->getMessage());
        }
    }

    private function validateStockSheet($sheet){

        if(!is_null($sheet) && sizeof($sheet) > 0)
        {
            $error_rows = [];
            $index = 0;

                $sku_stock = ProductVariant::where('store_id', $this->user->store_id)->whereIn('sku',array_column($sheet, 'sku'))->pluck('sku')->toArray();
                // dd(array_column($sheet, 'sku'),$sku_stock);

            foreach($sheet as $row){
                
                $validator = Validator::make($row, [
                    'sku' => ['required','min:3','max:250'],
                    // 'sku' => ['required','min:3','max:250', Rule::in($sku_stock)],
                ],[
                    'sku.in' => ':attribute '.$row['sku'].' must exist in standard or composite sheet or system',
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

    private function addStockSheet($stock_sheet){
        if (!empty($stock_sheet)){

            $outlets = $this->user->store->outlets->toArray();
            foreach (array_slice($stock_sheet, 0,1000) as $key => $stock_row) {
                $stock_row_sku = array_shift($stock_row);
                foreach ($outlets as  $outlet) {
                    $outlet_id              = $outlet['id'];
                    
                    if($outlet != null && isset($stock_row[str_replace(' ', '', strtolower($outlet['name'])).'_'.$outlet_id.'_stock'])){

                        $stock_value            = $stock_row[str_replace(' ', '', strtolower($outlet['name'])).'_'.$outlet_id.'_stock'];
                        $supplyprice_column     = $stock_row[str_replace(' ', '', strtolower($outlet['name'])).'_'.$outlet_id.'_supplyprice'];
                        $reorderpoint_column    = $stock_row[str_replace(' ', '', strtolower($outlet['name'])).'_'.$outlet_id.'_reorderpoint'];
                        $reorderquantity_column = $stock_row[str_replace(' ', '', strtolower($outlet['name'])).'_'.$outlet_id.'_reorderquantity'];
                        
                        $product_variant = ProductVariant::where([
                                ['sku',$stock_row_sku],
                                ['store_id', $this->user->store_id]
                            ])->with(['product_stock' => function ($stock) use  ($outlet_id) {
                                return $stock->where([['type',OrderType::In],['outlet_id',$outlet_id]]);
                            }])->first();
                        
                        $request = [
                            'stock'           => $stock_value,
                            'supplyprice'     => $supplyprice_column,
                            'reorderpoint'    => $reorderpoint_column,
                            'reorderquantity' => $reorderquantity_column,
                        ];
                        if(count($product_variant->product_stock) > 0 && $product_variant->product != null){
                            if($product_variant->product->is_composite == 1){
                                $this->addCompositestock($product_variant,$request,$outlet,$product_variant->product_stock->first());
                            }

                            $new_stock = $product_variant->product_stock[0];
                            if($this->stock == 'update'){
                                $new_stock->cost_price          = $supplyprice_column;
                                $new_stock->quantity            = $stock_value;
                                $new_stock->re_order_point      = $reorderpoint_column;
                                $new_stock->re_order_quantity   = $reorderquantity_column;
                            }

                            if($this->stock == 'add'){
                                $new_stock->quantity            += $stock_value;
                                $new_stock->re_order_point      += $reorderpoint_column;
                                $new_stock->re_order_quantity   += $reorderquantity_column;
                            }
                            
                            $new_stock->save();
                        }else{
                            $product_Stock                    = new ProductStock();
                            $product_Stock->invoice_date      = date('Y-m-d H:i:s');
                            $product_Stock->cost_price        = $supplyprice_column;
                            $product_Stock->sale_price        = $product_variant->retail_price;
                            $product_Stock->quantity          = $stock_value;
                            $product_Stock->re_order_quantity = $reorderquantity_column;
                            $product_Stock->re_order_point    = $reorderpoint_column;
                            $product_Stock->product_id        = $product_variant->product_id;
                            $product_Stock->variant_id        = $product_variant->id;
                            $product_Stock->created           = $product_Stock->updated = $this->user->id;
                            $product_Stock->created_at        = $product_Stock->updated_at = date('Y-m-d H:i:s');
                            $product_Stock->outlet_id         = $outlet_id;
                            $product_Stock->type              = OrderType::In;
                            $product_Stock->is_default        = 1;
                            $product_Stock->is_remove         = false;
                            $product_Stock->save();
                        }
                        $count = VariantStock::stock($product_variant->id);
                        $product_variant->stock  = $count;
                        $product_variant->save();

                    }
                }
            }
        }
        return true;
    }

    private function  addCompositestock($variant,$request,$outlet,$stock)
    { 
        $stock_alert = $this->stock;
        $product = $variant->product;
        $outlet_id = $outlet['id'];
        
        $old_stk = isset($stock->quantity) ? $stock->quantity : 0;

        foreach ($product->composite_products as $key => $combo_product) {
            $request['variantsku'] = $combo_product->product_variant->sku;
            $request['quantityofvariants'] = $combo_product->quantity;
            $request['retailprice'] = $variant->retail_price;

            $quantity = 0;
            $quantity = $request['quantityofvariants'] * $request['stock'];
            $this->removeProductstock($product,$request,$variant,$quantity,$stock_alert,$outlet,$stock);
            

            $quantity = 0;
            $quantity = $request['quantityofvariants'] * $request['stock'];
            $added_stock = 0;
            $returnQ = $this->calculateCompositeQuantity($product,$request,$variant,$quantity, $outlet_id,$stock);
        
            if($stock === null){
                $product_Stock                    = new ProductStock();
                $product_Stock->invoice_date      = date('Y-m-d H:i:s');
                $product_Stock->cost_price        = $request['supplyprice'];
                $product_Stock->sale_price        = $request['retailprice'];

                if($returnQ[0] == true){
                    $product_Stock->quantity          = $returnQ[1];
                }else{
                    $product_Stock->quantity          = $quantity;
                }
                $product_Stock->re_order_quantity = $request['reorderquantity'];
                $product_Stock->re_order_point    = $request['reorderpoint'];
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
                $stock->cost_price        = $request['supplyprice'];
                $stock->sale_price        = $request['retailprice'];
                $stk_qty = 0;
                if($stock_alert == 'add'){
                    $stk_qty              = $old_stk + $quantity;
                }else{
                    $stk_qty              = $quantity;
                }
                $stock->quantity          = $stk_qty;
                $stock->re_order_quantity = $request['reorderquantity'];
                $stock->re_order_point    = $request['reorderpoint'];
                $stock->updated           = $this->user->id;
                $stock->updated_at        = date('Y-m-d H:i:s');
                $stock->type              = OrderType::CompositionIn;
                $stock->is_default        = 1;
                $stock->is_remove = false;
                $stock->save();
            }

        }
        return true;
    }
    
    private function  removeProductstock($product,$request,$var,$quantity,$stock_alert,$outlet,$stock)
    { 
        if($var != null){
            $outlet_id = $outlet['id'];
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
                    $stock->quantity          = - $quantity;
                }
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
    private function  calculateCompositeQuantity($product,$req,$variant,$quantity,$outlet_id,$stock)
    { 
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
}

