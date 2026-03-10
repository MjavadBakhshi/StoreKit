<?php

namespace Domain\Cart\Actions\Shop;

use Domain\Cart\DataTransferObjects\Shop\ShopCartItemFormData;
use Domain\Cart\ViewModels\ShopCartViewModel;
use Domain\Shared\Actions\SessionAction;
use Domain\Shared\Exceptions\ActionException;

class UpdateShopCartAction
{
    static function execute(
        ShopCartItemFormData $data
    ) :ActionException|ShopCartViewModel
    {

        $cart = self::getCart();

        // Increasing quantity
        if($data->quantity > 0)
        {
            $reservedQuantity = $cart[$data->product_variant->id] ?? 0;
            $reservedQuantity += $data->quantity;

            // Checking inventory availability
            throw_if(
                $data->product_variant->stock < $reservedQuantity,
                new ActionException(
                    "{$data->product_variant->public_id}",
                    data: [$data->product_variant]
                )
            );
        }

        $updatedCart = self::updateQuantity($data);

        return new ShopCartViewModel($updatedCart);
    }

    private static function getCart(): array
    {
        $sessionManager = app(SessionAction::class);
        return $sessionManager->get('cart', []);
    }

    private static function updateQuantity(
        ShopCartItemFormData &$data
    ) :array
    {
        $cart = self::getCart();
        $cartItemKey = $data->product_variant->id;

        $currentQuantity = $cart[$cartItemKey] ?? 0;

        $cart[$cartItemKey] = 
        max(0, $currentQuantity + $data->quantity);

        // Removing cart item from the cart.
        if($cart[$cartItemKey] == 0) unset($cart[$cartItemKey]);

        // Store updated cart in the session.
        $sessionManager = app(SessionAction::class);
        $sessionManager->set('cart', $cart);

        return $sessionManager->get('cart');
    }
}