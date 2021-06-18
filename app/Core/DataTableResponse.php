<?php

namespace App\Core;

 class DataTableResponse {

	public $draw = 2;
	public $recordsTotal = 0;
    public $recordsFiltered = 0;
    public $data = [] ;
    public $extra = [];

	public function __construct($data,$totalData,$extra=[])
	{
		$this->draw = intval(\Request::input('draw'));
		$this->recordsTotal = $totalData;
		$this->recordsFiltered = $totalData;
		$this->data = $data;
		$this->extra = $extra;
	}
}