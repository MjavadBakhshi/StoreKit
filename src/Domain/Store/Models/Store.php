<?php

namespace Domain\Store\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

use Domain\Account\Models\User;
use Domain\Catalog\Models\Product;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Concerns\HasPublicId;

class Store extends BaseModel
{
    use HasPublicId;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'public_id',
        'domain_name',
        'max_storage_capacity',
        'free_storage_capacity',
    ];

    protected $casts = [
        'public_id' => 'string',
    ];

    function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function products() :HasMany
    {
        return $this->hasMany(Product::class);
    }
}