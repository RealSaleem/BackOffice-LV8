<?php 

namespace App\Helpers;
use \Illuminate\Support\Facades\Auth;
class StoreId
{
	public $store_id;
	public function __construct(){
	}

	public static function getId(){
		// $store_id = \Illuminate\Support\Facades\Auth::user()->store_id;
		$store_id = 3;
		return $store_id;
	}
}

?>