<?php

namespace App\Http\Controllers\Book;

use App\Book;
use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class BookBuyerTransactionController extends ApiController
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Book $book,User $buyer)
    {
        //
        $rules=[
            'quantity'=>'required|integer|min:1'
        ];

        $this->validate($request,$rules);

        if($buyer->id==$book->seller_id){
            return $this->errorResponse('The buyer must be different from the seller',409);
        }

        if($buyer->isVerified()){
            return $this->errorResponse('The buyer must be verified user',409);
        }

         if($book->seller->isVerified()){
            return $this->errorResponse('The seller must be verified user',409);
        }

        if(!$book->isAvailable()){
             
            return $this->errorResponse('The book is not available',409);
       
        }
        if($book->quantity < $request->quantity){
             
            return $this->errorResponse('The book does not have enough units for this transaction',409);
        
        }

        return DB::transaction(function() use ($request,$book,$buyer){

            $book->quantity -=$request->quantity;
            $book->save();

            $transaction=Transaction::create([

                'quantity'=>$request->quantity,
                'buyer_id'=>$buyer->id,
                'book_id'=>$book->id,
            ]);

            return $this->showOne($transaction,201);
        });

    }

    
}
