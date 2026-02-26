<?php

namespace Domain\Catalog\Models;

use Domain\Catalog\Enums\ProductStatus;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Concerns\HasPublicId;

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
}