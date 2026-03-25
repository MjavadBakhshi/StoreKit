<?php

namespace Tests\Feature\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

class UpsertProductCategoryTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    function setUp(): void
    {
        parent::setUp();

        $this->actingAsUser();
    }

    #[Test]
    function create_new_product_category_successfully()
    {}

    #[Test]
    function create_new_sub_product_category_successfully()
    {}

    #[Test]
    function cannot_create_loop_category()
    {}

    #[Test]
    function update_a_product_category_successfulyy()
    {}
}
