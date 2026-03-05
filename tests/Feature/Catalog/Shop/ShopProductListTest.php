<?php

namespace Tests\Feature\Catalog\Shop;

use Domain\Catalog\Enums\ProductStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use Domain\Catalog\Models\Product;
use Domain\Store\Models\Store;

class ShopProductListTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function customer_can_browse_store_products(): void
    {
        // Insert some fake products.
        $store = Store::factory()->create();
        $products = Product::factory(5)->create([
            'store_id' => $store->id,
        ]);

        // Get list of the store products.
        $response = $this->fetchProducts($store);

        $response->assertStatus(200);
        // If all active products are present in the response list ?
        collect($response->json('data.products'))
            ->each(fn($fetchedProduct) => $this->assertTrue(
                $products->firstWhere('public_id', $fetchedProduct['public_id']) !== null
            ));
    }

    #[Test]
    public function customer_can_browse_correct_store_products() :void
    {
        // Insert some fake products.
        $store = Store::factory()->create();
        $products = Product::factory(5)->create([
            'store_id' => $store->id,
        ]);

        // Insert product for the second store
        $storeTwo = Store::factory()->create();
        $storeTwoProduct = Product::factory()->create([
            'store_id' => $storeTwo->id
        ]);

        // Get list of the first store products.
        $response = $this->fetchProducts($store);

        $response->assertStatus(200);

        $publicIdsResponse = 
        collect($response->json('data.products'))
            ->pluck('public_id')
            ->toArray();

        // If the second store product's public_id is not present
        // in the first store products ?
        $this->assertFalse(
            in_array(
                $storeTwoProduct->public_id, 
                $publicIdsResponse
            )
        );

        // Get list of the second store products.
        $storeTwoResponse = $this->fetchProducts($storeTwo);

        $storeTwoResponse->assertStatus(200);

        $storeTwoProductsResponse = $storeTwoResponse->json('data.products');

        // If only the store two product has been fetched?
        $this->assertEquals(1, count($storeTwoProductsResponse));

        $this->assertEquals(
            $storeTwoProduct->public_id,
            $storeTwoProductsResponse[0]['public_id']
        );
    }

    #[Test]
    public function customer_can_browse_active_products() :void
    {
        // Insert some fake products for each product sttatus (Active, Archive, etc.).
        $store = Store::factory()->create();
        $products = [];
        foreach(ProductStatus::cases() as $productStatus)
        {
            $products[$productStatus->value] = 
            Product::factory()->create([
                'store_id' => $store->id,
                'status' => $productStatus
            ]);
        }

        // Get list of the second store products.
        $activeProductsResponse = $this->fetchProducts($store);

        $activeProduct = $activeProductsResponse->json('data.products.0');

        foreach($products as $status => $notActiveProduct)
        {
            if($status == ProductStatus::Active->value) continue;

            $this->assertTrue(
                $notActiveProduct['public_id'] != $activeProduct['public_id']
            );
        }
    }

    #[Test]
    public function customer_can_visit_a_specific_product() :void
    {

    }




    private function fetchProducts(Store $store)
    {
        // Get list of products.
        return $this->withHeaders([
            'X-Host' => $store->domain_name,
        ])->getJson(route('shop.products.index'));
    }
}
