<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PersonalAccessTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user
        $user = User::firstOrCreate([
            'email' => 'admin@themesbrand.com'
        ], [
            'name' => 'admin',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'avatar' => 'avatar-1.jpg',
            'created_at' => now(),
        ]);

        // Generate a Personal Access Token
        $token = $user->createToken('PersonalAccessToken')->plainTextToken;

        // Display the token in console
        $this->command->info("Personal Access Token: " . $token);
    }
}
