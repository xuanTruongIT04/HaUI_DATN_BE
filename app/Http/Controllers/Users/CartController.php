<?php

namespace App\Http\Controllers\Users;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;

use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function list()
    {
        try {
            if (Auth::guard('user')->check()) {
                $userId = Auth::guard('user')->id();
                $carts = $this->cartService->getCartByUser($userId);
                return $this->sendSuccessResponse($carts);
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    public function add($productId, $numberOrder)
    {
        try {
            $userId = Auth::guard('user')->id();
            $cart = $this->cartService->addCart($productId, $numberOrder, $userId);
            if ($cart) {
                return $this->sendSuccessResponse($cart);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function updateProductInCart(Request $request)
    {
        try {
            $idUser = Auth::guard('user')->id();
            $dataUpdate = json_decode($request->getContent(), true);
            $status = $this->cartService->updateProductInCart($dataUpdate, $idUser);
            if ($status) {
                return $this->sendSuccessResponse(['update' => true]);
            } else {
                return $this->sendErrorResponse(['update' => false]);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function deleteProductInCart($idDC = "")
    {
        try {
            $status = $this->cartService->deleteProductInCart($idDC);
            if ($status) {
                return $this->sendSuccessResponse(['delete' => true]);
            } else {
                return $this->sendErrorResponse(['delete' => false]);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function deleteAll()
    {
        try {
            $idUser = Auth::guard('user')->id();
            $status = $this->cartService->deleteAll($idUser);
            if ($status) {
                return $this->sendSuccessResponse(['delete' => true]);
            } else {
                return $this->sendErrorResponse(['delete' => false]);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

}