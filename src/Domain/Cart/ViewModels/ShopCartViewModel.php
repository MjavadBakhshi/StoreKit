<?php

namespace Domain\Cart\ViewModels;

use Domain\Catalog\Models\ProductVariant;
use Domain\Shared\ViewModels\ViewModel;

class ShopCartViewModel extends ViewModel
{

    public array $cartDetailsData;

    function __construct(
        public array $cart
    ) {

        // Fetch cart items details from DB.
        $variantIdList = array_keys($this->cart);

        $this->cart = 
            // Fetching details of each item in the cart and merging detials with cart quantity.
            ProductVariant::with('shopProduct')
                ->select(
                    'id', 'public_id', 'product_id',
                    'sku', 'price', 'attributes'
                )
                ->whereIn('id', $variantIdList)
                ->get()
                ->map(fn($variant) => [
                    ...$variant->only('public_id', 'sku', 'price', 'attributes'),
                    'quantity' => $this->cart[$variant->id],
                    'product' => $variant->shopProduct->only('title', 'slug'),
                ])
                ->keyBy('public_id')
                ->toArray();
    }

    function cart() :array
    {
        return $this->cart;
    }

    // Total cost which should be paid by customer.
    function total() :float
    {
        return collect($this->cart)
                ->map(fn($cartItem) => $cartItem['quantity'] * $cartItem['price'])
                ->sum();
    }
}