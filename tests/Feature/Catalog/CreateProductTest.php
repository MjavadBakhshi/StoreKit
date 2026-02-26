<?php

namespace Tests\Feature\Catalog;

use Domain\Account\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

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
    function create_new_product_requires_fields()
    {
        $productData = [
            'description' => 'High quality t-shirt'
        ];

        $response = $this->getNewProductResponse($productData);

        $response = $this->postJson(
            "api/v1/stores/{$this->store->public_id}/product",
            $productData
        );

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
    

    function user_cannot_create_new_product_in_other_users_stores()
    {
        $otherUser = User::factory()->create();

        $otherUserStore = Store::factory()
            ->for($otherUser)
            ->create();

        $response = $this->getNewProductResponse(store: $otherUserStore);

        // writing policy assertions here
    }


    //--------- Helpers methods

    private function getNewProductResponse(
        array|null $proudctData = null,
        ?Store $store = null
    )
    {
        $productData = $productData ?? $this->getDefaultProductData();
        $store = $store ?? $this->store;

        return $this->postJson(
            "api/v1/stores/{$store->public_id}/product",
            $productData
        );
    }

    function getDefaultProductData() :array
    {
        return [
            'title' =>  'Pull and bear T-shirt',
            'slug' => 'pull-and-bear-t-shirt',
            'description' => 'High quality t-shirt'
        ];
    }

}
