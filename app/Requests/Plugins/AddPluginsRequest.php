<?php

namespace App\Requests\Plugins;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Outlet;
use App\Models\Store;
use Auth;
use App\Mail\NewPluginsRegistered;
use Mail;

class AddPluginsRequest extends BaseRequest
{

    public $name;
   
}

class AddPluginsRequestValidator
{
    public function GetRules()
    {
        return [
            'name' => 'required|string|max:45',
            
            

        ];
    }
}

class AddPluginsRequestHandler
{

    public function Serve($request)
    {
       
        $user  = is_null(Auth::user()) ? \App::make('user') : Auth::user();
        $store = Store::where('id', $user->store_id)->first();
      

        $newOutlet              = new Outlet;
        $newOutlet->name        = $request->name;
       
        
        $newOutlet->store_id = $store->id;
        $newOutlet->save();
        
        return new Response(true, $newPlugins);
    }
}
