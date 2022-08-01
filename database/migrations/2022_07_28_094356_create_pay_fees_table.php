<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->default(0);
            $table->integer('class_id')->default(0);
            $table->integer('fees_id')->default(0);
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
        Schema::dropIfExists('pay_fees');
    }
}
