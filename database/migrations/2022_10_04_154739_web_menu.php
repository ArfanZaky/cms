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
        Schema::create('web_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id')->default(0);
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->default(0);
            $table->unsignedBigInteger('catalog_id')->default(0);
            $table->unsignedBigInteger('gallery_id')->default(0);
            $table->BigInteger('parent')->default(0);
            $table->BigInteger('target')->default(0);
            $table->string('url')->default('#');
            $table->timestamps();
            $table->softDeletes();
            $table->BigInteger('sort')->default(0);
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
        Schema::dropIfExists('web_menus');
    }
};
