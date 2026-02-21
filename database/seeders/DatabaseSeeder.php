<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Domain\Account\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => "Daniel Mercer",
            'email' => 'daniel@example.com',
            'password' => '123456'
        ]);

        User::factory()->create([
            'name' => "Sofia Bennett",
            'email' => 'bennett@example.com',
            'password' => '1234'
        ]);


    }
}
