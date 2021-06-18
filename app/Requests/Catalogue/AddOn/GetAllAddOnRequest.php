<?php

namespace App\Requests\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Core\DataTableResponse;
use App\Models\AddOn;
use Auth;

class GetAllAddOnRequest extends BaseRequest{
    public $store_id;
}

class GetAllAddOnRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
        $locale = \App::getLocale();

        if(is_null($locale)){
            $locale = 'en';
        }
        // $add_ons = AddOn::with(['items'])->where('store_id',Auth::user()->store_id)->withCount('items')->where('language_key' ,$locale)->orderBy('id','desc')->get();

        // return new Response(true, $add_ons);
        $columns = [
            'name',
            'type',
            'active',
            'count',

        ];

        $params = $request->all();


        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $addon = AddOn::with(['items'])->where([
            'store_id' => Auth::user()->store_id,
            'is_delete' => null,
        ])->withCount('items')->where('language_key' ,$locale)->orderBy('id','desc');

        if(isset($params['search']['value'])
            && !empty($params['search']['value'])
            && strlen($params['search']['value'])
            > 0 ){
            $addon = $addon->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $addon->count(); //
        $addons = $addon->get();
        $addons->transform(function($addon){
            $data = [
                'id' => $addon->id,
                'identifier' => $addon->identifier,
                'name' => $addon->name,
                'type' => $addon->type,
                'active' => $addon->is_active,
                'count' => $addon->items_count,
            ];
            return (object)$data;
        });

        return new DataTableResponse($addons,$totalData);
    }
}
