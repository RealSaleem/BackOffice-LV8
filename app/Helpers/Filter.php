<?php 

namespace App\Helpers;

class Filter
{
	public $Field;
	public $Operator;
	public $Value;

	public function __construct($field, $operator, $value){
		$this->Field = $field;
		$this->Operator = $operator;
		$this->Value = $value;
	}
}

?>