<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $user = User::create([
            'name' => 'Developer',
            'nik' => 'dev',
            'password' => bcrypt('dev')
        ]);

        $user->assignRole('dev');
    }
}
