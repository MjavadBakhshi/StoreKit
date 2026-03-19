<?php

namespace Tests\Feature\Catalog;

use Database\Factories\FileUploadFactory;
use Domain\Catalog\Enums\SEORobot;
use Domain\Catalog\Models\Product;
use Domain\Catalog\Models\ProductVariant;
use Domain\FileManager\Models\FileUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\FileFactory;
use PHPUnit\Framework\Attributes\Test;
use Spatie\LaravelData\Attributes\Validation\In;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;
   

    function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
        $this->store->update([
            'max_storage_capacity' => 100 * 1024, 
            'free_storage_capacity' => 100 * 1024, 
        ]);
    }

    #[Test]
    function can_update_a_product_successfully()
    {

        // Make a product record with some uploaded images.
        $images = FileUpload::factory(3)->create([
            'store_id' => $this->store->id
        ]);

        $product = Product::factory()
        // add default variant to validate updating on default varaint data as well.
        ->afterCreating(
            fn($product) => ProductVariant::factory()->create([
                'product_id' => $product->id,
            ])
        )->create([
            'store_id' => $this->store->id,
            'images' => $images->pluck('id')->toArray()
        ]);

        // Now try to update some additonal files.
        $fakeImage = 
        (new FileFactory)
            ->create('update-image.jpg', 200);

        $updatedFields = [
            'slug' => $product->slug,
            'default_variant' => [
                'stock' => 300,
                'price' => 5000,
                'discounted_price' => 3200,
            ],
            'title' => 'new title',
            'tags' => 'Iphone18,mobile,new iphone',
            'meta_keywords' => 'Iphone, new Iphone, mobile device',
            'meta_description' => "The new iphone model has been released in 26th NOV",
            'page_title' => "Iphone 18. news",
            'canonical_url' => 'https://storekitnews.com/news/iphone18',
            'redirect301_url' => 'https://storekitnews.com/blog/iphone18',
            'robot' => SEORobot::Follow->value,
            // upload another iamges
            'images' => [$fakeImage]
        ];

        $response = $this->put(
            route('products.update', $product),
            $updatedFields,
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        
        // validate new upload file has been added to end of the product images list?
        $updatedProduct = $product->fresh();

        $this->assertEquals(count($product->images) + 1, count($updatedProduct->images));
        // validate seo fields has been updated ?
        $this->assertEquals(collect($updatedFields)->only([
                'meta_keywords',
                'tags',
                'page_title',
                'canonical_url',
                'redirect301_url',
            ])
            ->toArray(),
            $updatedProduct->only([
                'meta_keywords',
                'tags',
                'page_title',
                'canonical_url',
                'redirect301_url',
            ])
        );

        // validate default variant has been updated ?
        $defaultVariantData = 
        $updatedProduct->defaultVariant
            ->only(array_keys($updatedFields['default_variant']));


        $this->assertEquals(
            $updatedFields['default_variant'],
            $defaultVariantData
        );
    }
}
