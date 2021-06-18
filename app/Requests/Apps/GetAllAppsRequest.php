<?php

namespace App\Requests\Apps;

use App\Core\BaseRequest as BaseRequest;
use App\Models\Apps;
use Auth;

class GetAllAppsRequest extends BaseRequest{

}

class GetAllAppsRequestHandler {

    public function Serve($request){
        $apps = Apps::with('purchased_apps')->get();

        $apps->each(function($app){
        	$app->is_purchased = sizeof($app->purchased_apps) > 0 ? true : false;

        });
         //dd($apps);
        return $apps;
    }
} 