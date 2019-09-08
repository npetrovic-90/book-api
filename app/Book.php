<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    //s
    use SoftDeletes;

    const AVAILABLE_BOOK='available';
    const UNAVAILABLE_BOOK='unavailable';

    protected $dates=['deleted_at'];

    protected $fillable=[
		'title',
		'description',
		'quantity',
		'status',
		'image',
		'seller_id',


    ];
    
    protected $hidden=['pivot'];

    public function isAvailable(){

    return $this->status==Book::AVAILABLE_BOOK;
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    
}
