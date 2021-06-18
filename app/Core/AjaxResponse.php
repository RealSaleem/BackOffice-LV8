<?php 

namespace App\Core;

class AjaxResponse  {

	public static function ToResult( $result ){
		$response = array();
		$response['Success']	=	$result->IsValid;
		$response['Payload']	=	$result->Payload;
		$response['Message']	= 	$result->Exception;
		return json_encode($response);
	}

}
