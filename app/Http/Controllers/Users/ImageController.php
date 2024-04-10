<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    //
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function getImageProduct(Request $request)
    {
        try {
            $idProduct = $request->idProduct;
            $idColor = $request->idColor;
            $image = $this->imageService->getImageProduct($idProduct, $idColor);
            if ($image) {
                return $this->sendSuccessResponse([
                    "main_image" => $image['main_image'],
                    "sub_images" => $image['sub_images'],
                ]);
            }
            return $this->sendErrorResponse(false);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function getImagePC($idProduct)
    {
        try {
            $colorData = $this->imageService->getImagePC($idProduct);
            return $this->sendSuccessResponse($colorData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

}