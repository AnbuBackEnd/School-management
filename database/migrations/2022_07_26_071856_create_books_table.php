<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->integer('catagory_id')->default(0);
            $table->integer('subcatagory_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('admin_id')->default(0);
            $table->integer('deleteStatus')->default(0);
            $table->text('book_name')->nullable();
            $table->text('isbn_no')->nullable();
            $table->text('author_name')->nullable();
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
