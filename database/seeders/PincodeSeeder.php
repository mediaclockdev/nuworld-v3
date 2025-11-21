<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PincodeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('pincodes')->insert([
      [
        'id' => 1,
        'code' => '700001',
        'status' => 1,
        'estimate_days' => '4 to 5 days',
        'created_at' => '2025-05-27 23:28:16',
        'updated_at' => '2025-05-28 01:34:58',
        'deleted_at' => null,
      ],
      [
        'id' => 2,
        'code' => '700005',
        'status' => 1,
        'estimate_days' => '5 days',
        'created_at' => '2025-05-27 23:28:16',
        'updated_at' => '2025-05-27 23:28:16',
        'deleted_at' => null,
      ],
      [
        'id' => 3,
        'code' => '700009',
        'status' => 1,
        'estimate_days' => '3 weeks',
        'created_at' => '2025-05-27 23:28:16',
        'updated_at' => '2025-05-27 23:28:16',
        'deleted_at' => null,
      ],
      [
        'id' => 4,
        'code' => '700013',
        'status' => 1,
        'estimate_days' => '6-8 days',
        'created_at' => '2025-05-28 01:33:08',
        'updated_at' => '2025-05-28 04:19:07',
        'deleted_at' => null,
      ],
      [
        'id' => 5,
        'code' => '700017',
        'status' => 1,
        'estimate_days' => '8-7 days',
        'created_at' => '2025-05-28 04:31:46',
        'updated_at' => '2025-05-28 04:45:01',
        'deleted_at' => null,
      ],
      [
        'id' => 6,
        'code' => '700021',
        'status' => 1,
        'estimate_days' => '8-7 days',
        'created_at' => '2025-05-28 04:48:08',
        'updated_at' => '2025-05-28 04:48:08',
        'deleted_at' => null,
      ],
      [
        'id' => 7,
        'code' => '700031',
        'status' => 1,
        'estimate_days' => '4-5 days',
        'created_at' => '2025-05-28 04:54:28',
        'updated_at' => '2025-05-28 04:54:28',
        'deleted_at' => null,
      ],
      [
        'id' => 8,
        'code' => '700037',
        'status' => 1,
        'estimate_days' => '3-5 days',
        'created_at' => '2025-05-28 04:55:27',
        'updated_at' => '2025-05-28 05:06:37',
        'deleted_at' => null,
      ],
      [
        'id' => 9,
        'code' => '700041',
        'status' => 1,
        'estimate_days' => '4-5 days',
        'created_at' => '2025-05-30 01:25:55',
        'updated_at' => '2025-05-30 01:25:55',
        'deleted_at' => null,
      ],
      [
        'id' => 10,
        'code' => '700045',
        'status' => 1,
        'estimate_days' => '3-8 days',
        'created_at' => '2025-05-30 01:25:55',
        'updated_at' => '2025-05-30 01:25:55',
        'deleted_at' => null,
      ],
      [
        'id' => 11,
        'code' => '700054',
        'status' => 1,
        'estimate_days' => '7-8 days',
        'created_at' => '2025-05-30 01:25:55',
        'updated_at' => '2025-05-30 01:25:55',
        'deleted_at' => null,
      ],
      [
        'id' => 12,
        'code' => '700063',
        'status' => 1,
        'estimate_days' => '9-10 days',
        'created_at' => '2025-05-30 01:25:55',
        'updated_at' => '2025-05-30 01:25:55',
        'deleted_at' => null,
      ],
      [
        'id' => 13,
        'code' => '700070',
        'status' => 1,
        'estimate_days' => '10-15 days',
        'created_at' => '2025-05-30 01:25:55',
        'updated_at' => '2025-05-30 01:25:55',
        'deleted_at' => null,
      ],
      [
        'id' => 14,
        'code' => '700086',
        'status' => 1,
        'estimate_days' => '1-5 days',
        'created_at' => '2025-05-30 01:25:55',
        'updated_at' => '2025-05-30 01:25:55',
        'deleted_at' => null,
      ],
      [
        'id' => 15,
        'code' => '700075',
        'status' => 1,
        'estimate_days' => '5-10 days',
        'created_at' => '2025-05-30 01:25:55',
        'updated_at' => '2025-05-30 01:25:55',
        'deleted_at' => null,
      ],
    ]);
  }
}
