<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $states = [
      // Indian States
      ['name' => 'Andhra Pradesh', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Arunachal Pradesh', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Assam', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Bihar', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Chhattisgarh', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Goa', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Gujarat', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Haryana', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Himachal Pradesh', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Jharkhand', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Karnataka', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Kerala', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Madhya Pradesh', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Maharashtra', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Manipur', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Meghalaya', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Mizoram', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Nagaland', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Odisha', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Punjab', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Rajasthan', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Sikkim', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Tamil Nadu', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Telangana', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Tripura', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Uttar Pradesh', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'Uttarakhand', 'country_id' => 101, 'created_by' => 1],
      ['name' => 'West Bengal', 'country_id' => 101, 'created_by' => 1],

      // US States
      ['name' => 'Alabama', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Alaska', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Arizona', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Arkansas', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'California', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Colorado', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Connecticut', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Delaware', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Florida', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Georgia', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Hawaii', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Idaho', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Illinois', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Indiana', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Iowa', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Kansas', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Kentucky', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Louisiana', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Maine', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Maryland', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Massachusetts', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Michigan', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Minnesota', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Mississippi', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Missouri', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Montana', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Nebraska', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Nevada', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'New Hampshire', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'New Jersey', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'New Mexico', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'New York', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'North Carolina', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'North Dakota', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Ohio', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Oklahoma', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Oregon', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Pennsylvania', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Rhode Island', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'South Carolina', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'South Dakota', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Tennessee', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Texas', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Utah', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Vermont', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Virginia', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Washington', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'West Virginia', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Wisconsin', 'country_id' => 230, 'created_by' => 1],
      ['name' => 'Wyoming', 'country_id' => 230, 'created_by' => 1],
    ];

    DB::table('states')->insert($states);
  }
}
