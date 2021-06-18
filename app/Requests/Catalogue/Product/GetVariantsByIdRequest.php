<?php

namespace App\Requests\Catalogue\Product;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product as Product;
use App\Models\ProductVariant;
use App\Models\ProductStock;
use App\Models\Outlet;
use App\Helpers\VariantStock;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Auth;
class GetVariantsByIdRequest extends BaseRequest{

    public $id;

}
class GetVariantsByIdRequestHandler {

    public function Serve($request){

            $outlets = Auth::user()->store->outlets->toArray();

            $variants = ProductVariant::where([['product_id',$request->id],['store_id',Auth::user()->store_id]])->get();
            $modified_variants = [];

            foreach ($variants as $variant) {

                $variant_outlets = [];

                foreach ($outlets as $outlet) {

                    $where = [
                        ['outlet_id','=',$outlet['id']],
                        ['product_id','=',$variant->product_id],
                        ['variant_id','=',$variant->id],
                        ['is_default','=',1],
                    ];

                    $product_stock = ProductStock::where($where)->first();

                    if(!is_null($product_stock)){
                        $outlet['quantity'] =  (float)VariantStock::count($outlet['id'],$variant->id);
                        $outlet['re_order_point'] = $product_stock->re_order_point;
                        $outlet['re_order_quantity'] = $product_stock->re_order_quantity;
                        $outlet['supply_price'] =  (float)$product_stock->cost_price;
                        $outlet['margin'] = $product_stock->margin;
                    }else{
                        $outlet['quantity'] = VariantStock::count($outlet['id'],$variant->id);
                        $outlet['re_order_point'] = 0;
                        $outlet['re_order_quantity'] = 0;
                        $outlet['supply_price'] = 0;
                        $outlet['margin'] = 0;
                    }

                    array_push($variant_outlets, $outlet);
                }
            // dd($variant_outlets);

                $variant->outlets = $variant_outlets;

                $translations = Auth::user()->store->languages->toArray();

                //dd($translations);

                $modified_translations = [];

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
                            ['product_id',$request->id],
                            ['language_key',$trans['short_name']]
                        ])->orderBy('id')->get()->toArray();

                        if(!$is_default_set){
                           $attribute_1_default = isset($attributes[0]['values']) ? array_column($attributes[0]['values'],'name') : [];
                           $attribute_2_default = isset($attributes[1]['values']) ? array_column($attributes[1]['values'],'name') : [];
                           $attribute_3_default = isset($attributes[2]['values']) ? array_column($attributes[2]['values'],'name') : [];
                           $is_default_set = true;
                        }

                        $index = 1;

                        foreach ($attributes as $attribute) {

                            $trans['attribute_'.$attr]           = $attribute['name'];

                            $attribute_values = $attribute['values'];

                            $values = array_column($attribute_values,'name');

                            $group = [];

                            if($index == 1){
                                $group = $this->merg_arrays($attribute_1_default,$values);
                                $trans['attr1_values']= $group;
                            }else if($index == 2){
                                $group = $this->merg_arrays($attribute_2_default,$values);
                                $trans['attr2_values'] = $group;
                            }else{
                                $group = $this->merg_arrays($attribute_3_default,$values);
                                $trans['attr3_values'] = $group;
                            }

                            $index++;

                            $attr++;
                        }

                        $attr = 1;

                        array_push($modified_translations, $trans);
                    }
                }

                $variant->attribute = $modified_translations;
                array_push($modified_variants,$variant);
            }

            return new Response(true, $modified_variants);

    }
    public function merg_arrays($arr1, $arr2){
        if(sizeof($arr1) == sizeof($arr2) && sizeof($arr1) > 0){
            $result = [];
            for ($i=0; $i < sizeof($arr1); $i++) {
                array_push($result, ['key'=>$arr1[$i],'value'=>$arr2[$i]]);
            }
            return $result;
        }else{
            return [];
        }

    }
}
