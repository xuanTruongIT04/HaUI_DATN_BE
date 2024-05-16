<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Helpers\Constant;

use App\Models\DetailOrder;
use App\Models\DetailCart;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Bill;
use App\Models\Cart;
use App\Models\User;

use Exception;

class OrderRepository extends BaseRepository
{
    protected $model, $modelCart, $modelCoupon, $modelBill, $modelDetailCart, $modeProduct, $modelDetailOrder, $modelUser;

    public function __construct(
        Order $model, Cart $modelCart, Coupon $modelCoupon, Bill $modelBill,
        DetailCart $modelDetailCart, Product $modeProduct,
        DetailOrder $modelDetailOrder, User $modelUser
    ) {
        $this->modelCart = $modelCart;
        $this->modelBill = $modelBill;
        $this->model = $model;
        $this->modelCoupon = $modelCoupon;
        $this->modeProduct = $modeProduct;
        $this->modelDetailCart = $modelDetailCart;
        $this->modelDetailOrder = $modelDetailOrder;
        $this->modelUser = $modelUser;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function findWithOrderID($ordertId)
    {
        $statusOrder = array_keys(Constant::STATUS_ORDER);
        return $this->model::find($ordertId)->where("status", $statusOrder[0])->first();
    }

    public function findByCartID($cartId)
    {
        $statusOrder = array_keys(Constant::STATUS_ORDER);
        $order = $this->model::where("cart_id", $cartId)->where("status", $statusOrder[0])->latest()->first();
        if ($order) {
            return $order->id;
        }
        return null;
    }

    public function updateOrCreate($dataUpdateOrCreate)
    {
        $statusOrder = array_keys(Constant::STATUS_ORDER);

        return $this->model::updateOrCreate(
            [
                "cart_id" => $dataUpdateOrCreate['cart_id'],
                "status" => $statusOrder[0]
            ],
            ["status" => $dataUpdateOrCreate['status']]
        );
    }

    public function getInfoOrder($cartId)
    {
        $statusOrder = array_keys(Constant::STATUS_ORDER);
        return $this->model::where("cart_id", $cartId)->where("status", $statusOrder[0])->latest()->first();
    }

    public function getDetailOrder($id)
    {
        return $this->modelDetailOrder::where("order_id", $id)->get();
    }

    public function submitOrder($idUser, $data)
    {
        try {
            DB::beginTransaction();
            $cart = $this->modelCart::where("user_id", $idUser)->first();
            $statusOrder = false;

            if ($cart) {
                $idCoupon = null;
                if ($data['coupon']) {
                    $coupon = $this->modelCoupon->where("code", $data['coupon'])->first();
                    $idCoupon = $coupon ? $coupon->id : null;
                }

                $statusOrder = array_keys(Constant::STATUS_ORDER);
                $statusOrderNow = $statusOrder[1];

                // CHECK PAYMENT METHOD
                if($data['payment_method'] == array_keys(Constant::PAYMENT_METHOD)[1]) {
                    $statusOrderNow = $statusOrder[2];
                    info($statusOrderNow);
                }

                $order = $cart->orders()->orderByDesc("id")->first();
                if ($order) {
                    // UPDATE ORDER
                    $dataUpdateOrder = [
                        'address_delivery' => $data['address_delivery'],
                        'payment_method' => $data['payment_method'],
                        'total_mount' => $data['total_mount'],
                        'order_date' => $data['order_date'],
                        'coupon_id' => $idCoupon,
                        'status' => $statusOrderNow
                    ];
                    $cntBill = $this->modelBill::where("order_id", $order->id)->where("user_id", $idUser)->count();
                    if ($cntBill > 0) {
                        $idBill = false;
                    } else {
                        $statusOrder = $order->update($dataUpdateOrder);
                        if ($statusOrder) {
                            $statusBill = number_format(array_keys(Constant::STATUS_BILL)[0]);

                            if($statusOrderNow == number_format(array_keys(Constant::STATUS_ORDER)[2])) {
                                $statusBill = array_keys(Constant::STATUS_BILL)[1];
                            }

                            $dataCreateBill = [
                                'order_id' => $order->id,
                                'user_id' => $idUser,
                                'status' => $statusBill
                            ];

                            // Check if the order_id exists in the orders table before creating a new bill
                            $existingOrder = $this->model::find($order->id);
                            if (!$existingOrder) {
                                throw new Exception("Order does not exist with ID: " . $order->id);
                            }

                            // Create a new bill
                            $idBill = $this->modelBill::create($dataCreateBill)->id;

                            // Update status card
                            if ($cart) {
                                $statusOrder = array_keys(Constant::STATUS_ORDER);
                                $dataUpdateOrder = [
                                    "status" => $statusOrder[1],
                                ];
                                $cart->orders()->orderBy("id")->first()->update($dataUpdateOrder);

                                $statusCart = array_keys(Constant::STATUS_CART);
                                $dataUpdateCart = [
                                    "status" => $statusCart[1],
                                    "total_item" => 0,
                                    "total_price" => 0.00,
                                ];
                                $cart->update($dataUpdateCart);

                                // Update quantity product
                                foreach ($cart->detailCarts as $item) {
                                    $item->product->qty_sold += $item->quantity;
                                    $item->product->save();
                                }

                                //Update user
                                $user = $this->modelUser::find($idUser);
                                $dataUpdateUser = [
                                    "total_order" => $user->total_order + 1,
                                ];
                                $this->modelUser::where("id", $idUser)->update($dataUpdateUser);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return $idBill;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to update cart. " . $e->getMessage());
        }
    }

    public function checkStatusOC($cartId)
    {
        $statusCart = array_keys(Constant::STATUS_CART);
        $statusOrder = array_keys(Constant::STATUS_ORDER);
        $order = Order::with('cart')
            ->where('cart_id', $cartId)
            ->where('status', $statusOrder[0])
            ->whereHas('cart', function ($query) use ($statusCart) {
                $query->where('status', $statusCart[0]);
            })
            ->orderByDesc('id')
            ->first();

        return $order ? $order->id : null;
    }

    public function getCoupon($id)
    {
        return $this->model::find($id)->coupon;
    }

    public function swapInfoStore($user, $orderId)
    {
        $cartWithDetailCarts = $user->cart()
            ->with('detailCarts')
            ->orderByDesc('id')
            ->first();

        $detailCarts = $cartWithDetailCarts->detailCarts;

        foreach ($detailCarts as $detailCart) {
            // Add records to DetailOrders table
            $detailOrder = $detailCart->toArray();
            $detailOrder['order_id'] = $orderId;

            $this->modelDetailOrder::create($detailOrder);

            // Remove records on Detailcarts table
            $detailCart->delete();
        }
    }

    public function searchOrders($keyword, $perPage, $where)
    {
        return $this->model::search($keyword, $perPage, $where)->paginate($perPage);
    }

    public function restore($id)
    {
        return $this->model::find($id)->first()->restore();
    }

    public function countOrders($status)
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
