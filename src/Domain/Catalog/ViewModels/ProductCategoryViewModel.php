<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\ProductCategory;
use Domain\Shared\ViewModels\ViewModel;

class ProductCategoryViewModel extends ViewModel
{
    function __construct(
        protected ProductCategory $productCategory
    ){}

    function productCategory() :array
    {        
        $parentId = $this->productCategory->parent_id 
        ? $this->productCategory->parent->public_id 
        : null;
       

        return [
            ...$this->productCategory->except(['id']),
            'parent' => [
                'public_id' => $parentId
            ],
        ];
    }
}