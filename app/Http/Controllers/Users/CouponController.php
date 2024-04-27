<?php

namespace App\Http\Controllers\Users;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Services\CouponService;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function check(Request $request)
    {
        try {
            $codeCoupon = $request['couponCode'];
            $coupon = $this->couponService->check($codeCoupon);
            if ($coupon)
                return $this->sendSuccessResponse($coupon);
            else
                return $this->sendSuccessResponse($coupon);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }

    }

    public function list()
    {
        try {
            $coupon = $this->couponService->getAllLicensed();
            if ($coupon)
                return $this->sendSuccessResponse($coupon);
            else
                return $this->sendSuccessResponse($coupon);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage());
        }

    }

}