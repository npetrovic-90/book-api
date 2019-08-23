<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Not all users are buyers,only those which id is contained in
        //transaction table
        $buyers=Buyer::has('transactions')->get();


        //returning corresponding code and data
        return $this->showAll($buyers);
    }

    

   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //getting single user that also exists in transactions table

        $buyer=Buyer::has('transactions')->findOrFail($id);


        //returning corresponding code and data
        return $this->showOne($buyer);
    }

    


   

    
}
