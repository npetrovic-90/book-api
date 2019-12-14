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

	    //if $collection is empty we do not need to transform,just return empty
        // collection and corresponding code
	    if($collection->isEmpty()){

            return $this->successResponse(['data'=>$collection],$code);

        }

	    //getting transformer of first element
	    $transformer=$collection->first()->transformer;

	    //using transformData() to transform data from out model to transformer
	    $collection=$this->transformData($collection,$transformer);

		return $this->successResponse($collection,$code);
	}
	//returns one response for a specific model
	protected function showOne(Model $instance,$code= 200){

	    // we take transformer class from specific model
	    $transformer=$instance->transformer;

	    //we use transformData() to transform our model to specific transformer
        // depending on value of $transformer
	    $instance=$this->transformData($instance,$transformer);

		return $this->successResponse($instance,$code);
	}

	protected function showMessage($message,$code = 200){

		return $this->successResponse(['data'=>$message],$code);
	}

	//function that we use to transform data from our model to our transformer
	protected function transformData($data,$transformer){

	    $transformation = fractal($data,new $transformer);

	    return $transformation->toArray();
    }

}



?>
