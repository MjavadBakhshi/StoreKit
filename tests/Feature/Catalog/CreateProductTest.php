<?php

namespace Tests\Feature\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

use Domain\Account\Models\User;
use Domain\Catalog\Models\Product;
use Domain\Store\Models\Store;

class CreateProductTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    protected Store $store;

    function setUp(): void
    {
        parent::setUp(); // MUST call this first
       
        $this->actingAsUser();
        $this->store = Store::factory()->for($this->user)->create();
    }

    #[Test]
    function create_new_product_successfully()
    {
        $response = $this->getNewProductResponse();

        $response->assertStatus(200);

        $response->assertJson([
            'ok' => true,
            'data' => [
                'product' => $this->getDefaultProductData()
            ]
        ]);
    }

    #[Test]
    function create_new_draft_product_successfully()
    {
        // Default product status is always draft.
        // So store owner can update stock, price and images later.
        // It provides flexibility during inserting products.
        // variations can be also added later.
        $response = $this->getNewProductResponse([
            ...$this->getDefaultProductData(),
            // validating optional values for default variant.
            'stock' => 0,
            'price' => null
        ]);

        $response->assertStatus(200);

        // Check the variant record has been labeld as deafult
        $publicId = $response->json('data.product.public_id');
        $product = Product::with('variants')->where('public_id', $publicId)->first();
        $this->assertEquals(true, $product->variants[0]->is_default_variant);
    }


    #[Test]
    function create_new_product_requires_fields()
    {
        $productData = [
            'description' => 'High quality t-shirt'
        ];

        $response = $this->getNewProductResponse($productData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'title', 
            'slug'
        ]);
    }

    #[Test]
    function store_cannot_have_products_with_the_same_slug()
    {
        $response1 = $this->getNewProductResponse();

        $response2 = $this->getNewProductResponse();

        $response1->assertStatus(200);

        $response2->assertStatus(422);

        $response2->assertJsonValidationErrorFor('slug');
       
    }

    #[Test]
    function user_can_only_create_new_product_in_own_stores()
    {
        $secondStore = Store::factory()->for($this->user)->create();

        $responseStore1 = $this->getNewProductResponse();

        $responseStore2 = $this->getNewProductResponse(store: $secondStore);

        $responseStore1->assertStatus(200);

        $responseStore2->assertStatus(200);

    }

    #[Test]
    function user_cannot_create_new_product_in_other_users_stores()
    {
        $otherUser = User::factory()->create();

        $otherUserStore = Store::factory()
            ->for($otherUser)
            ->create();

        $response = $this->getNewProductResponse(store: $otherUserStore);

        // Assert the response is a 403 Forbidden
        $response->assertStatus(403);

    }


    //--------- Helpers methods

    private function getNewProductResponse(
        array|null $productData = null,
        ?Store $store = null
    )
    {
        $productData = $productData ?? $this->getDefaultProductData();
        $store = $store ?? $this->store;

        return $this->postJson(
            route('products.store', $store->public_id),
            $productData
        );
    }

    function getDefaultProductData() :array
    {
        return [
            'title' =>  'Pull and bear T-shirt',
            'slug' => 'pull-and-bear-t-shirt',
            'description' => 'High quality t-shirt',
            'stock' => 200,
            'price' => 1999.9
        ];
    }

}
