<?php

namespace Domain\Catalog\Models;

use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Concerns\HasPublicId;

class ProductVariant extends BaseModel
{
    use HasPublicId;

    protected $guarded = ['id'];

    protected $casts = [
        'attributes' => 'array'
    ];

    
    
}