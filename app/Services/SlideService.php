<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\SlideRepository;
use App\Models\Slide;
use App\Helpers\Constant;

class SlideService
{
    protected $slideRepository;

    public function __construct(SlideRepository $slideRepository)
    {
        $this->slideRepository = $slideRepository;
    }

    public function all()
    {
        return $this->slideRepository->getAll();
    }

    public function getAllLicensed()
    {
        return $this->slideRepository->getAllLicensed();
    }

    public function create(array $data)
    {
        return $this->slideRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->slideRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->slideRepository->delete($id);
    }

    public function find($id)
    {
        return $this->slideRepository->findOrFail($id);
    }

    public function searchSlides($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->slideRepository->searchSlides($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->slideRepository->restore($id);
    }

    public function countSlides($condition = "without", $status = "")
    {
        return $this->slideRepository->countSlides($condition, $status);
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
                            $this->slideRepository->update($id, $dataUpdate);
                        }
                        Slide::destroy($listChecked);
                        return redirect("slide/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} màu sắc thành công!");
                    } else if ($act == "LICENSED") {
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[0];
                            $this->slideRepository->update($id, $dataUpdate);
                        }
                        return redirect("slide/list")->with("status", "Bạn đã cấp quyền {$cntMember} màu sắc thành công!");
                    } else if ($act == "PENDING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->slideRepository->update($id, $dataUpdate);
                        }
                        return redirect("slide/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} màu sắc thành công!");
                    } else if ($act == "DELETE_PERMANENTLY") {
                        Slide::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->forceDelete();
                        return redirect("slide/list")->with("status", "Bạn đã xoá vĩnh viễn {$cntMember} màu sắc thành công!");
                    } else if ($act == "RESTORE") {
                        Slide::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->restore();
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[1];
                            $this->slideRepository->update($id, $dataUpdate);
                        }
                        return redirect("slide/list")->with("status", "Bạn đã khôi phục {$cntMember} màu sắc thành công!");
                    }
                } else {
                    return redirect("slide/list")->with("status", "Không thể tìm thấy màu sắc nào!");
                }
            } else {
                return redirect("slide/list")->with("status", "Bạn chưa chọn màu sắc nào để thực hiện hành động!");
            }
        } else {
            return redirect("slide/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}