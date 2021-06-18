<?php

namespace App\Requests\Catalogue\Category;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Category;
use DB;

class ToggleCategoryRequest extends BaseRequest{

    public $id;
    public $type;
    public $store_id;
}

class ToggleCategoryRequestValidator{
    public function GetRules(){
        return [
            'name' => [
                'required|integer',
                'required|string',
            ],
        ];
    }
}

class ToggleCategoryRequestHandler {

    public function Serve($request){

        try {
            DB::beginTransaction();

            $category = Category::where(['id' => $request->id, 'store_id' => $request->store_id])->first();

            if($request->type == 'dinein'){
                $category->dinein_display = !$category->dinein_display;
            }else if($request->type == 'pos'){
                $category->pos_display = !$category->pos_display;
            }else if($request->type == 'website'){
                $category->web_display = !$category->web_display;
            }else if($request->type == 'active'){
                $category->active = !$category->active;

//                if(!$category->active){
//                    $category->dinein_display = false;
//                    $category->pos_display = false;
//                    $category->web_display = false;
//                }
            }

            $category->save();

            DB::commit();

            $data = [
                'id' => $category->id,
                'name' => $category->name,
                'parent_category' => isset($category->parent) ? $category->parent->name : '-',
                'sort_order' => $category->sort_order,
                'total_products' => $category->products_count,
                'dinein' => $category->dinein_display,
                'pos' => $category->pos_display,
                'website' => $category->web_display,
                'active' => $category->active,
                'actions' => '',
            ];

            return new Response(true, $data,null,null,\Lang::get( 'toaster.category_toggle'));

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false,null,$ex->getMessage());
        }
    }
}
