<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{Storage, File};

class StoreSeeder extends Seeder
{
    public function run()
    {
        DB::table('stores')->truncate();

        $countryIds = DB::table('countries')->pluck('id')->toArray();
        if (empty($countryIds)) {
            $countryIds = [1];
        }

        $stores = [
            [
                'name'       => 'Kolkata Central Mart',
                'address'    => 'Park Street, Kolkata, West Bengal',
                'country_id' => $countryIds[array_rand($countryIds)],
                'state'      => 'West Bengal',
                'city'       => 'Kolkata',
                'pincode'    => '700016',
                'latitude'   => 22.5726,
                'longitude'  => 88.3639,
                'location'   => 'https://www.google.com/maps?q=22.5726,88.3639',
                'phone'      => '+91 9876543210',
                'image'      => 'store1.jpg',
                'status'     => 1,
            ],
            [
                'name'       => 'Mumbai Super Outlet',
                'address'    => 'Bandra West, Mumbai, Maharashtra',
                'country_id' => $countryIds[array_rand($countryIds)],
                'state'      => 'Maharashtra',
                'city'       => 'Mumbai',
                'pincode'    => '400050',
                'latitude'   => 19.0760,
                'longitude'  => 72.8777,
                'location'   => 'https://www.google.com/maps?q=19.0760,72.8777',
                'phone'      => '+91 9123456780',
                'image'      => 'store2.jpg',
                'status'     => 1,
            ],
            [
                'name'       => 'Delhi Mega Hub',
                'address'    => 'Connaught Place, New Delhi',
                'country_id' => $countryIds[array_rand($countryIds)],
                'state'      => 'Delhi',
                'city'       => 'New Delhi',
                'pincode'    => '110001',
                'latitude'   => 28.6139,
                'longitude'  => 77.2090,
                'location'   => 'https://www.google.com/maps?q=28.6139,77.2090',
                'phone'      => '+91 9988776655',
                'image'      => 'store3.jpg',
                'status'     => 1,
            ],
            [
                'name'       => 'Chennai Express Store',
                'address'    => 'T Nagar, Chennai, Tamil Nadu',
                'country_id' => $countryIds[array_rand($countryIds)],
                'state'      => 'Tamil Nadu',
                'city'       => 'Chennai',
                'pincode'    => '600017',
                'latitude'   => 13.0827,
                'longitude'  => 80.2707,
                'location'   => 'https://www.google.com/maps?q=13.0827,80.2707',
                'phone'      => '+91 9090909090',
                'image'      => 'store4.jpg',
                'status'     => 1,
            ],
        ];

        foreach ($stores as &$store) {
            $store['created_by'] = null;
            $store['updated_by'] = null;
            $store['deleted_by'] = null;
            $store['created_at'] = now();
            $store['updated_at'] = now();
            $store['deleted_at'] = null;
        }

        DB::table('stores')->insert($stores);

        $this->uploadBannerImages();
    }


    private function uploadBannerImages()
    {
      // Ensure destination directory exists
      Storage::disk('public')->makeDirectory('uploads/stores');

      $destinationPath = storage_path('app/public/uploads/stores');
      $sourcePath = public_path('SeederImages/Store');


      // Remove all files in destination folder
      if (File::exists($destinationPath)) {
        File::deleteDirectory($destinationPath);
        Storage::disk('public')->makeDirectory('uploads/stores');
      }

      //  Copy files from source to destination
      if (File::exists($sourcePath)) {
        $files = File::files($sourcePath);

        foreach ($files as $file) {
          File::copy($file->getPathname(), $destinationPath . '/' . $file->getFilename());
        }
      }
    }
}
