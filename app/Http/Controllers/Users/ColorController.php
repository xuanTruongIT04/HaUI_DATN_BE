<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Services\ColorService;

class ColorController extends Controller
{
    protected $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    public function list()
    {
        try {
            $colorData = $this->colorService->getAllLicensed();

            return $this->sendSuccessResponse($colorData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }
}
