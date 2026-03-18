<?php

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\{Product, ProductVariant};
use Domain\FileManager\Models\FileUpload;
use Domain\Shared\ViewModels\ViewModel;

class ProductVariantViewModel extends ViewModel
{
    function __construct(
        protected ProductVariant $productVariant
    ){}

    function productVariant() :array
    {
        // Only sending public id of the image to client.
        $imageId = $this->productVariant->image_id
        ? FileUpload::getPublicId($this->productVariant->image_id)
        : null;

        return [
            ...$this->productVariant->except([
                'id', 
                'product_id',
                'image_id'
            ]),
            'image_id' => $imageId
        ];
    }

}