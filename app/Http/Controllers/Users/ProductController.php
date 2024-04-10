<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Users\Product\FilterRequest;
use App\Http\Controllers\Controller;
use App\Services\ProductService;

class ProductController extends Controller
{
    //
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function filter(FilterRequest $request)
    {
        $filters = $request->validated();
        $products = $this->productService->filterProducts($filters);
        return response()->json($products);
    }

    public function list()
    {
        try {
            $productData = $this->productService->getAllProductsWithMainImages();
            return $this->sendSuccessResponse($productData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function detail($slug)
    {
        try {
            $productData = $this->productService->getDetailProduct($slug);
            return $this->sendSuccessResponse($productData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function getProductRelated($idProduct)
    {
        try {
            $productData = $this->productService->getProductRelated($idProduct);
            return $this->sendSuccessResponse($productData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function getAllLatest()
    {
        try {
            $productData = $this->productService->getAllLatest();
            return $this->sendSuccessResponse($productData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

    public function getMMPrice()
    {
        try {
            $priceMM = $this->productService->getMMPrice();
            return $this->sendSuccessResponse($priceMM);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }

}