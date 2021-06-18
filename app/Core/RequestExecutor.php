<?php

namespace App\Core;

use Exception;
use App\Core\Response;
use ReflectionException;
use App\Core\BaseValidator;
use Illuminate\Database\QueryException;
use App\Exceptions\ConcurrencyException;
use Illuminate\Support\Facades\Validator;


class RequestExecutor
{

	public function Execute($request)
	{
		$response = $this->Validate($request);

		if ($response->IsValid)
			return $this->Handle($request);
		else
			return $response;
	}




	public function Validate($request)
	{
		$class = get_class($request);
		try {

			$validatorClass = new \ReflectionClass($class . "Validator");
			$validatorInstance = $validatorClass->newInstance();

			$valdiationRules = $validatorInstance->GetRules($request);

			$validator = Validator::make($request->all(), $valdiationRules);
			if ($validator->fails()) {
				return new Response(false, null, null, $validator->messages()->all());
			}

			return new Response(true);
		} catch (ReflectionException $e) {
			return new BaseValidator(null); //Auto validation
		}
	}

	public function Handle($request)
	{
		$class = get_class($request);
		$handlerClass = new \ReflectionClass($class . "Handler");
		$handler = $handlerClass->newInstance();
		try {
			$result = $handler->Serve($request);
			if (!is_array($result) && (get_class($result) == 'App\Core\Response'))
				return $result;
			return new Response(true, $result);
		} catch (ConcurrencyException $e) {
			$result = $e;
			return new Response(false, null, "Another user has modified the same record, please reload and redo your changes");
		} catch (QueryException $e) {
			return new Response(false, $e, "Eloquent Query Exception");
		} catch (Exception $e) {
			return new Response(false, $e, $e->getMessage());
		}
	}
}
