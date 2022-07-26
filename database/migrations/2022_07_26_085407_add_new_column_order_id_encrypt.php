<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnOrderIdEncrypt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_books', function (Blueprint $table) {
            $table->text('encrypt_catagory_id')->nullable();
            $table->text('encrypt_subcatagory_id')->nullable();
            $table->text('encrypt_book_id')->nullable();
            $table->text('encrypt_student_id')->nullable();
            $table->text('encrypt_staff_id')->nullable();
            $table->text('encrypt_class_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_books', function (Blueprint $table) {
            //
        });
    }
}
