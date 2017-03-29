<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_currency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_code', 4)->unique()->comment('ISO3 code');
            $table->string('country_name', 64)->unique();
            $table->string('currency_code', 4)->comment('ISO3 code');
            $table->string('currency_name', 64);
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
        Schema::drop('country_currency');
    }
}
