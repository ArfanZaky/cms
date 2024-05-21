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
        Schema::create('web_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->BigInteger('menu_id')->default(0);
            $table->BigInteger('view')->nullable();
            $table->string('custom')->nullable();
            $table->string('attachment')->default('default.pdf');
            $table->string('video')->default('default.mp4');
            $table->string('image')->default('default.jpg');
            $table->string('image_xs')->default('default.jpg');
            $table->string('image_sm')->default('default.jpg');
            $table->string('image_md')->default('default.jpg');
            $table->string('image_lg')->default('default.jpg');
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('unpublish_at')->nullable();
            $table->integer('sort')->default(0);
            $table->integer('visibility')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_pages');
    }
};
