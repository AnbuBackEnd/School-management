<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAuthorIdInPostTableRenameStatusBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_orders', function (Blueprint $table) {
            $table->renameColumn('requested_date','returned_date');
            $table->renameColumn('requested_status','returned_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_table_rename_status_books', function (Blueprint $table) {
            $table->renameColumn('returned_date','requested_date');
            $table->renameColumn('returned_status','requested_status');
        });
    }
}
