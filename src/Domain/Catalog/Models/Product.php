<?php

namespace Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\SoftDeletes;

use Domain\Catalog\Builders\ProductBuilder;
use Domain\Catalog\Enums\{ProductStatus, ProductType};
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Concerns\HasPublicId;
use Domain\Store\Models\Store;

class Product extends BaseModel
{    
    use HasPublicId, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'product_type'
    ];

    protected $casts = [
        'status' => ProductStatus::class,
        'product_type' => ProductType::class,
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

    function variants() :HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }


}