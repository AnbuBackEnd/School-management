<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnOrderIdEncryptNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_orders', function (Blueprint $table) {
            $table->date('requested_date')->nullable();
            $table->integer('requested_status')->default(0);
            $table->integer('fine_paid')->default(0);
            $table->double('fine_amount', 15, 8)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_orders', function (Blueprint $table) {
            //
        });
    }
}
