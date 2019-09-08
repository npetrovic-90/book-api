<?php

use App\Book;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description',1000);
            $table->integer('quantity')->unsigned();
            $table->string('status')->default(Book::UNAVAILABLE_BOOK);
            $table->string('image');
            $table->integer('seller_id')->unsigned()->foreign()->references('id')->on('users');
             $table->softDeletes();
            $table->timestamps();

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
