<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Domain\Catalog\Enums\{ProductStatus, ProductType};
use Domain\Catalog\Models\Product;
use Domain\Store\Models\Store;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->domainName(),
            'slug' => str_replace(' ', '-', fake()->unique()->sentence(2)),
            'store_id' => Store::factory()->create(),
            'status' => ProductStatus::Active,
            'product_type' => fake()->randomElement(ProductType::values())
        ];
    }
}
