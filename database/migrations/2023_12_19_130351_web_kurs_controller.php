<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_kurs', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('currency');
            $table->string('unit');
            $table->string('tt_buy');
            $table->string('tt_sell');
            $table->string('bn_buy');
            $table->string('bn_sell');
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

    }
};
