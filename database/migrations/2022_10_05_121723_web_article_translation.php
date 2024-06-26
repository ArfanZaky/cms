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
        Schema::create('web_article_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('web_articles')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('web_languages')->onDelete('cascade');
            $table->string('name');
            $table->string('sub_name')->nullable();
            $table->string('slug');
            $table->text('overview')->nullable();
            $table->text('description')->nullable();
            $table->text('info')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
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
        Schema::dropIfExists('web_article_translations');
    }
};
