<?php

namespace Tests\Feature\Catalog;

use Domain\Catalog\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;
   

    function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
    }

    #[Test]
    function can_update_a_product_successfully()
    {}

    #[Test]
    function can_update_default_variant_data()
    {}
}
