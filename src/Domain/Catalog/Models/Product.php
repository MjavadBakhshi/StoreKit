<?php

namespace Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Domain\Catalog\Builders\ProductBuilder;
use Domain\Catalog\Enums\ProductStatus;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Concerns\HasPublicId;
use Domain\Store\Models\Store;

class Product extends BaseModel
{    
    use HasPublicId;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => ProductStatus::class,
        'attributes' => 'array',
    ];

    function newEloquentBuilder($query) :ProductBuilder
    {
        return new ProductBuilder($query);
    }

    /** Relations */

    function store() :BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

}