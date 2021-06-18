<?php

namespace App\Requests\Catalogue\AddOn;
use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use Auth;

class GetEmptyAddOnRequest extends BaseRequest{
   
}

class GetEmptyAddOnRequestHandler {

    public function __construct(){
    }

    public function Serve($request){
    	$languages = Auth::User()->store->languages->toArray();

      $item_template = [
        'id' => 0,
        'price'=> '',
        'name_en'=> '',
      ];
      
      foreach ($languages as $language) {
        $item_template['name_'.$language['short_name']]       = '';
      }

    	$add_on = [
        'id' => 0,
        'is_active' => 1,
        'min' => 0,
        'max' => 0,
        'type'=> 'add_on',
        'add_on_items'=> [$item_template],
        'limit' => 1,
        'code' => ''
      ];

      foreach ($languages as $language) {
        $add_on['name_'.$language['short_name']]       = '';
      } 
         
      return new Response(true, $add_on);
    }
} 