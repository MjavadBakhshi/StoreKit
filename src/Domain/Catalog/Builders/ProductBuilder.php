<?php

namespace Domain\Catalog\Builders;

use Illuminate\Database\Eloquent\Builder;

use Domain\Catalog\Enums\ProductStatus;

class ProductBuilder extends Builder
{
    function active()
    {
        return $this->where('status', ProductStatus::Active);
    }
}
