<?php

namespace Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

use Domain\Catalog\Builders\ProductVariantBuilder;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Concerns\HasPublicId;

class ProductVariant extends BaseModel
{
    use HasPublicId, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'attributes' => 'array'
    ];

    function newEloquentBuilder($query) :ProductVariantBuilder
    {
        return new ProductVariantBuilder($query);
    }

    /** Relations */

    public function product() :BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function shopProduct() :BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')
                ->select('id', 'title', 'slug');
    }
}