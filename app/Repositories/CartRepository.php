<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Constant;
use Exception;
use App\Models\DetailCart;
use App\Models\Cart;
use App\Models\Bill;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class CartRepository extends BaseRepository
{
    protected $model, $modelDetailCart, $modelUser, $modelOrder, $modelBill, $modelProduct;

    public function __construct(Cart $model, DetailCart $modelDetailCart, User $modelUser, Order $modelOrder, Bill $modelBill, Product $modelProduct)
    {
        $this->model = $model;
        $this->modelDetailCart = $modelDetailCart;
        $this->modelUser = $modelUser;
        $this->modelOrder = $modelOrder;
        $this->modelBill = $modelBill;
        $this->modelProduct = $modelProduct;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function updateCart($id, $dataUpdate)
    {
        $cart = $this->model::findOrFail($id);
        if ($cart) {
            $cart->update($dataUpdate);
            $listStatusCart = array_keys(Constant::STATUS_CART);

            if ($dataUpdate['status'] == $listStatusCart[2] || $dataUpdate['status'] == $listStatusCart[3]) {
                $dataUpdate['total_item'] = 0;
                $dataUpdate['total_price'] = 0.00;
                $cart->update($dataUpdate);
                $cart->detailCarts()->delete();
            }
            return $cart;
        }
        return false;
    }

    public function searchCarts($keyword, $perPage, $where)
    {
        return $this->model::search($keyword, $perPage, $where)->paginate($perPage);
    }

    public function getDetailCart($id)
    {
        return $this->model::where("id", $id)->first()->detailCarts;
    }

    public function getCartByUser($user_id)
    {
        $cart = $this->model::where("user_id", $user_id)->first();
        if ($cart) {
            $cartData = [
                "cart" => $cart,
                "detailCarts" => $cart->detailCarts->map(function ($detailCart) {
                    $product = $detailCart->product;
                    $product->load([
                        'images' => function ($query) {
                            $query->where('level', 0);
                        }
                    ]);
                    $detailCartData = $detailCart->toArray();
                    $detailCartData['product'] = $product;
                    return $detailCartData;
                }),
            ];
            return $cartData;
        }
        return false;
    }

    public function countCarts($status)
    {
        $cnt = 0;
        if (!empty($status) || $status === 0) {
            $cnt = $this->model::where("status", $status)->count();
        } else {
            $cnt = $this->model::count();
        }
        return $cnt;
    }

    public function addCart($productId, $numberOrder, $userId)
    {
        try {
            DB::beginTransaction();

            $statusCart = array_keys(Constant::STATUS_CART);

            $cart = $this->model::updateOrCreate(
                ['user_id' => $userId],
                [
                    'status' => $statusCart[0],
                ]
            );

            $cartId = $cart->id;
            $product = $this->modelProduct::find($productId);

            if (!$product) {
                throw new Exception("Product not found.");
            }else {
                $qtyAvailable = $product->qty_import - $product->qty_sold;

                if ($qtyAvailable < $numberOrder) {
                    throw new Exception("Insufficient quantity in stock.");
                }
            }
            // FIND OUT OR UPDATE
            $detailCart = $this->modelDetailCart::updateOrCreate(
                ['cart_id' => $cartId, 'product_id' => $productId],
                ['quantity' => DB::raw("quantity + $numberOrder")]
            );

            // UPDATE TOTAL IN CART
            $totalQuantity = $this->modelDetailCart::where("cart_id", $cartId)->sum("quantity");
            $totalPrice = $this->modelDetailCart::where("cart_id", $cartId)->sum(DB::raw("quantity * price_sale"));

            $cart->update([
                'total_item' => $totalQuantity,
                'total_price' => $totalPrice
            ]);

            DB::commit();

            return [
                'cart' => $cart,
                'detail_cart' => $detailCart,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to add cart. " . $e->getMessage());
        }
    }

    public function updateProductInCart($data, $idUser)
    {
        try {
            DB::beginTransaction();
            $cart = $this->model::where("user_id", $idUser)->first();
            if ($cart) {
                $cartId = $cart->id;
                // UPDATE CART
                $totalItem = 0;
                $totalPrice = 0.00;
                foreach ($data as $key => $item) {
                    $detailCart = $this->modelDetailCart->where("cart_id", $cartId)->find($key);
                    if ($detailCart) {
                        $dataUpdateDC = [
                            'quantity' => $item,
                        ];
                        if ($item == 0) {
                            $this->deleteProductInCart($key);
                        } else {
                            $detailCart->update($dataUpdateDC);
                            $totalItem += $item;
                            $totalPrice += $detailCart->price_sale * $detailCart->quantity;
                        }
                    }
                }
            }
            // UPDATE TOTAL FOR CART
            $dataUpdateCart = [
                "total_item" => $totalItem,
                "total_price" => $totalPrice
            ];
            $status = $cart->update($dataUpdateCart);

            DB::commit();
            return $status;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to update cart. " . $e->getMessage());
        }
    }

    public function deleteProductInCart($idDC)
    {
        try {
            DB::beginTransaction();
            $status = false;
            $detailCart = $this->modelDetailCart::find($idDC);
            if ($detailCart) {
                // UPDATE CART
                $this->updateDetailCart($detailCart);
                $cartId = $detailCart->cart->id;
                $status = $detailCart->delete();
                $cntDCSameCartId = $this->modelDetailCart::where("cart_id", $cartId)->count();
                if ($cntDCSameCartId == 0) {
                    $order = $detailCart->cart->orders()->where("status", 0)->orderByDesc('id')->first();
                    if ($order) {
                        $order->delete();
                    }
                }
            }

            DB::commit();
            return $status;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to delete product in cart. " . $e->getMessage());
        }
    }

    public function deleteAll($idUser)
    {
        try {
            DB::beginTransaction();
            $statusCart = array_keys(Constant::STATUS_CART);
            $statusOrder = array_keys(Constant::STATUS_ORDER);

            $cart = $this->model::where("user_id", $idUser)->first();
            if ($cart) {
                $cartId = $cart->id;
                $statusDC = $this->modelDetailCart->where("cart_id", $cartId)->delete();
                if ($statusDC) {
                    // UPDATE CART
                    $dataUpdateCart = [
                        'total_item' => 0,
                        'total_price' => 0,
                        "status" => $statusCart[3],
                    ];
                    $cart = $this->model::where("user_id", $idUser)->update($dataUpdateCart);
                    $order = $this->modelOrder::where("cart_id", $cartId)->where("status", $statusOrder[0])->orderByDesc("id")->first();
                    if ($order) {
                        $order->delete();
                    }
                    $statusDC = true;
                } else {
                    $statusDC = false;
                }
                DB::commit();
                return $statusDC;
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to delete all product in cart. " . $e->getMessage());
        }
    }

    public function updateDetailCart($detailCart)
    {
        $cart = $detailCart->cart;
        $subTotalItem = $detailCart->quantity;
        $subTotalPrice = ($subTotalItem > 0) ? $detailCart->quantity * $detailCart->price_sale : 0;
        // Update total in cart

        $dataUpdate = [
            "total_item" => $cart->total_item - $subTotalItem,
            "total_price" => $cart->total_price - $subTotalPrice
        ];
        $cart->update($dataUpdate);
    }
}