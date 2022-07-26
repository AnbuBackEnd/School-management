<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->default(0);
            $table->integer('encrypt_student_id')->nullable();
            $table->integer('class_id')->default(0);
            $table->integer('encrypt_class_id')->nullable();
            $table->integer('staff_id')->default(0);
            $table->integer('encrypt_staff_id')->nullable();
            $table->integer('no_of_books')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('admin_id')->default(0);
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
        Schema::dropIfExists('request_orders');
    }
}
