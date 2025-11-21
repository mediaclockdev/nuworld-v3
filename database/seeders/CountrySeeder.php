<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $countries = json_decode(file_get_contents(resource_path('data/countries.json')), true);
    foreach ($countries as $country) {
      \App\Models\Country::create($country);
    }
  }
}
