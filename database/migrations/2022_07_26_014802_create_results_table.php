<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->default(0);
            $table->integer('class_id')->default(0);
            $table->integer('exam_id')->default(0);
            $table->integer('subject_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('deleteStatus')->default(0);
            $table->integer('encrypt_id')->default(0);
            $table->integer('student_encrypt_id')->default(0);
            $table->integer('class_encrypt_id')->default(0);
            $table->integer('exam_encrypt_id')->default(0);
            $table->integer('subject_encrypt_id')->default(0);
            $table->double('mark', 15, 8);
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
        Schema::dropIfExists('results');
    }
}
