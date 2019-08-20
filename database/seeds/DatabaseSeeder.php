<?php

use App\Book;
use App\User;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

    	DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // $this->call(UsersTableSeeder::class);
        User::truncate();
        Category::truncate();
        Book::truncate();
        Transaction::truncate();

        DB::table('book_category')->truncate();

        $usersQuanitity=200;
        $categoriesQuanitity=30;
        $booksQuanitity=1000;
        $transactionsQuanitity=1000;

        factory(User::class,$usersQuanitity)->create();
        factory(Category::class,$categoriesQuanitity)->create();
        
        factory(Book::class,$booksQuanitity)->create()->each(
        	function($product){
        		$categories=Category::all()->random(mt_rand(1,5))->pluck('id');

        		$product->categories()->attach($categories);
        	}
        );

        factory(Transaction::class,$transactionsQuanitity)->create();
    }
}
