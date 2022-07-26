<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnOrderIdEncryptNew1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_books', function (Blueprint $table) {
            $table->integer('encrypt_id')->default(0);
            $table->date('returned_date')->nullable();
            $table->integer('fine_status')->default(0);
            $table->integer('returned_status')->default(0);
            $table->double('fine_amount', 15, 8)->default(0);
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
