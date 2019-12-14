<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
* Buyers
*/
Route::resource('buyers','Buyer\BuyerController',['only'=>['index','show']]);
Route::resource('buyers.transactions','Buyer\BuyerTransactionController',['only'=>['index']]);
Route::resource('buyers.books','Buyer\BuyerBookController',['only'=>['index']]);
Route::resource('buyers.sellers','Buyer\BuyerSellerController',['only'=>['index']]);
Route::resource('buyers.categories','Buyer\BuyerCategoryController',['only'=>['index']]);
/**
* Categories
*/
Route::resource('categories','Category\CategoryController',['except'=>['create','edit']]);
Route::resource('categories.books','Category\CategoryBookController',['only'=>['index']]);
Route::resource('categories.sellers','Category\CategorySellerController',['only'=>['index']]);
Route::resource('categories.transactions','Category\CategoryTransactionController',['only'=>['index']]);
Route::resource('categories.buyers','Category\CategoryBuyerController',['only'=>['index']]);

/**
* Books
*/
Route::resource('books','Book\BookController',['only'=>['index','show']]);
Route::resource('books.transactions','Book\BookTransactionController',['only'=>['index']]);
Route::resource('books.buyers','Book\BookBuyerController',['only'=>['index']]);
Route::resource('books.categories','Book\BookCategoryController',['only'=>['index','update','destroy']]);
Route::resource('books.buyers.transactions','Book\BookBuyerTransactionController',['only'=>['store']]);

/**
* Sellers
*/
Route::resource('sellers','Seller\SellerController',['only'=>['index','show']]);
Route::resource('sellers.transactions','Seller\SellerTransactionController',['only'=>['index']]);
Route::resource('sellers.categories','Seller\SellerCategoryController',['only'=>['index']]);
Route::resource('sellers.buyers','Seller\SellerBuyerController',['only'=>['index']]);
Route::resource('sellers.books','Seller\SellerBookController',['except'=>['create','edit','show']]);

/**
* Transactions
*/
Route::resource('transactions','Transaction\TransactionController',['only'=>['index','show']]);
Route::resource('transactions.categories','Transaction\TransactionCategoryController',['only'=>['index']]);
Route::resource('transactions.sellers','Transaction\TransactionSellerController',['only'=>['index']]);
/**
* Users
*/
Route::resource('users','User\UserController',['except'=>['create','edit']]);
Route::name('verify')->get('users/verify/{token}','User\UserController@verify');
Route::name('resend')->get('users/{user}/resend','User\UserController@resend');


