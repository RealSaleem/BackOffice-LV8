<?php

namespace App\Requests\AddOn;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use App\Models\AddOnItems;
use App\Models\Product;
use App\Models\ProductVariant;


class DeleteItemRequest extends BaseRequest
{

    public $store_id;
    public $item_id;

}

class DeleteItemRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {
//dd($request);
        try {

            $item_id = $request->item_id;

                $Product_item = Product::find($item_id);
                $Product_item->delete();
                $product_variant = ProductVariant::where('product_id', $item_id)->first();
                $product_variant->delete();
                $addonItem = AddOnItems::where('name',$Product_item->name)->first();
                $addonItem->delete();
                $message = \Lang::get('toaster.item_deleted');
                $status = true;




            return new Response($status, $request, null, null, $message);

        } catch (Exception $ex) {
            return new Response(false, null, $ex->getMessage());

        }


    }
}
