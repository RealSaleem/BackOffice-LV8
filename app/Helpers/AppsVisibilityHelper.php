<?php
namespace App\Helpers;
use Auth;
 
class AppsVisibilityHelper {
    
    /**
     * @param int $user_id User-id
     * 
     * @return string
    */

    public static function get()
    {
        $apps = Auth::user()->apps;

        $data = [];

        $data['has_pos'] = true;
        $data['has_website'] = true;
        $data['has_dinein'] = true;
        $data['has_dinein_catalogue'] = true;
        $data['has_mobile'] = true;
        $data['has_pos_tablet'] = true;

        return $data;

        /*
        1  POS               (NULL)       
        2  Website           (NULL)       
        3  Dinein            (NULL)       
        4  Dinein Catalogue  (NULL)       
        5  Mobile            (NULL)       
        6  POS Tablet        (NULL)  
        */  
	}
}