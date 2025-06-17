<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            ContactSeeder::class,
            CallLogSeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
    }
}
