<?php

namespace Database\Seeders;

use Database\Seeders\Fashion\CustomBannerSeeder;
use Database\Seeders\Fashion\ProductAttributeValueSeeder;
use Database\Seeders\Fashion\ProductCategorySeeder;
use Database\Seeders\Fashion\ProductSeeder;
use Database\Seeders\Fashion\ProductVariantImageSeeder;
use Database\Seeders\Fashion\CmsPageSeeder;
use Database\Seeders\Fashion\BlogSeeder;
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
      BlogSeeder::class
    ]);
  }
}
