<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectmappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjectmappings', function (Blueprint $table)
        {
            $table->id();
            $table->integer('admin_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('subject_id')->default(0);
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
        Schema::dropIfExists('subjectmappings');
    }
}
