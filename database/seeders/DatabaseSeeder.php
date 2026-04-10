<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User Seeder
        $user = User::create([
            'username' => 'admin',
            'email' => 'admin@themesbrand.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'avatar' => null,
            'created_at' => now(),
        ]);

        // User Profile Seeder
        UserProfile::create([
            'user_id' => $user->id,
            'full_name' => 'Admin User',
            'bio' => 'This is the admin user profile.',
            'phone' => '123-456-7890',
            'address' => '123 Admin Street, City, Country',
            'job_title' => 'Administrator',
            'company' => 'Admin Company',
            'created_at' => now(),
        ]);

        $this->call(RbacSeeder::class);
    }
}
