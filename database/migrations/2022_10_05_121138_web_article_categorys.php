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
        Schema::create('web_contents', function (Blueprint $table) {
            $table->id();
            $table->UnsignedBigInteger('parent')->default(0);
            $table->string('image')->default('default.jpg');
            $table->string('image_xs')->default('default.jpg');
            $table->string('image_sm')->default('default.jpg');
            $table->string('image_md')->default('default.jpg');
            $table->string('image_lg')->default('default.jpg');
            $table->timestamps();
            $table->softDeletes();
            $table->string('custom')->default('');
            $table->UnsignedBigInteger('sort')->default(0);
            $table->BigInteger('visibility')->default(0);
            $table->BigInteger('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_contents');
    }
};
