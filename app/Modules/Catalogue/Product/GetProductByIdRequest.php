<?php

namespace App\Modules\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as Product;
use Auth;
class GetProductByIdRequest extends BaseRequest{
   
    public $id;
    
}
class GetProductByIdRequestHandler {

    public function Serve($request){
        $product     = Product::with([
            'product_categories',
            'product_suppliers',
            'product_ralated',
            'product_images',
            'product_stock',
            'product_verients',
            'composite_products',
            'transalation'
        ])->find($request->id);

        $product_template = [
            'product_categories'    => $product->product_categories->toArray(),
            'product_suppliers'     => $product->product_suppliers->toArray(),
            'product_ralated'       => $product->product_ralated->toArray(),
            'product_images'        => $product->product_images->toArray(),
            'product_stock'         => $product->product_stock->toArray(),
            'product_verients'      => $product->product_verients->toArray(),
            'composite_products'    => $product->composite_products->toArray(),
            'transalation'          => $product->transalation->toArray(),
            // 'category_id'           => '',
            // 'supplier_id'           => '',
            // 'related_id'            => '',
            // 'branches'              => '',
            'brand_id'              => $product->brand_id,
            'active'                => $product->active,
            'name'                  => $product->name,
            'handle'                => $product->handle,
            'description'           => $product->description,
            'attribute_1'           => $product->attribute_1,
            'attribute_2'           => $product->attribute_2,
            'attribute_3'           => $product->attribute_3,
            'supplier_price'        => $product->supplier_price,
            'markup'                => $product->markup,
            // 'retail_price'          => $product->retail_price,
            'supplier_code'         => $product->supplier_code,
            'sales_account_code'    => $product->sales_account_code,
            'purchase_account_code' => $product->purchase_account_code,
            'tags'                  => $product->tags,
            'has_variant'           => $product->has_variant,
            'variants'              => $product->variants,
            'is_featured'           => $product->is_featured,
            'prefix'                => $product->prefix,
            'is_composite'          => $product->is_composite,
            // 'composite_products'    => $product->composite_products,
            'sku_string'            => $product->sku_string,
            'created'               => $product->created,
            'barcode'               => $product->barcode,
            'dinein_display'        => $product->dinein_display,
            'meta_title'            => $product->meta_title,
            'meta_keywords'         => $product->meta_keywords,
            'meta_description'      => $product->meta_description,
            // 'before_discount_price' => $product->before_discount_price,
            // 'discounted_price'      => $product->discounted_price,
            'is_featured_web'       => $product->is_featured_web,
            'top_seller_web'        => $product->top_seller_web,
            'web_display'           => $product->web_display,
            'sku_custom'            => $product->sku_custom,
            'sku_name'              => $product->sku_name,
            'sku_number'            => $product->sku_number,
            'default_sku_name'      => $product->default_sku_name,
            'sku_type'              => $product->sku_type,
            'is_combo'              => $product->is_combo,
            'allow_subscription'    => $product->allow_subscription,
            'inc_or_exc'            => $product->inc_or_exc,
            'tax_per'               => $product->tax_per,
            'unit'                  => $product->unit,
            'sort_order'                  => $product->sort_order,
        ];

        if(!is_null($product_template['unit'])){

            $product_template['unit_divider'] = ($product_template['unit'] == 'm') ? 100 : 1000;
        }else{
            $product_template['unit_divider'] = 0;
        }

        if(isset($product->product_verients[0])){
            $product_template['retail_price']          = number_format($product->product_verients[0]->retail_price, Auth::user()->store->round_off, '.', '');
            $product_template['before_discount_price'] = number_format($product->product_verients[0]->before_discount_price, Auth::user()->store->round_off, '.', '');
            $product_template['allow_out_of_stock']    = $product->product_verients[0]->allow_out_of_stock;
            $product_template['sku']                   = $product->product_verients[0]->sku;
        }else{
            $product_template['retail_price']          = 0;
            $product_template['before_discount_price'] = 0;
            $product_template['allow_out_of_stock']    = true;
            $product_template['sku']                   = '';
        }

        $transalations = $product->transalation->toArray();
        $ltransalations = Auth::user()->store->languages->toArray();

        if(sizeof($transalations) == sizeof($ltransalations)){
            
            foreach ($transalations as $transalation) 
            {
                $has_seo = 'has_seo_'.$transalation['language_key'];
                $title   = 'title_'.$transalation['language_key'];
                $description = 'description_'.$transalation['language_key'];
                $meta_title = 'meta_title_'.$transalation['language_key'];
                $meta_keywords = 'meta_keywords_'.$transalation['language_key'];
                $meta_description = 'meta_description_'.$transalation['language_key'];

                $product_template[$title] = $transalation['title'];
                $product_template[$description] = $transalation['description'];
                $product_template[$meta_title] = $transalation['meta_title'];
                $product_template[$meta_keywords] = $transalation['meta_keywords'];
                $product_template[$meta_description] = $transalation['meta_description'];

                $product_template[$has_seo] = $transalation['meta_title'] != '' ? 1 : 0;
            }
        }else{

            foreach ($ltransalations as $transalation) 
            {
                $has_seo = 'has_seo_'.$transalation['short_name'];
                $title   = 'title_'.$transalation['short_name'];
                $description = 'description_'.$transalation['short_name'];
                $meta_title = 'meta_title_'.$transalation['short_name'];
                $meta_keywords = 'meta_keywords_'.$transalation['short_name'];
                $meta_description = 'meta_description_'.$transalation['short_name'];

                $trans = array_filter($transalations,function($item) use($transalation){
                    if($item['language_key'] == $transalation['short_name']){
                        return $item;
                    }
                });

                if(count($trans) > 0){
                      $row = array_first($trans);  
                    
                    $product_template[$title] = $row['title'];
                    $product_template[$description] = $row['description'];
                    $product_template[$meta_title] = $row['meta_title'];
                    $product_template[$meta_keywords] = $row['meta_keywords'];
                    $product_template[$meta_description] = $row['meta_description'];

                }else{
                    $product_template[$title] = $product->name;
                    $product_template[$description] = '';
                    $product_template[$meta_title] = '';
                    $product_template[$meta_keywords] = '';
                    $product_template[$meta_description] = '';
                }


                $product_template[$has_seo] = $meta_title != '' ? 1 : 0;
            }                
        }        

    	return new Response(true, $product_template);
    }
} 
