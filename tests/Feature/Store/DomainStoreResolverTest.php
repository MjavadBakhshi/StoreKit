<?php

namespace Tests\Feature\Store;

use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use Domain\Store\Actions\Shop\DomainStoreResolverAction;
use Domain\Store\Models\Store;

class DomainStoreResolverTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function store_data_cannot_find_when_domain_is_not_set()
    {
        Store::factory()->create([
            'domain_name' => null
        ]);
        
        $response = DomainStoreResolverAction::execute('example.com');
        $this->assertEquals($response, false);
        $this->assertEquals(Cache::get('store_id:example.com'), null);
    }


    #[Test]
    public function store_data_can_be_resolved_from_cache() :void
    {
        $store = Store::factory()->create([
            'domain_name' => 'storekit.com'
        ]);

        $resolvedStore = DomainStoreResolverAction::execute('storekit.com');

        $this->assertNotEquals($resolvedStore, false);
        $this->assertEquals($resolvedStore->id, $store->id);
        $this->assertEquals(Cache::has('store_id:storekit.com'), true);
        $this->assertEquals(Cache::get('store_id:storekit.com'), $store->id);
    }
}
