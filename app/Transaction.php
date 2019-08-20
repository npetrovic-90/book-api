<?php

namespace App;
use App\Buyer;
use App\Book;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable=[
    	'quantity',
    	'buyer_id',
    	'book_id'


    ];

    public function buyer(){
    	return $this->belongsTo(Buyer::class);
    }

    public function book(){
    	return $this->belongsTo(Book::class);
    }
}
