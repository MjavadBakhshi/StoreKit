<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Domain\Catalog\Models\{Product, ProductVariant};

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => str_replace(' ', '-', fake()->sentence(2)),
            'is_default_variant' => true,
            'price' => fake()->randomFloat(2, 100, 5000),
            'stock' => rand(10, 100),
            'product_id' => Product::factory()->create()
        ];
    }
}
