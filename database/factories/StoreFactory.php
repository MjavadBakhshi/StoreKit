<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Domain\Store\Models\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StoreFactory extends Factory
{

    protected $model = Store::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->domainName(),
            'description' => fake()->text()
        ];
    }
}
