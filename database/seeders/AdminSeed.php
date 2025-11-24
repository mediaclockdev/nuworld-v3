<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeed extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $defaultPassword = bcrypt('marooncrane2025');
    $superAdmin = [

      [
        'first_name' => 'Sourav',
        'middle_name' => '',
        'last_name' => 'Bhowmik',
        'email' => 'sb@mediaclock.com.au',
        'phone' => '9433857585',
        'password' => $defaultPassword,
        'status' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ],

    ];

    DB::table('admins')->insert($superAdmin);

    $role = [
      [
        'name' => 'Super Admin',
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'name' => 'Admin',
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ]
    ];

    DB::table('roles')->insert($role);

    $superAdminRole = [
      [
        'admin_id' => 1,
        'role_id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'admin_id' => 2,
        'role_id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'admin_id' => 3,
        'role_id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'admin_id' => 4,
        'role_id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'admin_id' => 5,
        'role_id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'admin_id' => 6,
        'role_id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ],
      [
        'admin_id' => 7,
        'role_id' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ]
    ];

    DB::table('admin_role')->insert($superAdminRole);
  }
}
