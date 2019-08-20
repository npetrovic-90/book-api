<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Book;
use App\User;
use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'verified'=>$verified=$faker->randomElement([User::UNVERIFIED_USER,User::UNVERIFIED_USER]),
        'verification_token'=>$verified==User::VERIFIED_USER ? null : User::generateVerificationCode(),
        'admin'=>$admin=$faker->randomElement([User::ADMIN_USER,User::REGULAR_USER]),
    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' =>$faker->paragraph(1), 
    ];
});

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'description' =>$faker->paragraph(1),
        'quantity' =>$faker->numberBetween(1,10),
        'status'=>$faker->randomElement([Book::AVAILABLE_BOOK,Book::UNAVAILABLE_BOOK]),
        'image'=>$faker->randomElement(['1.jpeg','2.jpeg','3.jpeg']),
        'seller_id'=>User::all()->random()->id,
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {

	$seller=Seller::has('books')->get()->random();
	$buyer=User::all()->except($seller->id)->random();


    return [
        'quantity'=>$faker->numberBetween(1,3),
        'buyer_id'=>$buyer->id,
        'book_id'=>$seller->books->random()->id,
    ];
});
