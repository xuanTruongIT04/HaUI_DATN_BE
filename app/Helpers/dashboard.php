<?php

use App\Helpers\Constant;
use App\Repositories\CategoryRepository;
use App\Models\Product;
use App\Models\Order;

if (!function_exists('getUrlAvatarAuth')) {
    function getUrlAvatarAuth($urlAvatar)
    {
        if (!empty($urlAvatar)) {
            return url("$urlAvatar");
        }
        return url("rsrc/dist/img/credit/avatar-default.jpeg");
    }
}

if (!function_exists("getTitleById")) {
    function getTitleById($categoryId)
    {
        $categoryRepository = app(CategoryRepository::class);
        $category = $categoryRepository->findOrFail($categoryId);
        if (!empty($category)) {
            return "<a href='" . route(
                'category.edit',
                $category->id
            ) . "' class='text-primary'>" . briefName($category->title, 6) . "</a>";
        }
        return $categoryId;
    }
}

if (!function_exists("getTotalProductSold")) {
    function getTotalProductSold()
    {
        return Product::withoutTrashed()->sum("qty_sold");
    }
}

if (!function_exists("getOrderPaid")) {
    function getOrderPaid()
    {
        $listStatus = array_keys(Constant::STATUS_ORDER);
        return Order::where("status", $listStatus[2])->count();
    }
}

if (!function_exists("getOrderProcessing")) {
    function getOrderProcessing()
    {
        $listStatus = array_keys(Constant::STATUS_ORDER);
        return Order::where("status", $listStatus[1])->count();
    }
}

if (!function_exists("getOrderOrdered")) {
    function getOrderOrdered()
    {
        $listStatus = array_keys(Constant::STATUS_ORDER);
        return Order::where("status", $listStatus[0])->count();
    }
}

if (!function_exists("getTotalOrder")) {
    function getTotalOrder($orderId)
    {
        $listStatus = array_keys(Constant::STATUS_ORDER);
        return Order::find($orderId)->detailOrders->sum("quantity");
    }
}

if (!function_exists("getTotalSales")) {
    function getTotalSales()
    {
        $listStatus = array_keys(Constant::STATUS_ORDER);
        return totalSaleFormat(Order::where("status", $listStatus[2])->sum("total_mount"));
    }
}

if (!function_exists("getNewOrder")) {
    function getNewOrder()
    {
        $listStatus = array_keys(Constant::STATUS_ORDER);
        $listStatusNow = [$listStatus[0], $listStatus[1], $listStatus[2]];
        return Order::with("cart.user")->whereIn("status", $listStatusNow)->latest('created_at')->take(5)->get();
    }
}
