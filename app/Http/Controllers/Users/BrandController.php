<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Services\BrandService;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function list()
    {
        try {
            $brandData = $this->brandService->getAllLicensed();

            return $this->sendSuccessResponse($brandData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }
}