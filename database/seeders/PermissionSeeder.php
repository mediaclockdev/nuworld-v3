<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $permissions = [
      ['name' => 'View', 'slug' => 'view'],
      ['name' => 'Create', 'slug' => 'create'],
      ['name' => 'Edit', 'slug' => 'edit'],
      ['name' => 'Delete', 'slug' => 'delete'],
      ['name' => 'Export', 'slug' => 'export'],
    ];

    Permission::insert($permissions);
  }
}
