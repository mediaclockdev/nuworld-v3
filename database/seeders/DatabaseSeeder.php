<?php

namespace Database\Seeders;

use Database\Seeders\Sunglasses\CustomBannerSeeder;
use Database\Seeders\Sunglasses\ProductAttributeValueSeeder;
use Database\Seeders\Sunglasses\ProductCategorySeeder;
use Database\Seeders\Sunglasses\ProductSeeder;
use Database\Seeders\Sunglasses\ProductVariantImageSeeder;
use Database\Seeders\Sunglasses\CmsPageSeeder;
//use Database\Seeders\Sunglasses\BlogSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      AdminSeed::class,
      RemixIconSeeder::class,
      PermissionSeeder::class,
      CountrySeeder::class,
      StatesTableSeeder::class,
      PaymentGatewaySeeder::class,
      // ValueListSeeder::class,
      ModuleSeeder::class,
      SubModuleSeeder::class,
      // FurnitureSeeder::class,
      SiteSettingsSeeder::class,
      MenuSeeder::class,
      ProductCategorySeeder::class,
      ProductAttributeValueSeeder::class,
      ProductSeeder::class,
      ProductVariantImageSeeder::class,
      CustomBannerSeeder::class,
      PincodeSeeder::class,
      CmsPageSeeder::class,
      // BlogSeeder::class
    ]);
  }
}
