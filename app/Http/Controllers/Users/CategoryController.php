<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Services\BrandService;
use App\Services\ColorService;
use App\Services\TagService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Helpers\Cacher;

class CategoryController extends Controller
{
    protected $categoryService, $brandService, $colorService, $tagService, $productService;
    private $cacher;

    public function __construct(
        CategoryService $categoryService, BrandService $brandService,
        ColorService $colorService, TagService $tagService,
        ProductService $productService
    ) {
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
        $this->colorService = $colorService;
        $this->tagService = $tagService;
        $this->productService = $productService;
        $this->cacher = new Cacher("file");

    }
    public function list()
    {
        try {
            $categories = $this->categoryService->getAllLicensed();
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }

        return $this->sendSuccessResponse($categories);
    }

    public function getTreeList()
    {
        return $this->categoryService->getTreeList();
    }

    public function getSidebarFilter()
    {

        try {
            $cachedData = [
                "tree_list" => $this->cacher->getCached("tree_list"),
                "brand_list" => $this->cacher->getCached("brand_list"),
                "color_list" => $this->cacher->getCached("color_list"),
                "tag_list" => $this->cacher->getCached("tag_list"),
                "price_min_max" => $this->cacher->getCached("price_min_max"),
            ];

            if (
                $cachedData["tree_list"] && $cachedData["brand_list"]
                && $cachedData["color_list"] && $cachedData["tag_list"] && $cachedData["price_min_max"]
            ) {
                $dataSidebar = $cachedData;
            } else {
                $catTreeList = $this->getTreeList();
                $brandList = $this->brandService->getAllLicensed();
                $colorList = $this->colorService->getAllLicensed();
                $tagList = $this->tagService->listPopular();
                $priceMM = $this->productService->getMMPrice();

                $dataSidebar = [
                    "tree_list" => $catTreeList,
                    "brand_list" => $brandList,
                    "color_list" => $colorList,
                    "tag_list" => $tagList,
                    "price_min_max" => $priceMM,
                ];
                // Store in cacher
                foreach ($dataSidebar as $key => $item) {
                    $this->cacher->setCached($key, $item);
                }
            }

            return $this->sendSuccessResponse($dataSidebar);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }
    }


}
