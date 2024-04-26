<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\ColorRepository;
use App\Models\Color;
use App\Helpers\Constant;

class ColorService
{
    protected $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    public function all()
    {
        return $this->colorRepository->getAll();
    }

    public function getAllLicensed()
    {
        return $this->colorRepository->getAllLicensed();
    }

    public function create(array $data)
    {
        return $this->colorRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->colorRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->colorRepository->delete($id);
    }

    public function find($id)
    {
        return $this->colorRepository->findOrFail($id);
    }

    public function searchColors($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->colorRepository->searchColors($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->colorRepository->restore($id);
    }

    public function countColors($condition = "without", $status = "")
    {
        return $this->colorRepository->countColors($condition, $status);
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
        } else {
            if ($status == "licensed") {

                // All record without trashed and status = licened
                unset($listAct['LICENSED'], $listAct['RESTORE'], $listAct['DELETE_PERMANENTLY']);
                $where['status'] = $listStatus[0];
                $statusData = "without";
            } else {
                if ($status == "pending") {

                    // All record without trashed and status = pending
                    unset($listAct['PENDING'], $listAct['RESTORE'], $listAct['DELETE_PERMANENTLY']);
                    $where['status'] = $listStatus[1];
                    $statusData = "without";
                } else {
                    if ($status == "trashed") {

                        // All record in trashed
                        unset($listAct['LICENSED'], $listAct['PENDING'], $listAct['DELETE']);
                        $statusData = "only";
                    }
                }
            }
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
                            $this->colorRepository->update($id, $dataUpdate);
                        }
                        Color::destroy($listChecked);
                        return redirect("color/list")->with(
                            "status",
                            "Bạn đã xoá tạm thời {$cntMember} màu sắc thành công!"
                        );
                    } else {
                        if ($act == "LICENSED") {
                            foreach ($listChecked as $id) {

                                $dataUpdate['status'] = $listStatus[0];
                                $this->colorRepository->update($id, $dataUpdate);
                            }
                            return redirect("color/list")->with(
                                "status",
                                "Bạn đã cấp quyền {$cntMember} màu sắc thành công!"
                            );
                        } else {
                            if ($act == "PENDING") {
                                foreach ($listChecked as $id) {
                                    $dataUpdate['status'] = $listStatus[1];
                                    $this->colorRepository->update($id, $dataUpdate);
                                }
                                return redirect("color/list")->with(
                                    "status",
                                    "Bạn đã xét trạng thái chờ {$cntMember} màu sắc thành công!"
                                );
                            } else {
                                if ($act == "DELETE_PERMANENTLY") {
                                    Color::onlyTrashed()
                                        ->whereIn("id", $listChecked)
                                        ->forceDelete();
                                    return redirect("color/list")->with(
                                        "status",
                                        "Bạn đã xoá vĩnh viễn {$cntMember} màu sắc thành công!"
                                    );
                                } else {
                                    if ($act == "RESTORE") {
                                        Color::onlyTrashed()
                                            ->whereIn("id", $listChecked)
                                            ->restore();
                                        foreach ($listChecked as $id) {

                                            $dataUpdate['status'] = $listStatus[1];
                                            $this->colorRepository->update($id, $dataUpdate);
                                        }
                                        return redirect("color/list")->with(
                                            "status",
                                            "Bạn đã khôi phục {$cntMember} màu sắc thành công!"
                                        );
                                    }
                                }
                            }
                        }
                    }
                } else {
                    return redirect("color/list")->with("status", "Không thể tìm thấy màu sắc nào!");
                }
            } else {
                return redirect("color/list")->with("status", "Bạn chưa chọn màu sắc nào để thực hiện hành động!");
            }
        } else {
            return redirect("color/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}