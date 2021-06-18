<?php

namespace App\ViewModels;

use App\Core\RequestExecutor;
use App\ViewModels\BaseViewModel;
use App\Requests\Catalogue\Product\GetProductByIdRequest;
use App\Requests\Catalogue\Product\GetEmptyProductRequest;
use App\Requests\Catalogue\AddOn\GetEmptyAddOnRequest;
use App\Models\Product;
use App\Models\ProductStock;
use App\Helpers\VariantStock;
use App\Requests\Catalogue\AddOn\GetAllItemsRequest;
use Auth;

class ProductFormViewModel extends BaseViewModel{

    public $product;
    public $languages = [];
    public $outlets = [];
    public $brands = [];
    public $categories = [];
    public $suppliers = [];
    public $addons = [];
    public $addon;
    public $options = [];
    public $related_products = [];
    public $currency;
    public $items;

    /*
            $data = [
            'add_ons'    => $add_on_response->Payload,
            'add_on' => $oddonResponse->Payload,
            'add_ons_related'    => [],
            'currency'    => $currency,
            'product'      => $product,
            'category_ids' => [],
            'supplier_ids' => [],
            'related_ids' => [],
            'sku'          => $sku,
            'title'        => 'New Product',
            'save_title'   => 'Save',
            'route'        => route('product.store'),
            'is_edit'      => false,
            'active'       => 1,
            'composite_products' =>[],
            'id'           => '',
            'sku_by_name'  => $sku_by_name,
            'variants_count' => 0,
            'store_currency' =>Auth::user()->store->default_currency,
            'salestax'  => $salestax,
            'show_unit'     =>  $show_unit
        ];
    */

    public static function load($id = 0)
    {
        $model = new ProductFormViewModel();

        $store = Auth::user()->store;

        $store->load(['brands' => function ($query) {
            $query->where('is_deleted', false);
        }]);

        $store->load(['categories' => function ($query) {
            $query->where('is_deleted', false)->orderBy('id','desc');
        }]);

        $addons = [];
        $options = [];
        $suppliers = [];

        if(!is_null($store->addons) && count($store->addons) > 0){
            $addons = array_filter($store->addons->toArray(),function($addon){
                if($addon['type'] == 'add_on' && $addon['is_delete'] == null && $addon['language_key'] == \App::getLocale()){
                    return $addon;
                }
            });

            $options = array_filter($store->addons->toArray(),function($addon){
                if($addon['type'] == 'option' && $addon['language_key'] == \App::getLocale()){
                    return $addon;
                }
            });
        }
//        dd($store->suppliers);
        if(!is_null($store->suppliers) && count($store->suppliers) > 0){
            $suppliers = array_filter($store->suppliers->toArray(),function($supplier){
                if($supplier['is_deleted'] == null){
                    return $supplier;
                }
            });

        }









        $store->load(['products' => function ($query) {
            $query->whereNull('deleted_by')->where(['active'=>true]);
        }]);


        $model->languages = $store->languages->toArray();

        $model->currency = $store->default_currency;
        $model->brands = $store->brands;
        $model->categories = $store->categories;
        $model->related_products = $store->products;
        $model->addons = $addons;
        $model->options = $options;
//        $model->suppliers = $store->suppliers;
        $model->suppliers = $suppliers;
        $model->sku = ($store->sku_generation == 0) ? "" : $store->current_sequence_number;
        $model->sku_by_name = ($store->sku_generation == 0);
        $model->composite_products = [];
        $model->show_unit = false;
        $model->salestax = null;
        $model->active = true;
        $model->has_addons = $store->industry_id == 3;

        $requestExecutor = new RequestExecutor();
        $request = new GetEmptyAddOnRequest();
        $response = $requestExecutor->execute($request);
        $model->addon = $response->Payload;

        $itemRequest = new GetAllItemsRequest();
        $itemResponse = $requestExecutor->execute($itemRequest);
        $items = $itemResponse->Payload;

        $model->items = $items;

        if($id > 0){
            $model->title = __('backoffice.edit_products');
            $model->button_title = __('backoffice.update');
            $model->route = route('api.update.product');

            $request = new GetProductByIdRequest();
            $request->id = $id;
            $response = $requestExecutor->execute($request);
            $model->product = $response->Payload;
        }else{
            $model->title = __('backoffice.add_products');
            $model->button_title = __('backoffice.add');
            $model->route = route('api.add.product');

            $request = new GetEmptyProductRequest();
            $response = $requestExecutor->execute($request);
            $model->product = $response->Payload;
        }

        $model->edit_mode = ($id > 0);

        $outlets = [];

        $variant_id = isset($model->product['product_verients']) && isset($model->product['product_verients'][0]) ? $model->product['product_verients'][0]['id'] : 0;

        foreach($store->outlets as $outlet){
            if($outlet->is_active){

                $quantity = VariantStock::count($outlet->id,$variant_id);
                $productStock = ProductStock::where(['outlet_id' => $outlet->id , 'variant_id' => $variant_id , 'is_default' => 1])->first();

                if(is_null($productStock)){
                    $productStock = new ProductStock();
                }

                array_push($outlets,[
                    'id'                  => '',
                    'outlet_id'           =>$outlet->id,
                    'name'                => $outlet->name,
                    'quantity'            => $quantity,
                    're_order_point'      => $productStock->re_order_point,
                    're_order_quantity'   => $productStock->re_order_quantity,
                    'supply_price'        => $productStock->cost_price,
                    'margin'              => $productStock->margin
                ]);
            }
        }

        $model->outlets = $outlets;

        return [ 'model' => $model ];
    }
}
