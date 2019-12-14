<?php

namespace App;
use App\Book;
use App\Transformers\SellerTransformer;

class Seller extends User
{
    //

    public $transformer=SellerTransformer::class;

    public function books(){
    	return $this->hasMany(Book::class);
    }
}
