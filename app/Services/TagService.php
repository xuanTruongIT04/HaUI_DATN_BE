<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\TagRepository;
use App\Models\Tag;
use App\Helpers\Constant;

class TagService
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function all()
    {
        return $this->tagRepository->getAll();
    }

    public function listPopular()
    {
        return $this->tagRepository->listPopular();

    }

    public function create(array $data)
    {
        return $this->tagRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->tagRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->tagRepository->delete($id);
    }

    public function find($id)
    {
        return $this->tagRepository->findOrFail($id);
    }

    public function searchTags($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->tagRepository->searchTags($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->tagRepository->restore($id);
    }

    public function countTags($condition = "without", $status = "")
    {
        return $this->tagRepository->countTags($condition, $status);
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
                            $this->tagRepository->update($id, $dataUpdate);
                        }
                        Tag::destroy($listChecked);
                        return redirect("tag/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} thẻ thành công!");
                    } else if ($act == "LICENSED") {
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[0];
                            $this->tagRepository->update($id, $dataUpdate);
                        }
                        return redirect("tag/list")->with("status", "Bạn đã cấp quyền {$cntMember} thẻ thành công!");
                    } else if ($act == "PENDING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->tagRepository->update($id, $dataUpdate);
                        }
                        return redirect("tag/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} thẻ thành công!");
                    } else if ($act == "DELETE_PERMANENTLY") {
                        Tag::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->forceDelete();
                        return redirect("tag/list")->with("status", "Bạn đã xoá vĩnh viễn {$cntMember} thẻ thành công!");
                    } else if ($act == "RESTORE") {
                        Tag::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->restore();
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[1];
                            $this->tagRepository->update($id, $dataUpdate);
                        }
                        return redirect("tag/list")->with("status", "Bạn đã khôi phục {$cntMember} thẻ thành công!");
                    }
                } else {
                    return redirect("tag/list")->with("status", "Không thể tìm thấy thẻ nào!");
                }
            } else {
                return redirect("tag/list")->with("status", "Bạn chưa chọn thẻ nào để thực hiện hành động!");
            }
        } else {
            return redirect("tag/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}