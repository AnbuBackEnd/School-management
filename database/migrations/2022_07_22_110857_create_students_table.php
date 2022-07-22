<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('address')->nullable();
            $table->integer('gender')->default(0);
            $table->text('city')->nullable();
            $table->text('phone')->nullable();
            $table->text('student_id')->nullable();
            $table->text('email')->nullable();
            $table->date('dob')->nullable();
            $table->text('password')->nullable();
            $table->text('parent_or_guardian')->nullable();
            $table->integer('class_id')->default(0);
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
        Schema::dropIfExists('students');
    }
}
