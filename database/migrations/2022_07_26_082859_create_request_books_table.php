<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_books', function (Blueprint $table) {
            $table->id();
            $table->integer('catagory_id')->default(0);
            $table->integer('subcatagory_id')->default(0);
            $table->integer('book_id')->default(0);
            $table->date('get_date')->nullable();
            $table->date('return_date')->nullable();
            $table->integer('student_id')->default(0);
            $table->integer('class_id')->default(0);
            $table->integer('admin_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('staff_id')->default(0);
            $table->integer('deleteStatus')->default(0);
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
        Schema::dropIfExists('request_books');
    }
}
