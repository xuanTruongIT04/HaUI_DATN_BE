<?php

namespace App\Services;

use App\Repositories\FavoriteRepository;

class FavoriteService
{
    protected $favoriteRepository;

    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function getAllFPActive($userId)
    {
        return $this->favoriteRepository->getAllFPActive($userId);
    }

    public function toggleFavorite($itemId, $userId)
    {
        return $this->favoriteRepository->toggleStatus($itemId, $userId);
    }
    public function addToCart($dataCreate, $idUser)
    {
        return $this->favoriteRepository->addToCart($dataCreate, $idUser);
    }

    public function deleteByIdProduct($idProduct = "", $idUser)
    {
        $statusDelete = false;
        if ($this->favoriteRepository->deleteByIdProduct($idProduct, $idUser)) {
            $statusDelete = true;
        }
        return $statusDelete;
    }

}