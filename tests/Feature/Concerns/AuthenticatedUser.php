<?php

namespace Tests\Feature\Concerns;

use Domain\Account\Models\User;

trait AuthenticatedUser
{
    protected User $user;

    protected function actingAsUser(?User $user = null)
    {
        $this->user = $user ?? User::factory()->create();
        $this->actingAs($this->user);
        return $this->user;
    }
}