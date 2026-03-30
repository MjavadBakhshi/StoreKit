<?php

namespace Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Concerns\HasPublicId;
use Domain\Store\Models\Store;

class ProductCategory extends BaseModel
{
    use HasPublicId;

        protected $fillable = [
        'title',
        'slug',
        'status',
        'menu_visibility',
        'meta_keywords',
        'meta_description',
        'page_title',
        'canonical_url',
        'redirect301_url',
        'robot',
    ];

    /** Relations */

    function store() :BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    function parent() :BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id', 'id');
    }
    
}