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
        //Package::truncate();     // clears packages table
        DB::table('packages')->delete(); // safer for foreign key constraints

        // Step 2: Define new packages
        $packages = [
            [
                'name' => 'XS',
                'price' => 180.00,
                'price_monthly' => 180.00,
                'price_yearly' => round(180.00 * 12 * 0.85, 2),
                'token_price' => 0.60,
                'description' => 'Za mikro preduzeća i samostalne korisnike koji žele isprobati AI skeniranje deklaracija bez velikih troškova.',
                'available_scans' => 300,
                'page_limit' => 5,
                'document_history' => 90, // 3x (300/10)
                'declaration_token_cost' => 10,
                'declaration_price' => 6.00,
                'extra_page_price' => 0.60,
                'simultaneous_logins' => 2,
                'icon' => 'ri-star-s-fill', // XS icon
                'duration' => 30,
                'speed_limit' => '20 s',
            ],
            [
                'name' => 'S',
                'price' => 300.00,
                'price_monthly' => 300.00,
                'price_yearly' => round(300.00 * 12 * 0.85, 2),
                'token_price' => 0.50,
                'description' => 'Savršen izbor za male timove i startupe koji žele ubrzati obradu deklaracija uz povoljnu cijenu.',
                'available_scans' => 600,
                'page_limit' => 5,
                'document_history' => 180, // 3x (600/10)
                'declaration_token_cost' => 10,
                'declaration_price' => 5.00,
                'extra_page_price' => 0.50,
                'simultaneous_logins' => 3,
                'icon' => 'ri-medal-line', // S icon
                'duration' => 30,
                'speed_limit' => '15 s',
            ],
            [
                'name' => 'M',
                'price' => 400.00,
                'price_monthly' => 400.00,
                'price_yearly' => round(400.00 * 12 * 0.85, 2),
                'token_price' => 0.40,
                'description' => 'Optimalno rješenje za rastuće firme kojima je potrebna pouzdana i brza AI automatizacija deklaracija.',
                'available_scans' => 1000,
                'page_limit' => 5,
                'document_history' => 300, // 3x (1000/10)
                'declaration_token_cost' => 10,
                'declaration_price' => 4.00,
                'extra_page_price' => 0.40,
                'simultaneous_logins' => 5,
                'icon' => 'ri-shield-star-line', // M icon
                'duration' => 30,
                'speed_limit' => '10 s',
            ],
            [
                'name' => 'L',
                'price' => 1050.00,
                'price_monthly' => 1050.00,
                'price_yearly' => round(1050.00 * 12 * 0.85, 2),
                'token_price' => 0.35,
                'description' => 'Namijenjen ambicioznim kompanijama koje obrađuju veći broj deklaracija i žele vrhunsku efikasnost.',
                'available_scans' => 3000,
                'page_limit' => 5,
                'document_history' => 900, // 3x (3000/10)
                'declaration_token_cost' => 10,
                'declaration_price' => 3.50,
                'extra_page_price' => 0.35,
                'simultaneous_logins' => 10,
                'icon' => 'ri-trophy-line', // L icon
                'duration' => 30,
                'speed_limit' => '8 s',
            ],
            [
                'name' => 'XL',
                'price' => 3000.00,
                'price_monthly' => 3000.00,
                'price_yearly' => round(3000.00 * 12 * 0.85, 2),
                'token_price' => 0.30,
                'description' => 'Za velike firme koje zahtijevaju napredne mogućnosti, visok kapacitet i maksimalnu fleksibilnost u radu.',
                'available_scans' => 10000,
                'page_limit' => 5,
                'document_history' => 3000, // 3x (10000/10)
                'declaration_token_cost' => 10,
                'declaration_price' => 3.00,
                'extra_page_price' => 0.30,
                'simultaneous_logins' => 15,
                'icon' => 'ri-building-line', // XL icon
                'duration' => 30,
                'speed_limit' => '6 s',
            ],
            [
                'name' => 'XXL',
                'price' => 3750.00,
                'price_monthly' => 3750.00,
                'price_yearly' => round(3750.00 * 12 * 0.85, 2),
                'token_price' => 0.25,
                'description' => 'Enterprise paket za kompanije s najvišim zahtjevima – neograničene mogućnosti, premium podrška i maksimalna sigurnost.',
                'available_scans' => 15000,
                'page_limit' => 5,
                'document_history' => 4500, // 3x (15000/10)
                'declaration_token_cost' => 10,
                'declaration_price' => 2.50,
                'extra_page_price' => 0.25,
                'simultaneous_logins' => 20,
                'icon' => 'ri-award-line', // XXL icon
                'duration' => 30,
                'speed_limit' => '4 s',
            ],
        ];


        // Step 3: Insert packages into DB
        foreach ($packages as $data) {
            Package::create($data);
        }

        $this->command->info('Packages seeded and UserPackages wiped.');
    }
}
