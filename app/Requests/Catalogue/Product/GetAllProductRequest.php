<?php

namespace App\Requests\Catalogue\Product;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\Product;
use App\Helpers\PriceHelper;
use App\Helpers\VariantStock;
use App\Models\ProductCategories;
use App\Models\ProductSuppliers;
use App\Models\ProductStock;

class GetAllProductRequest extends BaseRequest{
    public $store_id;
}

class GetAllProductRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	
        $columns = [
            'id',
            'name',
            'sku',
            'stock',
            'price',
            'stock',
            'image',
            'barcode',
            'variants_count',
            'dinein',
            'pos',
            'website',
            'active',
            'actions',            
        ];

        $params = $request->all();

        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [ 
            'store_id' => $request->store_id, 
        ];

        $productsObj = Product::with(['product_variants','product_images', 'product_stock'])->where($where)->whereNull('deleted_at');

        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {            
            $productsObj = $productsObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        //filter by name or SKU 
        if(isset($params['filter_name']) && !empty($params['filter_name']) && strlen($params['filter_name']) > 0 )
        {            
            $productsObj = $productsObj->where('name','LIKE',"%{$params['filter_name']}%");
        }

        //filter by brand
        if(isset($params['filter_brand']) && !empty($params['filter_brand']) && strlen($params['filter_brand']) > 0 )
        {            
            $productsObj = $productsObj->where('brand_id', $params['filter_brand']);
        }

        // filter by product categories
        if(isset($params['filter_category']) && !empty($params['filter_category']) && strlen($params['filter_category']) > 0){
            $relashions = ProductCategories::where(['category_id' => $params['filter_category']])->get();
            $ids = array_column($relashions->toArray(),'product_id');

            $productsObj = $productsObj->whereIn('id', $ids);        
        }

        // filter by product suppliers
        if(isset($params['filter_supplier']) && !empty($params['filter_supplier']) && strlen($params['filter_supplier']) > 0){
            $relashions = ProductSuppliers::where(['supplier_id' => $params['filter_supplier']])->get();
            $ids = array_column($relashions->toArray(),'product_id');

            $productsObj = $productsObj->whereIn('id', $ids);        
        }

        // filter by price range
        if(isset($params['filter_price_range']) && !empty($params['filter_price_range']) && strlen($params['filter_price_range']) > 0){
            // remove white space from price range
            $price_range = str_replace(' ', '', $params['filter_price_range']);

            // remove "-" sign from price range
            $prices = explode('-',$price_range);
            $price_from = $prices[0];
            $price_to   = $prices[1];

            $variants_callback = function($query) use($price_from, $price_to){ 
                return $query->where('retail_price', '>=' , $price_from)->where('retail_price', '<=' , $price_to);
            };

            $productsObj = $productsObj->whereHas('product_variants',$variants_callback);
                
        }

        // filter by stock range
        if(isset($params['filter_stock_range']) && !empty($params['filter_stock_range']) && strlen($params['filter_stock_range']) > 0){
            // remove white space from price range
            $stock_range = str_replace(' ', '', $params['filter_stock_range']);

            // remove "-" sign from price range
            $stock = explode('-',$stock_range);
            $stock_from = $stock[0];
            $stock_to   = $stock[1];

            $variants_callback = function($query) use($stock_from, $stock_to){ 
                return $query->where('quantity', '>=' , $stock_from)->where('quantity', '<=' , $stock_to);
            };

            $productsObj = $productsObj->whereHas('product_stock',$variants_callback);
                
        }

        $totalData = $productsObj->count(); // 
        $products = $productsObj->offset($start)->limit($limit)->orderBy($order,$dir)->where('is_item',0)->get();
        
        $outlets = \Auth::user()->store->outlets->where('is_active' , 1);
        $threshold = \Auth::user()->store->stock_threshold;

        $products->transform(function($product) use($outlets,$threshold){

            $total_variants = 0;
            $image = '';

            $variant = $product->product_variants->first();

            if(sizeof($product->product_images) > 0){
                $image = $product->product_images->first()->url;
            }else{
                $image = url('img/image-not-available.jpg');
            }

            if($variant){
                $barcode =  $variant->barcode;
                $sku =  $variant->sku;
                $price = PriceHelper::number_format($variant->retail_price);
                $price_without_currency = $variant->retail_price;
                $stock = VariantStock::stock($variant->id);
                $variants = [];


                if($product->has_variant){
                    $total_variants = sizeof($product->product_variants);

                    foreach($product->product_variants as $variant){
                        $row = [
                            'name' => sprintf('%s %s %s',$variant->attribute_value_1,$variant->attribute_value_2,$variant->attribute_value_3),
                            'price' => PriceHelper::number_format($variant->retail_price),
                            'sku' => $variant->sku,
                            'barcode' => $variant->barcode,
                            'active' => $variant->active,
                            'stock' => VariantStock::stock($variant->id)
                        ];

                        $stocks = [];
            
                        foreach($outlets as $outlet){
                            array_push($stocks,VariantStock::count($outlet->id,$variant->id));
                        }

                        $row['stocks'] = $stocks;
                        unset($stocks);

                        array_push($variants,$row);
                    }

                }else{

                    $variant = $product->product_variants->first();
                    
                    $row = [
                        'name' => sprintf('%s %s %s',$variant->attribute_value_1,$variant->attribute_value_2,$variant->attribute_value_3),
                        'price' => PriceHelper::number_format($variant->retail_price),
                        'sku' => $variant->sku,
                        'barcode' => $variant->barcode,
                        'active' => $variant->active,
                        'stock' => VariantStock::stock($variant->id)
                    ];

                    $stocks = [];
        
                    foreach($outlets as $outlet){
                        array_push($stocks,VariantStock::count($outlet->id,$variant->id));
                    }

                    $row['stocks'] = $stocks;

                    array_push($variants,$row);
                }

                $data = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'stock' => $stock,
                    'image' => $image,
                    'sku' => $sku,
                    'barcode' => $barcode,
                    'variants_count' => $total_variants,
                    'dinein' => $product->dinein_display,
                    'pos' => $product->is_featured,
                    'website' => $product->web_display,
                    'active' => $product->active,
                    'actions' => '',
                    'total_value' => $price_without_currency * $stock,
                    'outlets' => array_column($outlets->toArray(),'name'),
                    'variants' => $variants,
                    'threshold' => $threshold
                ];
               
                return (object)$data;
                
            }else{
                 $data = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' =>'N/A',
                    'stock' => 0,
                    'image' => $image,
                    'sku' => 'N/A',
                    'barcode' => 'N/A',
                    'variants_count' => 0,
                    'dinein' => $product->dinein_display,
                    'pos' => $product->is_featured,
                    'website' => $product->web_display,
                    'active' => $product->active,
                    'actions' => '',
                    'total_value' => 0,
                    'outlets' => array_column($outlets->toArray(),'name'),
                    'variants' => [],
                    'threshold' => 0
                ];
               
                return (object)$data;
            }

        });

        return new DataTableResponse($products,$totalData);
    }
} 