<?php

namespace Tests\Feature\Shared;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use Domain\Shared\Actions\SessionAction;

class SessionActionTest extends TestCase
{
    protected SessionAction $sessionManager;

    function setUp(): void
    {
        parent::setUp();

        $this->sessionManager = app(SessionAction::class);
        $this->sessionManager->start();
    }


    #[Test]
    function session_is_stored_successfully()
    {   
        $sessionManager = app(SessionAction::class);

        $cartData = [
            ['iphone18' => 1],
            ['macbook pro m4' => 2]
        ];

        $sessionManager->set('cart', $cartData);

        $this->assertEquals($cartData, $sessionManager->get('cart'));
    }

    #[Test]
    function is_an_entry_removed_successfully()
    {
        $this->sessionManager->set('item1', 1);
        $this->sessionManager->set('item2', 2);

        $this->assertEquals($this->sessionManager->get('item1'), 1);
       
        $this->sessionManager->forget('item1');
        $this->assertEquals($this->sessionManager->get('item1'), null);
      
        $this->assertEquals($this->sessionManager->get('item2'), 2);
    }
}
