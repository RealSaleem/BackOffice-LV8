<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
// use App\Models\Product;
use Auth;

class GetEmptyProductRequest extends BaseRequest
{

    
}
class GetEmptyProductRequestHandler {

    public function Serve($request){
       $languages = Auth::user()->store->languages->toArray();

        $product_template = [
            'id'                    => '',
            'category_id'           => '',
            'supplier_id'           => '',
            'related_id'            => '',
            'branches'              => '',
            'brand_id'              => '',
            'active'                => '',
            'name'                  => '',
            'handle'                => '',
            'description'           => '',
            'product_images'        => [],
            'attribute_1'           => '',
            'attribute_2'           => '',
            'attribute_3'           => '',
            'supplier_price'        => '',
            'markup'                => '',
            'retail_price'          => '',
            'sku'                   => '',
            'supplier_code'         => '',
            'sales_account_code'    => '',
            'purchase_account_code' => '',
            'tags'                  => '',
            'has_variant'           => '',
            'variants'              => '',
            'is_featured'           => 1,
            'prefix'                => '',
            'is_composite'          => '',
            'composite_products'    => [],
            'sku_string'            => '',
            'created'               => '',
            'barcode'               => '',
            'dinein_display'        => '',
            'meta_title'            => '',
            'meta_keywords'         => '',
            'meta_description'      => '',
            'before_discount_price' => '',
            'discounted_price'      => '',
            'is_featured_web'       => '',
            'top_seller_web'        => '',
            'web_display'           => '',
            'sku_custom'            => '',
            'sku_name'              => '',
            'sku_number'            => '',
            'default_sku_name'      => '',
            'sku_type'              => '',
            'allow_out_of_stock'    => '',
            'is_combo'              => '',
            'product_images'        => [],
            'product_stock'         => [],
            'inc_or_exc'            => '',
            'tax_per'               => '',
            'unit'                  => '',
            'sort_order'                  => '',
            'allow_subscription'    => false
        ];
        // dd($product_template);

        $languages =  Auth::user()->store->languages->toArray();

        foreach ($languages as $language) 
        {
            $product_template['has_seo_'.$language['short_name']] = 0;
            $product_template['title_'.$language['short_name']] = '';
            $product_template['description_'.$language['short_name']] = '';
            $product_template['meta_title_'.$language['short_name']] = '';
            $product_template['meta_keywords_'.$language['short_name']] = '';
            $product_template['meta_description_'.$language['short_name']] = '';
        }
    	return new Response(true, $product_template);
    }
} 