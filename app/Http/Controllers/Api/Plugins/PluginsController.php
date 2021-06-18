<?php

namespace App\Http\Controllers\Api\Plugins;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\Plugins\GetAllPluginsRequest;
use App\Core\Response;
use App\Models\Loyalty;
use Auth;


class PluginsController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function getplugins(GetAllPluginsRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

  public function addPlugins(AddPluginsRequest $request)

    {
        $response = $this->RequestExecutor->execute($request);
        $response->Message = \Lang::get('outlets.outlets_added_successfully');
         return response()->json($response);
    }


    public function updateOutlets(EditOutletsRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        $response->Message = \Lang::get('outlets.outlets_updated_successfully');
        return response()->json($response);
    }

    public function UpdatePluginSetting(Request $request){
        $plugin_id  = $request->input('plugin_id');
        $plugin_name = $request->input('plugin_name');

        $response = null;
        if($plugin_name == 'loyalty'){
            $pluginSetting = Loyalty::where('id', $plugin_id)->first();

            $pluginSetting->cap_amount = $request->input('cap_amount');
            $pluginSetting->points = $request->input('points');
            $pluginSetting->redeem_rate = $request->input('redeem_rate');
            $pluginSetting->max_reword_discount= $request->input('max_reword_discount');
            $pluginSetting->expire_after = $request->input('expire_after');
            $pluginSetting->save();

            $response = new Response(true,$pluginSetting);
        }

        $response->Message = "Record has been updated successfully";
        return response()->json($response);
    }
}





