<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Admin'],
            ['name' => 'User'],
            ['name' => 'Guest'],
        ];
        foreach ($data as $role) {
            Role::firstOrCreate($role);
        }
        $this->command->info('Roles seeded successfully!');
    }
}
