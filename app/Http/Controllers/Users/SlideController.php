<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Services\SlideService;

class SlideController extends Controller
{
    protected $slideService;

    public function __construct(SlideService $slideService)
    {
        $this->slideService = $slideService;
    }

    public function list()
    {
        try {
            $sliderData = $this->slideService->getAllLicensed();

            return $this->sendSuccessResponse($sliderData);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e);
        }
    }
}