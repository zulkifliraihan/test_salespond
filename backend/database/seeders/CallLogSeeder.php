<?php

namespace Database\Seeders;

use App\Enums\StatusCallLogEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CallLog;
use App\Models\Contact;
use Faker\Factory as Faker;

class CallLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $contactsCount = Contact::count();

        foreach (range(1, 100) as $index) {
            CallLog::firstOrCreate([
                'contact_id' => rand(1, $contactsCount),
                'duration' => $faker->numberBetween(1, 10),
                'status' => StatusCallLogEnum::random()->value,
            ]);
        }
    }
}
