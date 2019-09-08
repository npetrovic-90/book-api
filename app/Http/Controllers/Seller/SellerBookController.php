<?php

namespace App\Http\Controllers\Seller;

use App\Book;
use App\User;
use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerBookController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        //
        $books=$seller->books;


        return $this->showAll($books);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $seller)
    {
        //
        $rules=[
            'title'=>'required',
            'description'=>'required',
            'quantity'=>'required|integer|min:1',
            'image'=>'required|image',

        ];

        $this->validate($request,$rules);

        $data = $request->all();

        $data['status']=Book::UNAVAILABLE_BOOK;
        $data['image']='1.jpeg';
        $data['seller_id']=$seller->id;

        $book=Book::create($data);

        return $this->showOne($book);

    }

    

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller,Book $book)
    {
        $rules=[
            'quantity'=>'integer|min:1',
            'status'=>'in:'.Book::AVAILABLE_BOOK.','.Book::UNAVAILABLE_BOOK,
            'image'=>'image',
            ];

         $this->validate($request,$rules);
         
         $this->checkSeller($seller,$book);

         $book->fill($request->only([
            'name',
            'description',
            'quantity',

         ]));

         if($request->has('status')){
            $book->status=$request->status;
            if($book->isAvailable() && $book->categories()->count()==0){
                return $this->errorResponse('An active product must have at least one category!',409);
            }
         } 
         if($book->isClean()){
            return $this->errorResponse('You need to specify different value to update!',422);

         }

         $book->save();

         return $this->showOne($book);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller,Book $book)
    {
        //
        $this->checkSeller($seller,$book);

        $book->delete();


        return $this->showOne($book);
    }

    protected function checkSeller(Seller $seller,Book $book){
        if($seller->id !=$book->seller_id)
            throw new HttpException(422,'The specified seller is not actual seller of the book!');
    }
}
