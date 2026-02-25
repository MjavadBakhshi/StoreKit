<?php

namespace Tests\Feature\Catalog;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Concerns\AuthenticatedUser;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use RefreshDatabase, AuthenticatedUser;

    function setUp(): void
    {
        $this->actingAsUser();
    }

    #[Test]
    function create_product_successfully()
    {

    }

    #[Test]
    function create_product_requires_fields()
    {


    }
    #[Test]
    function user_can_only_create_product_in_own_stores()
    {

    }
    
    #[Test] 
    function user_cannot_create_product_in_other_users_stores()
    {

    }

}
