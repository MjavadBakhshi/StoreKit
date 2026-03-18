<?php

namespace Tests\Feature\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

use Domain\Catalog\Enums\ProductType;
use Domain\Catalog\Models\{Product, ProductVariant};
use Domain\FileManager\Models\FileUpload;

class UpsertProductVariantTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
    }

    #[Test]
    function insert_new_variant_successfully()
    {
        $randomVariantData = $this->getRandomVariantData();
        list($product, $response) = $this->createNewVariant($randomVariantData);
        
        $response->assertStatus(200);

        $response->assertJson([
            'ok' => true,
            'data' => [
                'product_variant' => $randomVariantData
            ]
        ]);
    }

    #[Test]
    function update_a_variant_successfully()
    {
        $randomVariantData = $this->getRandomVariantData();
        list($product, $variantResponse) = 
        $this->createNewVariant(
            $randomVariantData,
            ProductType::Physical
        );

        $variantPublicId = $variantResponse->json('data.product_variant.public_id');
        
        $updatingData = [
            'stock' => 200,
            'price' => 1500000,
            'attributes' => [
                'size' => 'Medium',
                'df-store-weight' => 500,
            ]
        ];
       
        $updateResponse = $this->putJson(
            route('products.variants.update', [$product, $variantPublicId]),
            [...$updatingData]
        );

        $updateResponse->assertStatus(200);
        $updateResponse->assertJson([
            'ok' => true,
            'data' => [
                'product_variant' => [
                    ...$updatingData
                ]
            ]
        ]);
    }

    #[Test]
    function soft_delete_a_variant_successfully()
    {
        $randomProductVariant = $this->getRandomVariantData();
        list($product, $response) = $this->createNewVariant($randomProductVariant);
        $variantPublicId = $response->json('data.product_variant.public_id');

        $deleteResponse = $this->deleteJson(
            route('products.variants.destroy', [$product, $variantPublicId])
        );

        $deleteResponse->assertStatus(200);
        $variantModel = ProductVariant::withTrashed()
                        ->where('public_id', $variantPublicId)
                        ->first();
        $this->assertTrue(!is_null($variantModel->deleted_at));
    }

    #[Test]
    function store_owner_cannot_do_crud_on_other_store_products()
    {
        $newProduct = Product::factory()->create();
        $randomVariantData = $this->getRandomVariantData();

        $response = $this->postJson(
            route('products.variants.store', $newProduct),
            $randomVariantData
        );

        $response->assertStatus(403);
    }

    #[Test]
    function set_an_image_for_a_variant_successfully()
    {
        // Add some images to the product.
        $productImages = FileUpload::factory(3)->create([
            'store_id' => $this->store->id
        ]);

        $product = Product::factory()->create([
            'store_id' => $this->store->id,
            'images' => $productImages->pluck('id')->toArray()
        ]);


        $variantData = [
            ...$this->getRandomVariantData(),
            # Choosing first image of the product
            'image_id' => $productImages[0]->public_id
        ];

        $response = $this->postJson(
            route('products.variants.store', $product),
            $variantData
        );

        $response->assertStatus(200);

        $imageId = $response->json('data.product_variant.image_id');
        $this->assertEquals($productImages[0]->public_id, $imageId);

    }

    private function createNewVariant(array $data, ?ProductType $productType = null)
    {
        $productData = ['store_id' => $this->store->id];
        if($productType)
            $productData['product_type'] = $productType;

        $product = Product::factory()->create($productData);

        // The request is being tried to call with other's product
        // This test actually validates the EntityStoreChecker middleware.
        $response = $this->postJson(
            route('products.variants.store', $product),
            $data
        );

        return [$product, $response];
    }

    private function getRandomVariantData() :array
    {
        return   [
            'price' => rand(5, 20) * 1000000,
            'discounted_price' => rand(1, 4) * 1000000,
            'stock' => rand(1, 100),
            'attributes' => [
                'color' => 'red',
                'size' => 'Large',
            ],
        ];
    }
}
