<?php

namespace Tests\Feature\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

use Domain\Catalog\Enums\ProductType;
use Domain\FileManager\Models\FileUpload;
use Domain\Catalog\Models\Product;

class UploadProductImagesTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    function setUp(): void
    {
        parent::setUp();
        $this->actingAsUser();

        $this->store->update([
            # IN KB
            'max_storage_capacity' => 10 * 1024,
            'free_storage_capacity' => 10 * 1024,
        ]);

        Storage::fake('products');
    }

    #[Test]
    public function can_create_new_product_with_multiple_iamges_successfully(): void
    {
        $files = [
            ['name' => 'file1.png', 'size' => 1024, 'type' => 'image/png'],
            ['name' => 'file2.jpg', 'size' => 2048, 'type' => 'image/jpeg'],
        ];

        // Making fake files.
        $fileFactory = new FileFactory;
        foreach ($files as $file) {
            // $content = Str::random($file['size']);
            $fakeFiles[] = $fileFactory->create(
                name: $file['name'],
                kilobytes: $file['size'],
                mimeType: $file['type']
            );
        }

        $response = $this->post(
            route('products.store'),
            [
                'title' => 'Iphone 18',
                'slug' => 'Iphone-18',
                'default_variant' => [
                    'stock' => 200,
                ],
                'product_type' => ProductType::Physical->value,
                'images' => $fakeFiles
            ],
            ['Accept' => 'application/json']
        ); 

        $response->assertStatus(200);

        $productPublicId = $response->json('data.product.public_id');
        $productImages = Product::where('public_id', $productPublicId)->value('images');
        $storedFiles = FileUpload::whereIn('id', $productImages)->get();
        $this->assertEquals(
            collect($files)->pluck('name')->toArray(),
            $storedFiles->pluck('original_name')->toArray()
        );
    }

}
