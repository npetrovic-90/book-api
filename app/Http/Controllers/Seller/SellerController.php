<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //getting all sellers, seller is a user that has a book
        $sellers=Seller::has('books')->get();

        return $this->showAll($sellers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        //getting single user that also exists in books table

        $seller=Seller::has('books')->findOrFail($id);
        //returning corresponding code and data
        return $this->showOne($seller);
    }

   
}
