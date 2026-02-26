<?php

namespace Domain\Shared\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Some resources like stores, products and orders will expose id 
 * so it provides a safe way to access specific resource through endpoints.
 */
trait HasPublicId 
{
    protected static function booted()
    {
        // Automatically generate uuid for public_id field during creation new store.
        static::creating(function ($store) {
            if (!$store->public_id) {
                $store->public_id = (string) Str::orderedUuid(); // generates a UUID
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }
}