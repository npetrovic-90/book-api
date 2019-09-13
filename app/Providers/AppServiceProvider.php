<?php

namespace App\Providers;

use App\Book;
use App\User;
use GuzzleHttp\retry;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
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

        User::created(function($user){
          retry(5, function() use ($user){
            Mail::to($user)->send(new UserCreated($user));
            },100
        );
        });

        User::updated(function($user){

            if($user->isDirty('email')){

                retry(5, function() use ($user){
            Mail::to($user)->send(new UserMailChanged($user));
            },100
        );
            }
        });



    }
}
