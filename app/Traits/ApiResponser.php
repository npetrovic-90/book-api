<?php

namespace App\Traits;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;


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
	    //filtering data first, for example: selecting admin that is also verified
        $collection=$this->filterData($collection,$transformer);
        //sorting data first
        $collection=$this->sortData($collection,$transformer);
        //paginating data
        $collection=$this->paginateData($collection);
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

	//filtering data, by attributes passed through url, can be more than one attribute
    protected  function  filterData(Collection $collection,$transformer){

	    foreach(request()->query() as $query=>$value){
            $attribute = $transformer::originalAttribute($query);

            if(isset($attribute,$value)){

                $collection=$collection->where($attribute,$value);
            }
        }

	    return $collection;
    }

	//sorting data by sort_by attribute, which is passed through url.
	protected  function  sortData(Collection $collection,$transformer){

	    if(request()->has('sort_by')){

	        $attribute=$transformer::originalAttribute(request()->sort_by);

	        $collection=$collection->sortBy->{$attribute};
        }

	    return $collection;
    }
    //custom paginate method
    protected  function paginateData(Collection $collection){

	    $rules=[
	        'per_page'=>'integer|min:2|max:50',
        ];


	    Validator::validate(request()->all(),$rules);
        //element per page,default
        $perPage=15;
        //We change $perPage, if we get custom per_page in request
        if(request()->has('per_page')){

            $perPage=(int) request()->per_page;

        }

	    //current page
	    $page = LengthAwarePaginator::resolveCurrentPage();


	    //creating slice of collection we want to paginate
	    $results=$collection->slice(($page-1)*$perPage,$perPage)->values();

	    //create new LengthAwarePaginator, attributes are:
        // $result-slice of collection,collection length, elements per page, page number and
        // path attribute, that is used to resolve next and previous page

	    $paginated = new LengthAwarePaginator($results, $collection->count(),$perPage,$page, [
                'path'=>LengthAwarePaginator::resolveCurrentPath(),
            ]);
        //LengthAwarePaginator ignores all other attributes, that are passed through url/request, so we have to
        // append them
	    $paginated->appends(request()->all());

	    return $paginated;

    }

	//function that we use to transform data from our model to our transformer
	protected function transformData($data,$transformer){

	    $transformation = fractal($data,new $transformer);

	    return $transformation->toArray();
    }

}



?>
