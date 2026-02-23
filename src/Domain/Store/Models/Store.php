<?php

namespace Domain\Store\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

use Domain\Shared\Models\BaseModel;

class Store extends BaseModel
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'public_id'
    ];

    protected $casts = [
        'public_id' => 'string',
    ];

    protected static function booted()
    {
        // Automatically generate uuid for public_id field during creation new store.
        static::creating(function ($store) {
            if (!$store->public_id) {
                $store->public_id = (string) Str::orderedUuid(); // generates a UUID
            }
        });
    }
}