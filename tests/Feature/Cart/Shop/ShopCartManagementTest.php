<?php

namespace Tests\Feature\Cart\Shop;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use Domain\Catalog\Models\{Product, ProductVariant};
use Domain\Shared\Actions\SessionAction;

class ShopCartManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Collection $products;
    protected SessionAction $sessionManagr;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->products = Product::factory(2)
            // Creating default variant for the product.
            ->afterCreating(
                fn($product) => 
                ProductVariant::factory()
                ->create(['product_id' => $product->id])
            )->create();
        

        // Reseting session before each test.
        $this->sessionManagr = app(SessionAction::class);
        $this->sessionManagr->start();
        $this->sessionManagr->forget('cart');
        // Reseting cache before each test to prevent confilicts in domain-store resolving.
        Cache::flush();
    }

    #[Test]
    function customer_can_add_an_active_item_to_cart_successfully()
    {
        $product = $this->products[0];
        $productVariant = $product->variants[0];
        $quantity = 2;
        
        $response = $this->updateCart(
            $productVariant,
            $quantity
        );

        $response->assertStatus(200);

        // Checking session data
        $cart = $response->json('data.cart');
        $this->assertTrue(isset($cart[$productVariant->public_id]));
        $this->assertEquals($quantity, $cart[$productVariant->public_id]['quantity']);
    }   


    #[Test]
    function customer_can_update_an_existing_item_in_the_cart()
    {
        $product = $this->products[0];
        $productVariant = $product->variants[0];
        $quantity = 2;

        $response = $this->updateCart(
            $productVariant, 
            $quantity
        );

        $response->assertStatus(200);

        // Current quantity is 2, Now increase to 7
        $response = $this->updateCart(
            $productVariant,
            quantity: 5
        );

        $quantity += 5;

        $cart = $response->json('data.cart');
        $this->assertEquals($quantity, $cart[$productVariant->public_id]['quantity']);
        

        // Current quantity = 7, Now decreas to 3
        $response = $this->updateCart(
            $productVariant,
            quantity: -4
        );

        $response->assertStatus(200);

        $quantity -= 4;
        $cart = $response->json('data.cart');
        $this->assertEquals($quantity, $cart[$productVariant->public_id]['quantity']);

    }   
        

    #[Test]
    function cart_item_is_removed_when_the_quantity_is_zerro()
    {
        $product = $this->products[0];
        $productVariant = $product->variants[0];
        $quantity = 2;

        $response = $this->updateCart(
            $productVariant, 
            $quantity
        );

        $response->assertStatus(200);

        // Now make the quanity zero:
        $response = $this->updateCart(
            $productVariant,
            quantity: -2
        );

        $response->assertStatus(200);
    
        // Check the cart item has been removed successfully ?
        $cart = $this->sessionManagr->get('cart');
        $this->assertFalse(isset($cart[$productVariant->id]));

    }

    #[Test]
    function cart_data_is_separated_per_domain()
    {
        $quantity = 2;

        // Add cart item in different store.
        foreach($this->products as $product)
        { 
            $productVariant = $product->variants[0];

            $response = $this->updateCart(
                $productVariant,
                $quantity
            );

            $response->assertStatus(200);
        }
        
        // get whole session data.
        $sessionData = ($this->sessionManagr->all());

        // Checking data is stored separately per store?
        foreach($this->products as $product)
        {
            $sessionKey = 'store:'.str_replace('.', '*', $product->store->domain_name).':cart';
            $productVariant = $product->variants[0];

            $this->assertEquals(
                $quantity,
                $sessionData[$sessionKey][$productVariant->id]
            );
        }
    }

    private function updateCart(
        ProductVariant $productVariant,
        int $quantity
    )
    {
        $store = $productVariant->product->store;
        $url = "http://{$store->domain_name}/api/v1/shop/carts/$productVariant->public_id";

        return $this->postJson(
            $url,
            ['quantity' => $quantity]
        );
    }
    
}
