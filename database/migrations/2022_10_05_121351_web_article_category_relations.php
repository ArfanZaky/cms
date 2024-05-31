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
        Schema::create('web_article_category_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('web_articles')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('web_contents')->onDelete('cascade');
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
        Schema::dropIfExists('web_article_category_relations');
    }
};
