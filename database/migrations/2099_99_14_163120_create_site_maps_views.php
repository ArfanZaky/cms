<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW web_sitemaps AS
            (
                select `page`.`page_id` AS `id_table`,`page`.`language_id` AS `language`,'page' AS `code`,`page`.`slug` AS `slug` from `web_page_translations` `page` 
                union all select `content`.`content_id` AS `id_table`,`content`.`language_id` AS `language`,'content' AS `code`,`content`.`slug` AS `slug` from `web_content_translations` `content` 
                union all select `article`.`article_id` AS `id_table`,`article`.`language_id` AS `language`,'article' AS `code`,`article`.`slug` AS `slug` from `web_article_translations` `article` 
                union all select `brand`.`brand_id` AS `id_table`,`brand`.`language_id` AS `language`,'brand' AS `code`,`brand`.`slug` AS `slug` from `web_products_brands_translations` `brand` 
                union all select `catalog`.`catalog_id` AS `id_table`,`catalog`.`language_id` AS `language`,'catalog' AS `code`,`catalog`.`slug` AS `slug` from `web_products_catalogs_translations` `catalog` 
                union all select `product`.`product_id` AS `id_table`,`product`.`language_id` AS `language`,'product' AS `code`,`product`.`slug` AS `slug` from `web_products_translations` `product` 
                union all select `variant`.`variant_id` AS `id_table`,`variant`.`language_id` AS `language`,'variant' AS `code`,`variant`.`slug` AS `slug` from `web_products_variants_translations` `variant` 
                union all select `gallery`.`gallery_id` AS `id_table`,`gallery`.`language_id` AS `language`,'gallery' AS `code`,`gallery`.`slug` AS `slug` from `web_gallery_translations` `gallery` 
                union all select `item`.`item_id` AS `id_table`,`item`.`language_id` AS `language`,'item' AS `code`,`item`.`slug` AS `slug` from `web_gallery_item_translations` `item` 
                union all select `vacancy`.`vacancy_id` AS `id_table`,`vacancy`.`language_id` AS `language`,'vacancy' AS `code`,`vacancy`.`slug` AS `slug` from `web_vacancy_translations` `vacancy`
                union all select `chatbot`.`chatbot_content_id` AS `id_table`,`chatbot`.`language_id` AS `language`,'chatbot' AS `code`,`chatbot`.`slug` AS `slug` from `web_chatbot_categories_translations` `chatbot`
            )
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS web_sitemaps');
    }
};
