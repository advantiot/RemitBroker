<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remitters', function (Blueprint $table) {
            $table->increments('id'); //The default id for Laravel
            $table->string('remitter_id', 16)->unique()->comment('Assigned to Remitter');
            $table->string('name', 128)->unique()->comment('Remitter Name');
            $table->string('master_password', 128)->unique()->comment('Remitter Master Password');
            $table->string('api_key', 128)->unique()->comment('Remitter API Key');
            $table->string('country_code', 4)->comment('Country Code in ISO3');
            $table->tinyInteger('service_type')->comment('1=Send,2=Payout,3=Both');
            $table->tinyInteger('status')->comment('0=Inactive,1=Active,2=Suspended,3=Terminated');
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
        Schema::drop('remitters');
    }
}
