<?php 

namespace App\Core;

class ValidationError {
	public $name;
	public $error;
	
	public function __construct($name, $error) {
		$this->name = $name;
		$this->error = $error;
	}
}