<?php

namespace App\Http\Controllers\Users;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Services\FavoriteService;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function list()
    {
        try {
            $userId = Auth::guard('user')->id();
            $wishlist = $this->favoriteService->getAllFPActive($userId);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }

        return $this->sendSuccessResponse($wishlist);
    }

    public function toggle($itemId)
    {
        try {
            $userId = Auth::guard('user')->id();
            $status = $this->favoriteService->toggleFavorite($itemId, $userId);
            if ($status) {
                return $this->sendSuccessResponse(['add' => true]);
            } else {
                return $this->sendSuccessResponse(['add' => false]);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $idUser = Auth::guard('user')->id();
            $dataCreate = json_decode($request->getContent(), true);
            $status = $this->favoriteService->addToCart($dataCreate, $idUser);
            if ($status) {
                $this->deleteByIdProduct();
                return $this->sendSuccessResponse(['add' => true]);
            } else {
                return $this->sendErrorResponse(['add' => false]);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

    public function deleteByIdProduct($idProduct = "")
    {
        try {
            $idUser = Auth::guard('user')->id();
            $status = $this->favoriteService->deleteByIdProduct($idProduct, $idUser);
            if ($status) {
                return $this->sendSuccessResponse(['delete' => true]);
            } else {
                return $this->sendSuccessResponse(['delete' => false]);
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse(['error' => $e->getMessage()]);
        }
    }

}