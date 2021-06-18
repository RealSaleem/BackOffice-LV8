<?php
namespace App\Models\Instrumentation;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model{

	protected $connection = 'mysql_instrumentation';
	protected $table = 'error_logs';
	public $timestamps = false;
}

?>