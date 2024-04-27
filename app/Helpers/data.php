<?php

use App\Models\Image;
use App\Models\Order;

function getMainImage($productId)
{
    $image = Image::where("product_id", $productId)->where("level", "0")->first();
    if ($image) {
        return $image->link;
    }
    return "rsrc/dist/img/credit/product-thumb-default.jpg";
}

function getSubTotal($price, $quantity)
{
    return currencyFormat($price * $quantity);
}

function getMainTotal($totalMount, $discountCoupon)
{
    return currencyFormat($totalMount * (100 - $discountCoupon) / 100);
}

function getQtyOrder($orderId, $productId)
{
    $order = Order::find($orderId);
    if ($order) {
        if ($detailCart = $order->cart->detailCarts->where('product_id', $productId)->first()) {
            return $detailCart->quantity;
        }
    } else {
        return 0;
    }
}

function getPricePromotion($price, $percentPromotion)
{
    return currencyFormat(($price * $percentPromotion) / 100);
}