<?php 

namespace App\Core;
use Request;
use ReflectionProperty;
use ReflectionClass;

class BaseRequest {	
	private $_request;
	public function __construct(){
		$this->_request = Request::all();

		$class = get_class($this);
		$ref = new \ReflectionClass($class);
		$props   = $ref->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED );
		
		$json = json_encode($this->_request);
		$jsonArray = json_decode($json,true);
		foreach($jsonArray as $key=>$value)
		{
			if($ref->hasProperty( $key ))
			{
				$refProperty = $ref->getProperty( $key );
				$refProperty->setAccessible( true );
				$refProperty->setValue($this, $value);
			}
		}
	}

	public function all(){
		return $this->_request;
	}
}