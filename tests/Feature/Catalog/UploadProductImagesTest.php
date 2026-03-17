<?php

namespace Tests\Feature\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

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

    }

}
