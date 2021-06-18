<?php

namespace App\Requests\Catalogue\Brands;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\Brand;

class GetAllBrandsRequest extends BaseRequest{
    public $store_id;
}

class GetAllBrandsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){

        $columns = [
            'name',
            'number_of_products',
            'active',
            'actions',
            'feature',
            'image'
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

        $brandsObj = Brand::where($where);

        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {
            $brandsObj = $brandsObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $brandsObj->count(); //
        $brands = $brandsObj->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        //->withCount('products')

        $brands->transform(function($brand){

            $image = '';

            if(sizeof($brand->images) > 0){
                $image = $brand->images->first()->url;
            }else{
                $image = url('img/image-not-available.jpg');
            }

            $data = [
                'id' => $brand->id,
                'name' => $brand->name,
                'number_of_products' => isset($brand->product) ?  $brand->product->count() : 0,//$brand->nop,
                'active' => $brand->active,
                'feature' =>$brand->is_featured,
                'actions' => '',
                'image' => $image
            ];

            return (object)$data;
        });

        return new DataTableResponse($brands,$totalData);
    }
}
