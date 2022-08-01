<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntiateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intiate_fees', function (Blueprint $table) {
            $table->id();
            $table->text('fees_name')->nullable();
            $table->double('amount', 15, 2);
            $table->integer('class_id')->default(0);
            $table->date('last_day_to_pay')->nullable();
            $table->integer('status')->nullable();
            $table->integer('deleteStatus')->default(0);
            $table->integer('admin_id')->default(0);
            $table->integer('user_id')->default(0);
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
        Schema::dropIfExists('intiate_fees');
    }
}
