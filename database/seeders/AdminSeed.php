<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeed extends Seeder
{
  public function run(): void
  {
    $defaultPassword = Hash::make('marooncrane2025');

    // 1) Ensure admins table has the admin (insert if not exists)
    $adminEmail = 'sb@mediaclock.com.au';
    $existingAdmin = DB::table('admins')->where('email', $adminEmail)->first();

    if ($existingAdmin) {
      $adminId = $existingAdmin->id;
      // Optionally update some fields
      DB::table('admins')->where('id', $adminId)->update([
        'first_name' => 'Sourav',
        'middle_name' => '',
        'last_name' => 'Bhowmik',
        'phone' => '9433857585',
        'status' => 1,
        'updated_at' => now(),
        'updated_by' => 1,
      ]);
    } else {
      $adminId = DB::table('admins')->insertGetId([
        'first_name' => 'Sourav',
        'middle_name' => '',
        'last_name' => 'Bhowmik',
        'email' => $adminEmail,
        'phone' => '9433857585',
        'password' => $defaultPassword,
        'status' => 1,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    // 2) Ensure roles exist (create if not)
    $superRoleName = 'Super Admin';
    $adminRoleName = 'Admin';

    $superRole = DB::table('roles')->where('name', $superRoleName)->first();
    if (! $superRole) {
      $superRoleId = DB::table('roles')->insertGetId([
        'name' => $superRoleName,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    } else {
      $superRoleId = $superRole->id;
    }

    $adminRole = DB::table('roles')->where('name', $adminRoleName)->first();
    if (! $adminRole) {
      $adminRoleId = DB::table('roles')->insertGetId([
        'name' => $adminRoleName,
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    } else {
      $adminRoleId = $adminRole->id;
    }

    // 3) Attach roles to the admin using updateOrInsert (no hardcoded ids)
    DB::table('admin_role')->updateOrInsert(
      ['admin_id' => $adminId, 'role_id' => $superRoleId],
      [
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
      ]
    );

    // (Optional) If you need the admin to also have normal Admin role:
    // DB::table('admin_role')->updateOrInsert(
    //     ['admin_id' => $adminId, 'role_id' => $adminRoleId],
    //     ['created_by' => 1, 'updated_by' => 1, 'created_at' => now(), 'updated_at' => now()]
    // );
  }
}
