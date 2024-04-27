<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponRepository extends BaseRepository
{
    protected $model;

    public function __construct(Coupon $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function getAllLicensed()
    {
        $status = array_keys(Constant::STATUS);
        return $this->model::where("status", $status[0])->orderByDesc("id")->get();
    }

    public function check($codeCoupon)
    {
        $status = array_keys(Constant::STATUS);
        $now = Carbon::now();
        $coupon = $this->model->where("code", $codeCoupon)->where('end_date', '>', $now)->where('status', $status)->first();
        if ($coupon) {
            $percentPromotion = $coupon->percent ?? 0;
            return $percentPromotion;
        }
    }

    public function searchCoupons($keyword, $perPage, $status, $where)
    {
        return $this->model::search($keyword, $perPage, $status, $where)->paginate($perPage);
    }

    public function restore($id)
    {
        return $this->model::onlyTrashed()->where("id", $id)->first()->restore();
    }

    public function countCoupons($condition, $status)
    {
        $cnt = 0;
        if ($condition == "without") {
            if (!empty($status) || $status === 0) {
                $cnt = $this->model::withoutTrashed()->where("status", $status)->count();
            } else {
                $cnt = $this->model::withoutTrashed()->count();
            }
        } else {
            $cnt = $this->model::onlyTrashed()->count();
        }
        return $cnt;

    }
}