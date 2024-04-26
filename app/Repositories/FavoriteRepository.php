<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Favorite;
use App\Models\Cart;
use App\Models\DetailCart;
use Illuminate\Support\Facades\DB;
use Exception;

class FavoriteRepository extends BaseRepository
{
    protected $modelFavorite, $modelCart, $modelDetailCart;

    public function __construct(Favorite $modelFavorite, Cart $modelCart, DetailCart $modelDetailCart)
    {
        $this->modelFavorite = $modelFavorite;
        $this->modelCart = $modelCart;
        $this->modelDetailCart = $modelDetailCart;
    }

    public function getAllFPActive($userId)
    {
        $listStatus = Constant::STATUS_FAVORITE_PRODUCT;
        $favorites = $this->modelFavorite::with("product.images")->where("user_id", $userId)->where("status", $listStatus[0])->get();

        $products = $favorites->map(function ($item) {
            return $item->product;
        });

        return $products;
    }

    public function findByItemId($itemId, $userId)
    {
        return $this->modelFavorite::where('product_id', $itemId)
            ->where('user_id', $userId)
            ->first();
    }

    public function createFP($itemId, $userId)
    {
        $listStatus = Constant::STATUS_FAVORITE_PRODUCT;
        return $this->modelFavorite::create([
            'product_id' => $itemId,
            'user_id' => $userId,
            'status' => $listStatus[0],
        ]);
    }

    public function toggleStatus($itemId, $userId)
    {
        $listStatus = Constant::STATUS_FAVORITE_PRODUCT;
        $status = false;

        $favorite = $this->modelFavorite::where("product_id", $itemId)->where("user_id", $userId)->first();
        $dataUpdate = [];
        if (!$favorite) {
            $this->modelFavorite::create([
                'product_id' => $itemId,
                'user_id' => $userId,
                'status' => $listStatus[0],
            ]);
            $status = true;
        } else if ($favorite && $favorite->status == $listStatus[0]) {
            $dataUpdate = [
                'status' => $listStatus[1]
            ];
            $favorite->update($dataUpdate);
            $status = false;
        } else {
            $dataUpdate = [
                'status' => $listStatus[0]
            ];
            $favorite->update($dataUpdate);
            $status = true;
        }

        return $status;
    }


    public function addToCart($dataCreate, $userId)
    {
        try {
            DB::beginTransaction();

            $statusCart = array_keys(Constant::STATUS_CART);

            $cart = $this->modelCart::updateOrCreate(
                ['user_id' => $userId],
                [
                    'status' => $statusCart[0],
                ]
            );
            $cartId = $cart->id;

            // FIND OUT OR UPDATE MULTI
            foreach ($dataCreate as $productId => $numberOrder) {
                $detailCart = $this->modelDetailCart::updateOrCreate(
                    ['cart_id' => $cartId, 'product_id' => $productId],
                    [
                        'quantity' => DB::raw("quantity + $numberOrder"),
                    ]
                );
            }


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

    public function deleteByIdProduct($idProduct = "", $idUser)
    {
        if ($idProduct) {
            $result = $this->modelFavorite::where("product_id", $idProduct)->where("user_id", $idUser)->delete();
        } else {
            $result = $this->modelFavorite::where("user_id", $idUser)->delete();
        }
        if ($result) {
            return true;
        }
        return false;
    }
}