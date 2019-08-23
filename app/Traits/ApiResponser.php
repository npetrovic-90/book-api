<?php 

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
	//function that returns data and code when part of the code executes successfully
	private function successResponse($data,$code){

		return response()->json($data,$code);
	}
	//function that returns message and code when part of the code executes unsuccessfully
	protected function errorResponse($message,$code){

		return response()->json(['message'=>$message,'code'=>$code],$code);
	}
	//returns all responses 
	protected function showAll(Collection $collection,$code= 200){

		return $this->successResponse(['data'=>$collection],$code);
	}
	//returns one response for a specific model
	protected function showOne(Model $model,$code= 200){

		return $this->successResponse(['data'=>$model],$code);
	}

}



?>