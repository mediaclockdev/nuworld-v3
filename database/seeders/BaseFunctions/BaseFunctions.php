<?php

namespace Database\Seeders\BaseFunctions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait BaseFunctions
{
  private function truncateTable()
  {
    $tables = [
      // From ProductSeeder Related Table
      'products',
      'product_variants',
      'product_variant_attributes',
      'inventories',
      'media_galleries',
      'product_variant_images',

      // Orders Related Table
      'orders',
      'order_histories',
      'order_products',
      'order_returns',
      'order_return_items',

      // From CustomBannerSeeder Related Table
      'custom_banners',

      // From ProductAttributeValueSeeder Related Table
      'product_attributes',
      'product_attribute_values',

      // From ProductCategorySeeder Related Table
      'product_categories',
      'menu_items',
      'cms_pages',
      'product_filters',
    ];

    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Schema::disableForeignKeyConstraints();

    foreach ($tables as $table) {
      if (Schema::hasTable($table)) {
        DB::table($table)->truncate();
      }
    }

    Schema::enableForeignKeyConstraints();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
  }
}
