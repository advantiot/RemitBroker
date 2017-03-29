<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestenvLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testenv_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->comment('Unique id for this post');
            $table->string('origin_uuid')->comment('Id of post this response is for, not required for initial post');
            $table->string('from_rmtr_id', 16)->comment('Post from Remitter');
            $table->string('to_rmtr_id', 16)->comment('Post to Remitter');
            $table->string('type', 16)->comment('Could be any request or response type, including acks');
            $table->timestamp('posted_on')->comment('Epoch time this post was recorded');
            //$table->timestamps(); ///timestamps not needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('testenv_logs');
    }
}
