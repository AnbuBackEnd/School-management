<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesStructureCatagoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees_structure_catagories', function (Blueprint $table) {
            $table->id();
            $table->text('feesStructreCatagory')->nullable();
            $table->text('duration')->nullable();
            $table->decimal('amount',15,2)->unsigned()->default(0);
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
        Schema::dropIfExists('fees_structure_catagories');
    }
}
