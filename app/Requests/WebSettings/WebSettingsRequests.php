<?php

namespace App\Requests\WebSettings;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Store;
use App\Models\WebSettings;

class WebSettingsRequests extends BaseRequest
{
    public $store_id;
}


class WebSettingsRequestsHandler
{
    public function Serve($request)
    {
        $websettings = WebSettings::with(['banners','links'])->where('store_id',$request->store_id)->first();

        if(is_null($websettings)){
        	$websettings = new WebSettings();
            $websettings->logo = null;
            $websettings->show_slider = true;
            $websettings->show_featured = false;
            $websettings->show_top_seller = false;
            $websettings->about_us = null;
            $websettings->contact_us = null;
        }

        return new Response(true,$websettings);
    }
}
