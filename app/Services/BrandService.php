<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\BrandRepository;
use App\Models\Brand;
use App\Helpers\Constant;

class BrandService
{
    protected $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function all()
    {
        return $this->brandRepository->getAll();
    }

    public function getAllLicensed()
    {
        return $this->brandRepository->getAllLicensed();
    }

    public function create(array $data)
    {
        return $this->brandRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->brandRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->brandRepository->delete($id);
    }

    public function find($id)
    {
        return $this->brandRepository->findOrFail($id);
    }

    public function searchBrands($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->brandRepository->searchBrands($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->brandRepository->restore($id);
    }

    public function countBrands($condition = "without", $status = "")
    {
        return $this->brandRepository->countBrands($condition, $status);
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
                            $this->brandRepository->update($id, $dataUpdate);
                        }
                        Brand::destroy($listChecked);
                        return redirect("brand/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} nhãn hiệu thành công!");
                    } else if ($act == "LICENSED") {
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[0];
                            $this->brandRepository->update($id, $dataUpdate);
                        }
                        return redirect("brand/list")->with("status", "Bạn đã cấp quyền {$cntMember} nhãn hiệu thành công!");
                    } else if ($act == "PENDING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->brandRepository->update($id, $dataUpdate);
                        }
                        return redirect("brand/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} nhãn hiệu thành công!");
                    } else if ($act == "DELETE_PERMANENTLY") {
                        Brand::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->forceDelete();
                        return redirect("brand/list")->with("status", "Bạn đã xoá vĩnh viễn {$cntMember} nhãn hiệu thành công!");
                    } else if ($act == "RESTORE") {
                        Brand::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->restore();
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[1];
                            $this->brandRepository->update($id, $dataUpdate);
                        }
                        return redirect("brand/list")->with("status", "Bạn đã khôi phục {$cntMember} nhãn hiệu thành công!");
                    }
                } else {
                    return redirect("brand/list")->with("status", "Không thể tìm thấy nhãn hiệu nào!");
                }
            } else {
                return redirect("brand/list")->with("status", "Bạn chưa chọn nhãn hiệu nào để thực hiện hành động!");
            }
        } else {
            return redirect("brand/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}
