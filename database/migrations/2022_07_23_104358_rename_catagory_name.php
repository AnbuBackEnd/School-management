<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCatagoryName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fees_structure_catagories', function (Blueprint $table) {
            $table->renameColumn('feesStructreCatagory', 'feesStructureCatagory');
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
            $table->renameColumn('feesStructureCatagory', 'feesStructreCatagory');
        });
    }
}
