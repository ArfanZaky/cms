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
            CREATE VIEW web_view_categories_map AS
            (
                select * from 
                (
                    select `category`.`language_id` ,'article' AS `type` ,`category`.`name` AS `name` ,`category`.`category_id` AS `model_id`,'App/Models/WebArticleCategories' AS `model_type` from `web_article_category_translations` `category` 
                    union all select `catalog`.`language_id` ,'catalog' AS `type` ,`catalog`.`name` AS `name` ,`catalog`.`catalog_id` AS `model_id`,'App/Models/WebProductsCatalogs' AS `model_type` from `web_products_catalogs_translations` `catalog` 
                    union all select `gallery`.`language_id` ,'gallery' AS `type` ,`gallery`.`name` AS `name` ,`gallery`.`gallery_id` AS `model_id`,'App/Models/WebGalleries' AS `model_type` from `web_gallery_translations` `gallery` 
                    union all select `banner`.`language_id` ,'banner' AS `type` ,`banner`.`name` AS `name` ,`banner`.`banner_id` AS `model_id`,'App/Models/WebBannerCategories' AS `model_type` from `web_banner_translations` `banner` 
                    union all select `contact`.`language_id` ,'contact' AS `type` ,`contact`.`name` AS `name` ,`contact`.`category_id` AS `model_id`,'App/Models/WebContactCategories' AS `model_type` from `web_contact_category_translations` `contact` 
                    union all select `vacancy`.`language_id` ,'vacancy' AS `type` ,`vacancy`.`name` AS `name` ,`vacancy`.`category_id` AS `model_id`,'App/Models/WebVacancies' AS `model_type` from `web_vacancy_category_translations` `vacancy` 
                    union all select `brand`.`language_id` ,'brand' AS `type` ,`brand`.`name` AS `name` ,`brand`.`brand_id` AS `model_id`,'App/Models/WebProductsBrands' AS `model_type` from `web_products_brands_translations` `brand` 
                    union all select `product`.`language_id` ,'product' AS `type` ,`product`.`name` AS `name` ,`product`.`product_id` AS `model_id`,'App/Models/WebProducts' AS `model_type` from `web_products_translations` `product`     
                )
                as `union`
                where `union`.`language_id` = 1
                order by `union`.`type` asc
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
        DB::statement('DROP VIEW web_view_categories_map');
    }
};
