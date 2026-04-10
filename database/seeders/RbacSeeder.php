<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make super-admin role if it doesn't exist
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        // Assign to the first user (assumed to be admin)
        $admin = User::first();
        if ($admin) {
            $admin->assignRole('super-admin');
        }
    }
}
