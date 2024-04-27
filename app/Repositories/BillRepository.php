<?php

namespace App\Repositories;

use App\Models\Bill;
use App\Models\Cart;

class BillRepository extends BaseRepository
{
    protected $model, $modelCart;

    public function __construct(Bill $model, Cart $modelCart)
    {
        $this->model = $model;
        $this->modelCart = $modelCart;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function getInfoFromBill($idBill)
    {
        $bill = $this->model::find($idBill);
        if (!$bill) {
            return null;
        }
        $billInfo = $this->model::with([
            'order.coupon',
            'user',
            'order.cart',
            'order.cart.detailCarts.product.images'
        ])->where("id", $idBill)->first();

        return $billInfo;
    }

    public function getDetailOrder($idBill)
    {
        $bill = $this->model::find($idBill);

        if ($bill && $bill->order && $bill->order->detailOrders) {
            return $bill->order->detailOrders;
        }

        return null;
    }

    public function getCoupon($idBill)
    {
        $bill = $this->model::find($idBill);

        if ($bill && $bill->order && $bill->order->cart) {
            return $bill->order->coupon;
        }

        return null;
    }

    public function getUser($idBill)
    {
        $bill = $this->model::find($idBill);
        if (!$bill) {
            return null;
        }
        $user = $this->model::find($idBill)->user;

        return $user;
    }

    public function searchBills($keyword, $perPage, $where)
    {
        return $this->model::search($keyword, $perPage, $where)->paginate($perPage);
    }

    public function countBills($status)
    {
        $cnt = 0;
        if (!empty($status) || $status === 0) {
            $cnt = $this->model::where("status", $status)->count();
        } else {
            $cnt = $this->model::count();
        }
        return $cnt;
    }
}