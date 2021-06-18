<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\ProductImage;
use App\Models\ProductCategories;
use App\Models\ProductVariant;

use Auth;
use Request;
use View;

class GetProductListRequest extends BaseRequest{
    public $name;
    public $category_id;
    public $supplier_id;
    public $brand_id;
    public $tag;
    public $status;
    public $web;
    public $dinein;
    public $is_item;
}

class GetProductListRequestValidatosr {
    public function IsValid($request){
        return new Response(true);
    }
}

class GetProductListRequestHandler {

    public function __construct(){
    }

    public function Serve($request)
	{
		$login_user = is_null(Auth::user())? \App::make('user') : Auth::user();
		$query = Product::where('store_id' , $login_user->store_id)
						->with(['tags','product_variants' => function($query){
							return $query->with('product_stock');},'brand','supplier','category','user']);

		if(isset($request->status) && $request->status == 0){
			$query->where('active',false);
		}

		if(isset($request->status) && $request->status == 1){
			$query->where('active',true);
		}

		if(isset($request->web) && $request->web == 0){
			$query->where('active',true);
			$query->where('web_display',false);
		}

		if(isset($request->web) && $request->web == 1){
			$query->where('active',true);
			$query->where('web_display',true);
		}	
		if(isset($request->dinein) && $request->dinein == 0){
			$query->where('active',true);
			$query->where('dinein_display',false);
		}

		if(isset($request->dinein) && $request->dinein == 1){
			$query->where('active',true);
			$query->where('dinein_display',true);
		}

		if(!is_null($request->category_id) && $request->category_id > 0){
            $relashions = ProductCategories::where(['category_id' => $request->category_id])->get();
            $ids = array_column($relashions->toArray(),'product_id');
            $query->whereIn('id',$ids);			
		}

		if(!is_null($request->is_item) && $request->is_item){
			$query->where('is_item',1);
		}else{
			$query->where('is_item',0);
		}

		if(!is_null($request->brand_id) && $request->brand_id > 0){
			$query->where('brand_id',$request->brand_id);
		}

		if(!is_null($request->supplier_id) && $request->supplier_id > 0){
			$query->where('supplier_id',$request->supplier_id);
		}

        if(!is_null($request->name)){
        	$relashions = ProductVariant::where('store_id' , $login_user->store_id)->where('sku', 'like', '%'.$request->name.'%')->orWhere('name', 'like', '%'.$request->name.'%')->get();
            $ids = array_column($relashions->toArray(),'product_id');
            $query->whereIn('id',$ids);
            
		}


		if($this->isMobile()){
			$query->where('active',true);
			$query->orderBy('id');
			$productsData =$query->paginate(100);
		}
		else{
			$query->orderBy('id', 'desc');
			$productsData =$query->paginate(10);
		}

		
		$dataHolder = $productsData->toArray();
		
		$params = Request::except('page');
		$queryString = [];

		if(count($params) > 0){
			foreach($params as $key => $value){
				array_push($queryString,sprintf('%s=%s',$key,$value));
			}
		}

		if(count($queryString) > 0){
			$dataHolder['next_page_url'] = isset($dataHolder['next_page_url']) ? sprintf('%s&%s',$dataHolder['next_page_url'],join('&',$queryString)) : null;
			$dataHolder['prev_page_url'] = isset($dataHolder['prev_page_url']) ? sprintf('%s&%s',$dataHolder['prev_page_url'],join('&',$queryString)) : null;
		}

		// Uncomment for multiple links of pagination
		//$data['pagination'] = View::make('sales_history.pagination')->with('pagination', $productsData)->render();
		//$data['data'] = $dataHolder;

		foreach ($dataHolder['data'] as &$product) {
			if(is_null($product['image'])){
				$img = ProductImage::where('product_id',$product['id'])->first();
				$imges = ProductImage::where('product_id',$product['id'])->get();

				if($img){
					$product['image'] = $img->url;
				   $product['images'] = $imges;

				}
			}
			foreach ($product['product_variants'] as &$variant) {
				$variant['product_name'] = $product['name'];
			}
		}

        return new Response(true, $dataHolder);
    }

    private function isMobile() {
	    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
}