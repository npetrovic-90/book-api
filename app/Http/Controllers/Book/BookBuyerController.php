<?php

namespace App\Http\Controllers\Book;

use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BookBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Book $book)
    {
        //
        $buyers=$book->transactions()
                     ->with('buyer')
                     ->get()
                     ->pluck('buyer')
                     ->unique('id')
                     ->values();


        return $this->showAll($buyers);
    }

}
