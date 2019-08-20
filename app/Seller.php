<?php

namespace App;
use App\Book;

class Seller extends User
{
    //

    public function books(){
    	return $this->hasMany(Book::class);
    }
}
