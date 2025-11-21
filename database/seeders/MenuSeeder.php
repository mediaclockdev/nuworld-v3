<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Insert Menu
    DB::table('menus')->insert([
      'name' => 'Main Menu',
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}
