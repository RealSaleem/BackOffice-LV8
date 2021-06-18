<?php
namespace App\Helpers;
 
class CustomUrl {
    
    /**
     * @param int $user_id User-id
     * 
     * @return string
    */

    public static function asset($path, $secure = null)
    {
       if ( config('app.APP_ENV') == 'local') {
            return app('url')->asset($path, $secure);
	   }else{
	   		return app('url')->asset('public/'.$path, $secure);
	   }
	}
}