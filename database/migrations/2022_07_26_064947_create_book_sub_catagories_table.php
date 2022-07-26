<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookSubCatagoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_sub_catagories', function (Blueprint $table) {
            $table->id();
            $table->text('subcatagory_name')->nullable();
            $table->text('encrypt_catagory_id')->nullable();
            $table->text('encrypt_id')->nullable();
            $table->integer('catagory_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('admin_id')->default(0);
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
        Schema::dropIfExists('book_sub_catagories');
    }
}
