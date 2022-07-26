<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_records', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id')->default(0);
            $table->integer('class_id')->default(0);
            $table->integer('subject_id')->default(0);
            $table->integer('deleteStatus')->default(0);
            $table->date('date')->nullable();
            $table->text('encrypt_id')->nullable();
            $table->text('exam_id_encrypt')->nullable();
            $table->text('class_id_encrypt')->nullable();
            $table->text('subject_id_encrypt')->nullable();
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
        Schema::dropIfExists('exam_records');
    }
}
