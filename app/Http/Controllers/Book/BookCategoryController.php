<?php

namespace App\Http\Controllers\Book;

use App\Book;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BookCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Book $book)
    {
        //
         $categories=$book->categories;

        return $this->showAll($categories);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book,Category $category)
    {
        //attach, sync, syncWithoutDetach
        $book->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($book->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book,Category $category)
    {
        //
        if(!$book->categories()->find($category->id)){

            return $this->errorResponse('The specified category is not a category of this book',404);
        }

        $book->categories()->detach($category->id);

        return $this->showAll($book->categories);
    }
}
