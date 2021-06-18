<?php

namespace App\Core;

 class Response {

	public $Exception;
	public $IsValid;
	public $Payload;

	public function __construct($IsValid = null, $Payload = null, $Exception = null,$Errors = null,$message=null)
	{
		$this->Exception = $Exception;
		$this->IsValid = $IsValid;
		$this->Payload = $Payload;
		$this->Errors = $Errors;
		$this->Message = $message;
	}


 }
