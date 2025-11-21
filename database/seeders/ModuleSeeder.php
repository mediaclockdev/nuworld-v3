<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ModuleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    Schema::disableForeignKeyConstraints();
    Module::truncate();
    Schema::enableForeignKeyConstraints();

    $modules = [
      ['name' => 'Master Settings', 'sequence' => 1, 'icon' => 'ri-shield-user-line', 'created_by' => null, 'updated_by' => 1],
      ['name' => 'Admin Settings', 'sequence' => 2, 'icon' => 'ri-shield-user-line', 'created_by' => null, 'updated_by' => null],
      ['name' => 'Content Management', 'sequence' => 3, 'icon' => 'ri-computer-line', 'created_by' => null, 'updated_by' => null],
      ['name' => 'Product Management', 'sequence' => 4, 'icon' => 'ri-book-read-line', 'created_by' => null, 'updated_by' => null],
      ['name' => 'Order Management', 'sequence' => 5, 'icon' => 'ri-file-list-3-line', 'created_by' => null, 'updated_by' => null],
      ['name' => 'Promotions', 'sequence' => 6, 'icon' => 'ri-gift-2-line', 'created_by' => null, 'updated_by' => null],
      ['name' => 'Reports and Analytics', 'sequence' => 7, 'icon' => 'ri-survey-line', 'created_by' => null, 'updated_by' => null],
      ['name' => 'Inventory Management', 'sequence' => 8, 'icon' => 'ri-ancient-pavilion-line', 'created_by' => null, 'updated_by' => null],
      ['name' => 'Blog Management', 'sequence' => 9, 'icon' => 'ri-a-b', 'created_by' => null, 'updated_by' => NULL],
      ['name' => 'Contact Manage', 'sequence' => 10, 'icon' => 'ri-account-circle-fill', 'created_by' => null, 'updated_by' => NULL],
    ];

    Module::insert($modules);
  }
}
