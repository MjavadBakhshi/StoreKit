<?php

namespace Tests\Feature\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

use Domain\Catalog\Models\Product;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
    }

    #[Test]
    function store_owner_can_soft_delete_a_product()
    {
        $product = Product::factory()->create([
            'store_id' => $this->store->id
        ]);

        $deleteResponse = $this->deleteJson(
            route('products.destroy', $product)
        );

        $deleteResponse->assertStatus(200);
        $publicId = $deleteResponse->json('data.public_id');

        $productModel = Product::withTrashed()
                        ->where('public_id', $publicId)
                        ->first();

        $this->assertTrue(!is_null($productModel->deleted_at));
    }
}
