<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\ProductTagRepository;
use App\Models\ProductTag;
use App\Helpers\Constant;

class ProductTagService
{
    protected $productTagRepository;

    public function __construct(ProductTagRepository $productTagRepository)
    {
        $this->productTagRepository = $productTagRepository;
    }

    public function all()
    {
        return $this->productTagRepository->getAll();
    }

    public function checkExists($dataCheck)
    {
        return $this->productTagRepository->checkExists($dataCheck);
    }

    public function create(array $data)
    {
        return $this->productTagRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->productTagRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->productTagRepository->delete($id);
    }

    public function find($id)
    {
        return $this->productTagRepository->findOrFail($id);
    }

    public function searchProductTags($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->productTagRepository->searchProductTags($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->productTagRepository->restore($id);
    }

    public function countProductTags($condition = "without", $status = "")
    {
        return $this->productTagRepository->countProductTags($condition, $status);
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
        if ($act != "") {
            if ($listChecked) {
                $cntMember = count($listChecked);
                if ($cntMember > 0) {
                    if ($act == "DELETE") {
                        ProductTag::destroy($listChecked);
                        return redirect("product-tag/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} thẻ thành công!");
                    } else if ($act == "DELETE_PERMANENTLY") {
                        ProductTag::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->forceDelete();
                        return redirect("product-tag/list")->with("status", "Bạn đã xoá vĩnh viễn {$cntMember} thẻ thành công!");
                    } else if ($act == "RESTORE") {
                        ProductTag::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->restore();
                        return redirect("product-tag/list")->with("status", "Bạn đã khôi phục {$cntMember} thẻ thành công!");
                    }
                } else {
                    return redirect("product-tag/list")->with("status", "Không thể tìm thấy thẻ nào!");
                }
            } else {
                return redirect("product-tag/list")->with("status", "Bạn chưa chọn thẻ nào để thực hiện hành động!");
            }
        } else {
            return redirect("product-tag/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}