<?php

namespace Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Eloquent\SoftDeletes;

use Domain\Catalog\Builders\ProductBuilder;
use Domain\Catalog\Enums\{ProductStatus, ProductType, SEORobot};
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
        'product_type',
        'images',
        'meta_keywords',
        'meta_description',
        'tags',
        'page_title',
        'canonical_url',
        'redirect301_url',
        'robot',
    ];

    protected $casts = [
        'status' => ProductStatus::class,
        'product_type' => ProductType::class,
        'robot' => SEORobot::class,
        'images' => 'array',
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

    function defaultVariant() :HasOne
    {
        return $this->hasOne(ProductVariant::class, 'product_id', 'id')
                    ->where('is_default_variant', true);
    }


}