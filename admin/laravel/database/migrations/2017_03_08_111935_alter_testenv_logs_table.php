<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTestenvLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testenv_logs', function (Blueprint $table) {
            //Add a downloaded column to flag a txnpost as downloaded from API/MongoDB, but not necessarily processed
            $table->boolean('downloaded');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('testenv_logs', function (Blueprint $table) {
            //
        });
    }
}
