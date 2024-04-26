<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\CouponRepository;
use App\Models\Coupon;
use App\Helpers\Constant;

class CouponService
{
    protected $couponRepository;

    public function __construct(CouponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }

    public function all()
    {
        return $this->couponRepository->getAll();
    }

    public function getAllLicensed()
    {
        return $this->couponRepository->getAllLicensed();
    }

    public function create(array $data)
    {
        return $this->couponRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->couponRepository->update($id, $data);
    }

    public function check($codeCoupon)
    {
        return $this->couponRepository->check($codeCoupon);
    }

    public function delete($id)
    {
        return $this->couponRepository->delete($id);
    }

    public function find($id)
    {
        return $this->couponRepository->findOrFail($id);
    }

    public function searchCoupons($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->couponRepository->searchCoupons($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->couponRepository->restore($id);
    }

    public function countCoupons($condition = "without", $status = "")
    {
        return $this->couponRepository->countCoupons($condition, $status);
    }

    public function constraintAction(Request $request)
    {
        // Update records
        // List action
        $listAct = Constant::ACTION;
        $listStatus = array_keys(Constant::STATUS);
        // Default status = active
        $status = !empty(request()->input('status')) ? $request->input('status') : 'active';
        // Khai báo biến điều kiện => lọc theo trạng thái
        $where = array();
        if ($status == "active") {

            // All record without trashed
            unset($listAct['RESTORE'], $listAct['DELETE_PERMANENTLY']);
            $statusData = "without";
        } else if ($status == "licensed") {

            // All record without trashed and status = licened
            unset($listAct['LICENSED'], $listAct['RESTORE'], $listAct['DELETE_PERMANENTLY']);
            $where['status'] = $listStatus[0];
            $statusData = "without";
        } else if ($status == "pending") {

            // All record without trashed and status = pending
            unset($listAct['PENDING'], $listAct['RESTORE'], $listAct['DELETE_PERMANENTLY']);
            $where['status'] = $listStatus[1];
            $statusData = "without";
        } else if ($status == "trashed") {

            // All record in trashed
            unset($listAct['LICENSED'], $listAct['PENDING'], $listAct['DELETE']);
            $statusData = "only";
        }
        $data = [
            "where" => $where,
            "status" => $status,
            "statusData" => $statusData,
            "listAct" => $listAct
        ];
        return $data;
    }

    public function action(Request $requests)
    {

        $listChecked = $requests->input("listCheck");
        $act = $requests->input('act');
        $listStatus = array_keys(Constant::STATUS);
        if ($act != "") {
            if ($listChecked) {
                $cntMember = count($listChecked);
                if ($cntMember > 0) {
                    if ($act == "DELETE") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[2];
                            $this->couponRepository->update($id, $dataUpdate);
                        }
                        Coupon::destroy($listChecked);
                        return redirect("coupon/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} phiếu giảm giá thành công!");
                    } else if ($act == "LICENSED") {
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[0];
                            $this->couponRepository->update($id, $dataUpdate);
                        }
                        return redirect("coupon/list")->with("status", "Bạn đã cấp quyền {$cntMember} phiếu giảm giá thành công!");
                    } else if ($act == "PENDING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->couponRepository->update($id, $dataUpdate);
                        }
                        return redirect("coupon/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} phiếu giảm giá thành công!");
                    } else if ($act == "DELETE_PERMANENTLY") {
                        Coupon::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->forceDelete();
                        return redirect("coupon/list")->with("status", "Bạn đã xoá vĩnh viễn {$cntMember} phiếu giảm giá thành công!");
                    } else if ($act == "RESTORE") {
                        Coupon::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->restore();
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[1];
                            $this->couponRepository->update($id, $dataUpdate);
                        }
                        return redirect("coupon/list")->with("status", "Bạn đã khôi phục {$cntMember} phiếu giảm giá thành công!");
                    }
                } else {
                    return redirect("coupon/list")->with("status", "Không thể tìm thấy phiếu giảm giá nào!");
                }
            } else {
                return redirect("coupon/list")->with("status", "Bạn chưa chọn phiếu giảm giá nào để thực hiện hành động!");
            }
        } else {
            return redirect("coupon/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}