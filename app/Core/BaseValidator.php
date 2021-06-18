<?php 

namespace App\Core;
use ArrayObject;
use App\Core\Response;
use App\Core\ValidationError;

class BaseValidator {

	protected $valid;
	protected $errorsList;
	private $request;
	public $IsValid = true;

	public function __construct($request) {		
		$this->errorsList = array();
		$this->request = $request;
	}

	public function RuleFor($property, $function, $errorMessage = null) {		

		if(!$this->$function($this->request->$property)) {			

			$errorMessage = $errorMessage ? $errorMessage : $property . ' is invalid';

			$this->errorsList[] = new ValidationError($property, $errorMessage);
		}		
	}
}

