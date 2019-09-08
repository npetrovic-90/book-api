<?php

namespace App\Providers;

use App\Book;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        Book::updated(function($book){
            if($book->quantity==0 && $book->isAvailable()){
                $book->status=Book::UNAVAILABLE_BOOK;

                $book->save();
            }
        });
    }
}
