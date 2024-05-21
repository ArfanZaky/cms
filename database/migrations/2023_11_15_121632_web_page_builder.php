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
        Schema::create('web_page_builders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('web_pages')->onDelete('cascade');
            $table->string('type', 255)->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('model_type', 255)->nullable();
            $table->integer('model_id')->nullable();
            $table->foreignId('language_id')->constrained('web_languages')->onDelete('cascade');
            $table->longText('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_page_builders');
    }
};
