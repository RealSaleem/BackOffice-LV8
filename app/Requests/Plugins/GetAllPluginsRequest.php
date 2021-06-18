<?php

namespace App\Requests\Plugins;

use App\Core\BaseRequest as BaseRequest;
use App\Core\DataTableResponse;
use App\Models\Plugin;
use App\Models\Store;


class GetAllPluginsRequest extends BaseRequest{
    public $store_id;
}

class GetAllPluginsRequestHandler {

    public function __construct(){
    }

    public function Serve($request){


        $columns = [
            'name',
            'actions',
        ];

        $params = $request->all();

        $limit = $params['length'];
        $start = $params['start'];

        $order = $columns[$params['order'][0]['column']];
        $dir = $params['order'][0]['dir'];

        $where = [
            'store_id' => $request->store_id,
        ];

        // $pluginsObj = Plugin::where($where);

        $pluginsObj = Plugin::with('pluginsStore')->whereHas('pluginsStore', function($query) use($request){
            $query->where('store_id', $request->store_id);
        });

        if(isset($params['search']['value']) && !empty($params['search']['value']) && strlen($params['search']['value']) > 0 )
        {
            $pluginsObj = $pluginsObj->where('name','LIKE',"%{$params['search']['value']}%");
        }

        $totalData = $pluginsObj->count(); //
        $plugins = $pluginsObj->offset($start)->limit($limit)->orderBy($order,$dir)->get();
        //->withCount('products')

        // overwrite because i need just my own store pluging, currently it was givin me all
        $store = Store::with('plugins')->where('id', $request->store_id)->first();
        $plugins = $store->plugins;

        $plugins->transform(function($plugin,$store_id){
            $data = [
                'id' => $plugin->id,
                'name' => $plugin->name,
                'actions' => '',
                'slug' => $plugin->slug,
                'active' => $plugin->pivot->active
            ];

            return (object)$data;
        });

        return new DataTableResponse($plugins,$totalData);
    }
}
