<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $rolesCount = Role::count();

        foreach (range(1, 100) as $index) {
            Contact::firstOrCreate([
                'role_id' => rand(1, $rolesCount),
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'company' => $faker->company,
            ]);
        }

        $this->command->info('Contacts seeded successfully!');
    }
}
