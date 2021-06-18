<?php 

namespace App\Core;
use ReflectionClass;
use ReflectionProperty;
use App\Core\Response;
use App\Exceptions\ConcurrencyException;

class GridRequestExecutor {
	public function __construct()
	{
	}

	public function Execute($request, $context, $relations = array(), $filters = array())
	{
		$response = $this->Validate($request);		
		if($response->IsValid)
			return $this->Handle($request, $context, $relations, $filters);
		else
			return $response;
	}

	public function Validate($request)
	{
		$class = get_class($request);
		$validatorClass = new \ReflectionClass(str_replace("Models","Validators",$class)."Validator");
		$validator = $validatorClass->newInstance();
		return $validator->IsValid($request);
	}

	public function Handle($request, $context, $relations, $filters)
	{
		$class = get_class($request);
		$handlerClass = new \ReflectionClass(str_replace("Models","Handlers",$class)."Handler");
		$handler = $handlerClass->newInstance();
		try{
			$result = $handler->Serve($request, $context, $relations, $filters);
			return new Response(true, $result);
		}
		catch ( ConcurrencyException $e) {
			$result = $e;
			return new Response(false, null, "Another user has modified the same record, please reload and redo your changes");
		}
		catch ( QueryException $e) {
			$result = $e->errorInfo;
			return new Response(false, $result, "Eloquent Query Exception");
		}
		catch( Exception $e ){
			$result = $e->errorInfo;
			return new Response(false, $result, "Something Went Wrong Exception");
		}
	}

}