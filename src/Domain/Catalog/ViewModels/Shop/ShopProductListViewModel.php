<?php

namespace Domain\Catalog\ViewModels\Shop;

use Domain\Catalog\Actions\Shop\SearchShopProductsAction;
use Domain\Store\Models\Store;
use Domain\Shared\ViewModels\ViewModel;

class ShopProductListViewModel extends ViewModel
{
    function __construct(
        public readonly Store $store,
        public readonly array $filters,
    ){}

    function products() :array
    {
        #TODO pagination will be add later.
        $products = SearchShopProductsAction::execute($this->store, $this->filters);

        // Apply manipulations such as formating, filtering data on raw results
        return $products->map(
                    fn($product) => [
                        'public_id' => $product->public_id,
                        'title' => $product->title,
                        // image, price, discount, category, and etc. will be added later.
                    ]
                )->toArray();
    }
}