<?php

namespace App\Requests\Catalogue\Category;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\Category;

class GetAllCategoryRequest extends BaseRequest{
    public $store_id;
}

class GetAllCategoryRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $columns = [
            'name',
            'parent_category',
            'sort_order',
            'image',
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
            'is_deleted' => 0,
        ];

        $categoryObj = Category::with('images')->where($where);

        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {
            $categoryObj = $categoryObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $categoryObj->count(); //
        $categories = $categoryObj->withCount('product_categories')->offset($start)->limit($limit)->orderBy($order,$dir)->get();

        $categories->transform(function($category){

            $image = '';

            if(count($category->images) > 0){
                $image = $category->images->first()->url;
            }else{
                $image = url('img/image-not-available.jpg');
            }

            $data = [
                'id' => $category->id,
                'name' => $category->name,
                'parent_category' => isset($category->parent) ? $category->parent->name : '-',
                'sort_order' => $category->sort_order,
                'total_products' => $category->product_categories_count,
                'dinein' => $category->dinein_display,
                'pos' => $category->pos_display,
                'website' => $category->web_display,
                'active' => $category->active,
                'actions' => '',
                'image' => $image
            ];

            return (object)$data;
        });

        return new DataTableResponse($categories,$totalData);
    }
}
