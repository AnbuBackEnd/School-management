<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnDeleteUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fees_structure_catagories', function (Blueprint $table) {
            $table->text('encrypt_id')->nullable();
            $table->integer('user_id')->default(0);
            $table->integer('deleteStatus')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fees_structure_catagories', function (Blueprint $table) {
            //
        });
    }
}
