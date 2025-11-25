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
    $now = now();

    // 1) Ensure admins with explicit ids exist (1..7). id=1 uses your real info.
    $adminsToEnsure = [
      1 => [
        'first_name' => 'Sourav',
        'middle_name' => '',
        'last_name' => 'Bhowmik',
        'email' => 'sb@mediaclock.com.au',
        'phone' => '9433857585',
      ],
      // others are minimal seed admins
      2 => ['first_name' => 'Ritu', 'last_name' => 'Admin', 'email' => 'ritu@mediaclock.com.au'],
      3 => ['first_name' => 'Seed', 'last_name' => 'Admin3', 'email' => 'seed-admin-3@example.com'],
      4 => ['first_name' => 'Seed', 'last_name' => 'Admin4', 'email' => 'seed-admin-4@example.com'],
      5 => ['first_name' => 'Seed', 'last_name' => 'Admin5', 'email' => 'seed-admin-5@example.com'],
      6 => ['first_name' => 'Seed', 'last_name' => 'Admin6', 'email' => 'seed-admin-6@example.com'],
      7 => ['first_name' => 'Seed', 'last_name' => 'Admin7', 'email' => 'seed-admin-7@example.com'],
    ];

    foreach ($adminsToEnsure as $id => $data) {
      $exists = DB::table('admins')->where('id', $id)->exists();

      if (! $exists) {
        // avoid email collisions: if email already exists for another id, map to that id instead of inserting duplicate
        $email = $data['email'] ?? "seed-admin-{$id}@example.com";
        $existingByEmail = DB::table('admins')->where('email', $email)->first();

        if ($existingByEmail) {
          // If an admin with that email already exists, we won't try to reuse the explicit id.
          continue;
        }

        $insert = [
          'id' => $id,
          'first_name'  => $data['first_name'] ?? 'Seed',
          'middle_name' => $data['middle_name'] ?? '',
          'last_name'   => $data['last_name'] ?? ("Admin{$id}"),
          'email'       => $email,
          'phone'       => $data['phone'] ?? '0000000000',
          'password'    => $defaultPassword,
          'status'      => 1,
          'created_by'  => null,
          'updated_by'  => null,
          'created_at'  => $now,
          'updated_at'  => $now,
        ];

        DB::table('admins')->insert($insert);
      }
    }

    // bump AUTO_INCREMENT above highest seeded id to avoid collisions later
    try {
      $maxId = max(array_keys($adminsToEnsure));
      $tableName = DB::getTablePrefix() . 'admins';
      DB::statement("ALTER TABLE {$tableName} AUTO_INCREMENT = " . ($maxId + 1));
    } catch (\Throwable $e) {
      // ignore if not supported
    }

    // 2) Ensure roles exist and capture their ids
    $roleNames = ['Super Admin', 'Admin'];
    $roleIds = [];

    foreach ($roleNames as $roleName) {
      // use updateOrInsert to be idempotent
      DB::table('roles')->updateOrInsert(
        ['name' => $roleName],
        [
          'created_by' => 1,
          'updated_by' => 1,
          'created_at' => $now,
          'updated_at' => $now,
        ]
      );

      $roleIds[$roleName] = DB::table('roles')->where('name', $roleName)->value('id');
    }

    // 3) Insert admin_role mappings only for admins that exist
    $adminsExisting = DB::table('admins')->pluck('id')->toArray();

    // Attach Super Admin role to admins 1..7 if they exist
    $superRoleId = $roleIds['Super Admin'] ?? null;
    if ($superRoleId) {
      foreach ($adminsToEnsure as $id => $_) {
        if (in_array($id, $adminsExisting, true)) {
          DB::table('admin_role')->updateOrInsert(
            ['admin_id' => $id, 'role_id' => $superRoleId],
            [
              'created_by' => 1,
              'updated_by' => 1,
              'created_at' => $now,
              'updated_at' => $now,
            ]
          );
        }
      }
    }

    // Optionally: attach the plain 'Admin' role to admin 1 (or others) if you want
    $adminRoleId = $roleIds['Admin'] ?? null;
    if ($adminRoleId && in_array(1, $adminsExisting, true)) {
      DB::table('admin_role')->updateOrInsert(
        ['admin_id' => 1, 'role_id' => $adminRoleId],
        [
          'created_by' => 1,
          'updated_by' => 1,
          'created_at' => $now,
          'updated_at' => $now,
        ]
      );
    }
  }
}
