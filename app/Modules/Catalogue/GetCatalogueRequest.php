<?php

namespace App\Modules\Catalogue;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Helpers\VariantStock as VariantStock;
use App\Models\Category as Category;
use App\Models\Product as Product;
use App\Models\ProductVariant as ProductVariant;
use App\Models\User;

class GetCatalogueRequest extends BaseRequest {

	public $store_id;
	public $user_id;

}
class GetCatalogueRequestHandler {

	public function Serve($request) {

		try {

			/*
				        |--------------------------------------------------------------------------
				        | Category
				        |--------------------------------------------------------------------------
			*/
			$category = Category::select('id', 'name', 'parent_id')
				->where([
					'store_id' => $request->store_id,
					'is_deleted' => 0])
				->get()->toArray();

			/*
				        |--------------------------------------------------------------------------
				        | Product
				        |--------------------------------------------------------------------------
			*/
			$products = Product::select(['id', 'name', 'description', 'category_id'])
				->with(['product_images','product_variants' ,'category' => function ($q) {
					return $q->select('id', 'name');
				}])
				->where([
					'store_id' => $request->store_id,
					'active' => 1])
				->get();

			$products->transform(function($product){
				$image = $product->product_images->first();
				
				if(isset($image)){
					$product->image = $image->url;
				}

				$variant = $product->product_variants->first();

				if(isset($variant)){
					$product->retail_price = $variant->retail_price;
				}

				unset($product->product_images);
				unset($product->product_variants);
				unset($product->category);

				return $product;
			});

			// Final Array
			$catalogue = array(
				'category' => $category,
				'product' => $products,
			);

			// dd($catalogue);

			return new Response(true, $catalogue);
		} catch (Exception $ex) {
			return new Response(false, null, null, $ex->getMessage());
		}
	}
}