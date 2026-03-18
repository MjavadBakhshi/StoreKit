<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Domain\FileManager\Models\FileUpload;
use Domain\Store\Models\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FileUploadFactory extends Factory
{

    protected $model = FileUpload::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'original_name' => fake()->domainName(),
            'stored_name' => fake()->unique()->domainName(),
            'is_private' => false,
            'store_id' => Store::factory()->create(),
            'size' => rand(1, 10) * 1024,
        ];
    }
}
