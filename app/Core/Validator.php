<?php 

namespace App\Core;
use ReflectionClass;
use ReflectionProperty;
use App\Core\Response;

class Validator {

	public function IsValid($request)
	{
		$class = get_class($request);
		$handlerClass = new \ReflectionClass(str_replace("Models","Actions",$class)."Action");
		$handler = $handlerClass->newInstance();
	}

}