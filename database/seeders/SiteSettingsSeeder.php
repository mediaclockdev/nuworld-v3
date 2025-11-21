<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
  public function run()
  {
    $settings = [
      ['id' => 1, 'key' => 'siteurl', 'value' => 'https://sundew.com/server', 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-08 23:17:38', 'deleted_at' => null],
      ['id' => 2, 'key' => 'sitename', 'value' => 'Sundew Ecomm', 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-08 23:19:20', 'deleted_at' => null],
      ['id' => 3, 'key' => 'site_email', 'value' => 'akd@a.com', 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-08 23:14:05', 'deleted_at' => null],
      ['id' => 4, 'key' => 'site_primary_phone', 'value' => '+911234567890', 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-09 01:37:07', 'deleted_at' => null],
      ['id' => 5, 'key' => 'site_secondary_phone', 'value' => '+917899999745', 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-05-08 23:56:57', 'deleted_at' => null],
      ['id' => 6, 'key' => 'tax_no', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-08 23:14:05', 'deleted_at' => null],
      ['id' => 7, 'key' => 'address1', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-08 23:14:05', 'deleted_at' => null],
      ['id' => 8, 'key' => 'address2', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-08 23:14:05', 'deleted_at' => null],
      ['id' => 9, 'key' => 'landmark', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-08 23:14:05', 'deleted_at' => null],
      ['id' => 10, 'key' => 'city', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:14:05', 'updated_at' => '2025-04-08 23:14:05', 'deleted_at' => null],
      ['id' => 11, 'key' => 'state_id', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 12, 'key' => 'country_id', 'value' => '101', 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 13, 'key' => 'zip_code', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 14, 'key' => 'currency_id', 'value' => '2', 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 15, 'key' => 'facebook_link', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 16, 'key' => 'x_link', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 17, 'key' => 'instagram_link', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 18, 'key' => 'youtube_link', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 19, 'key' => 'linkedin_link', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 20, 'key' => 'google_map', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-08 23:15:34', 'updated_at' => '2025-04-08 23:15:34', 'deleted_at' => null],
      ['id' => 21, 'key' => 'site_logo', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-09 01:58:01', 'updated_at' => '2025-04-24 21:41:33', 'deleted_at' => null],
      ['id' => 22, 'key' => 'two_factor_auth', 'value' => 1, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-09 01:58:01', 'updated_at' => '2025-04-24 21:41:33', 'deleted_at' => null],
      ['id' => 23, 'key' => 'payment_gateway', 'value' => 1, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-09 01:58:01', 'updated_at' => '2025-04-24 21:41:33', 'deleted_at' => null],

      ['id' => 24, 'key' => 'order_copy_to_id', 'value' => 1, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-09 01:58:01', 'updated_at' => '2025-04-24 21:41:33', 'deleted_at' => null],
      ['id' => 25, 'key' => 'threshold_mails_id', 'value' => 1, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-09 01:58:01', 'updated_at' => '2025-04-24 21:41:33', 'deleted_at' => null],
      ['id' => 26, 'key' => 'google_play_link', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-09 01:58:01', 'updated_at' => '2025-04-24 21:41:33', 'deleted_at' => null],
      ['id' => 27, 'key' => 'apple_app_link', 'value' => null, 'status' => 1, 'created_by' => 1, 'updated_by' => 1, 'deleted_by' => null, 'created_at' => '2025-04-09 01:58:01', 'updated_at' => '2025-04-24 21:41:33', 'deleted_at' => null],
    ];

    DB::table('site_settings')->insert($settings);
  }
}
