<?php

namespace App\Requests\Catalogue\Category;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category;
use DB;

class BulkCategoryRequest extends BaseRequest
{
    public $store_id;
    public $type;
    public $categories;
    public $action;
}

class BulkCategoryRequestHandler
{

    public function __construct()
    {
    }

    public function Serve($request)
    {
//        dd($request);
        try {
            DB::beginTransaction();

            $categoryObj = Category::with('products')->where(['store_id' => $request->store_id])->whereIn('id', $request->categories);

            $is_active = $request->action === 'true' ? 1 : 0;
//dd($categoryObj->withCount('products')->get());

            if ($request->type == 'is_featured') {
                $categoryObj->update(['is_featured' => $is_active]);
            } else if ($request->type == 'pos') {
                $categoryObj->update(['pos_display' => $is_active]);
            } else if ($request->type == 'dinein') {
                $categoryObj->update(['dinein_display' => $is_active]);
            } else if ($request->type == 'website') {
                $categoryObj->update(['web_display' => $is_active]);
            } else if ($request->type == 'active') {
                $categoryObj->update(['active' => $is_active]);
            } else if ($request->type == 'delete') {

                foreach ($categoryObj->withCount('products')->get() as $key => $category) {
                    if($category->products_count > 0){
                        return new Response(false, null, null, null, \Lang::get('toaster.category_product_exist'));
                        continue;

                    }else{
                        $category->is_deleted = 1;
                        $category->deleted_by = $request->user->id;
                        $category->save();
                        $category->delete();
                        return new Response(false, null, null, null, \Lang::get('toaster.category_deleted'));

                    }

                }






//                    if ($category->products_count == 0) {
//                        $category->is_deleted = 1;
//                        $category->updated = $request->user->id;
//                        $category->save();
//                        return new Response(false, null, null, null, \Lang::get('toaster.category_deleted'));
//                    } else {
//                        return new Response(false, null, null, null, \Lang::get('toaster.category_softdeleted'));
//
//                    }
                }


            DB::commit();

//            $message = $request->type == 'delete' ? \Lang::get('toaster.category_bulk_deleted') : \Lang::get('toaster.category_updated');

            return new Response(true, null, null, null, \Lang::get('toaster.category_updated'));

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false, null, $ex->getMessage());
        }
    }
}
