<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\UserPackage;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Clean existing data
        UserPackage::truncate(); // clears user_packages table
        Package::truncate();     // clears packages table

        // Step 2: Define new packages
        $packages = [
            [
                'name' => 'StartUp',
                'price' => 500,
                'description' => 'Za manja preduzeća',
                'available_scans' => 100,
                'page_limit' => 50,
                'document_history' => 200,
                'icon' => 'ri-star-s-fill',
                'duration' => 30,
                'speed_limit' => '20 s'
            ],
            [
                'name' => 'GoBig',
                'price' => 850,
                'description' => 'Idealno za biznise u razvoju',
                'available_scans' => 200,
                'page_limit' => 150,
                'document_history' => 500,
                'icon' => 'ri-medal-line',
                'duration' => 120,
                'speed_limit' => '10 s'
            ],
            [
                'name' => 'Business',
                'price' => 2000,
                'description' => 'Skrojeno za velike biznise',
                'available_scans' => 500,
                'page_limit' => 9999,
                'document_history' => 9999,
                'icon' => 'ri-shield-star-line',
                'duration' => 365,
                'speed_limit' => '4 s'
            ],
        ];


        // Step 3: Insert packages into DB
        foreach ($packages as $data) {
            Package::create($data);
        }

        $this->command->info('Packages seeded and UserPackages wiped.');
    }
}
