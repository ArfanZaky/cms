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
        Schema::create('web_page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('web_pages')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('web_languages')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('sub_name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('overview')->nullable();
            $table->text('info')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keyword')->nullable();
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
        Schema::dropIfExists('web_page_translations');
    }
};
