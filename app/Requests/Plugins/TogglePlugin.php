<?php

namespace App\Requests\Plugins;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Store;

class TogglePlugin extends BaseRequest{
	public $id;
}

class TogglePluginHandler {

    public function Serve($request)
    {
    	try{
	    	$id = $request->id;

	        $store = Store::with('plugins')->find(\Auth::user()->store_id);
	        foreach ($store->plugins as $plugin) {
	        	if($plugin->id == $id){
	        		\Auth::user()->store->plugins()->updateExistingPivot($id,['active' => !$plugin->pivot->active]);
	        	}
	        }

	        return new Response(true);

    	}catch(Exception $ex){
    		return new Response(false,null,null,$ex->getMessage());
    	}
    }
} 