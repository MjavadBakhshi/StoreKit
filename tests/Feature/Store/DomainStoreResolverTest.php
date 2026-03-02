<?php

namespace Tests\Feature\Store;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DomainStoreResolverTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function store_data_can_be_resolved_from_cache() :void
    {
        // Implement as developing.
    }

    #[Test]
    public function store_data_is_merged_to_request_object_by_middleware() :void
    {
        // Implement as developing.
    }
}
