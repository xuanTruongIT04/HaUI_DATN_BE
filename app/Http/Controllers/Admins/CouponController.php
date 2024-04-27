<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\Coupon\EditCouponRequest;
use App\Http\Requests\Admins\Coupon\StoreCouponRequest;
use App\Models\Coupon;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    //
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    function list(Request $request)
    {
        $coupons = $this->couponService->all();
        $countCoupons = $coupons->count();

        // Search information
        $keyWord = "";

        if ($request->input("keyWord")) {
            $keyWord = $request->input("keyWord");
        }

        // Get constraint action
        $constraintAction = $this->couponService->constraintAction($request);

        $where = $constraintAction['where'];
        $status = $constraintAction['status'];
        $statusData = $constraintAction['statusData'];
        $listAct = $constraintAction['listAct'];

        // Handle action with constaint
        $coupons = $this->couponService->searchCoupons($keyWord, 20, $statusData, $where);
        $coupons->withQueryString();

        $listCondition = array_keys(Constant::STATUS);
        // Get number record by status
        $countCouponsSearch = $coupons->total();
        $cntCouponActive = $this->couponService->countCoupons();
        $cntCouponLicensed = $this->couponService->countCoupons("without", $listCondition[0]);
        $cntCouponPending = $this->couponService->countCoupons("without", $listCondition[1]);
        $cntCouponTrashed = $this->couponService->countCoupons("only");
        // Merge to array count status
        $countCouponStatus = [$cntCouponActive, $cntCouponLicensed, $cntCouponPending, $cntCouponTrashed];

        return view("coupon.list", compact('coupons', "countCouponStatus", "listAct", "countCoupons", "countCouponsSearch"));
    }

    public function add()
    {
        return view('coupon.add');
    }

    public function store(StoreCouponRequest $request)
    {
        $dataCreate = $request->validated();

        $name = $request->input("name");

        $this->couponService->create($dataCreate);
        return redirect("coupon/list")->with('statusSuccess', "Bạn đã thêm phiếu giảm giá tên '$name' thành công!");

    }

    public function edit($id)
    {
        $coupon = $this->couponService->find($id);

        return view('coupon.edit', compact("coupon"));
    }

    public function update(EditCouponRequest $request, $id)
    {
        $dataUpdate = $request->validated();
        $status = $dataUpdate['status'];
        // Update super coupon
        $this->couponService->update($id, $dataUpdate);
        $listCondition = array_keys(Constant::STATUS);
        // Update coupon other
        $couponName = $this->couponService->find($id)->name;
        if ($status == $listCondition[2]) {
            // dd($status);
            $this->couponService->delete($id);
        }
        return redirect("coupon/list")->with('statusSuccess', "Bạn đã cập nhật thông tin phiếu giảm giá tên '$couponName' thành công!");
    }

    public function delete($id)
    {
        $coupon = Coupon::withTrashed()->where("id", $id)->first();
        $coupon_id = $coupon->id;
        $name = $coupon->name;

        if (empty($coupon->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[2];

            $this->couponService->update($id, $dataUpdate);
            $this->couponService->delete($id);

            return redirect("coupon/list")->with("status", "Bạn đã xoá tạm thời phiếu giảm giá tên {$name} thành công!");
        } else {
            $coupon->forceDelete();
            return redirect("coupon/list")->with("status", "Bạn đã xoá vĩnh viễn phiếu giảm giá tên {$name} thành công!");
        }
    }

    public function restore($id)
    {
        $coupon = Coupon::onlyTrashed()->where("id", $id)->first();
        $coupon->restore();
        $name = $coupon->name;

        if (empty($coupon->deleted_at)) {
            $listCondition = array_keys(Constant::STATUS);
            $dataUpdate['status'] = $listCondition[1];
            $this->couponService->update($id, $dataUpdate);
        }
        return redirect("coupon/list")->with("status", "Bạn đã khôi phục phiếu giảm giá tên '$name' thành công");
    }

    public function action(Request $requests)
    {
        return $this->couponService->action($requests);
    }
}