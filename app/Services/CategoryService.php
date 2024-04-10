<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Models\Category;
use App\Helpers\Constant;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function all()
    {
        return $this->categoryRepository->getAll();
    }

    public function getAllLicensed()
    {
        return $this->categoryRepository->getAllLicensed();
    }

    public function getAllAscLevel()
    {
        return $this->categoryRepository->getAllAscLevel();
    }

    public function getTreeList()
    {
        return $this->categoryRepository->getTreeList();
    }

    public function create(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->categoryRepository->delete($id);
    }

    public function find($id)
    {
        return $this->categoryRepository->findOrFail($id);
    }

    public function searchCategories($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->categoryRepository->searchCategories($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->categoryRepository->restore($id);
    }

    public function countCategories($condition = "without", $status = "")
    {
        return $this->categoryRepository->countCategories($condition, $status);
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
                            $this->categoryRepository->update($id, $dataUpdate);
                        }
                        Category::destroy($listChecked);
                        return redirect("category/list")->with(
                            "status",
                            "Bạn đã xoá tạm thời {$cntMember} danh mục thành công!"
                        );
                    } else {
                        if ($act == "LICENSED") {
                            foreach ($listChecked as $id) {

                                $dataUpdate['status'] = $listStatus[0];
                                $this->categoryRepository->update($id, $dataUpdate);
                            }
                            return redirect("category/list")->with(
                                "status",
                                "Bạn đã cấp quyền {$cntMember} danh mục thành công!"
                            );
                        } else {
                            if ($act == "PENDING") {
                                foreach ($listChecked as $id) {
                                    $dataUpdate['status'] = $listStatus[1];
                                    $this->categoryRepository->update($id, $dataUpdate);
                                }
                                return redirect("category/list")->with(
                                    "status",
                                    "Bạn đã xét trạng thái chờ {$cntMember} danh mục thành công!"
                                );
                            } else {
                                if ($act == "DELETE_PERMANENTLY") {
                                    Category::onlyTrashed()
                                        ->whereIn("id", $listChecked)
                                        ->forceDelete();
                                    return redirect("category/list")->with(
                                        "status",
                                        "Bạn đã xoá vĩnh viễn {$cntMember} danh mục thành công!"
                                    );
                                } else {
                                    if ($act == "RESTORE") {
                                        Category::onlyTrashed()
                                            ->whereIn("id", $listChecked)
                                            ->restore();
                                        foreach ($listChecked as $id) {

                                            $dataUpdate['status'] = $listStatus[1];
                                            $this->categoryRepository->update($id, $dataUpdate);
                                        }
                                        return redirect("category/list")->with(
                                            "status",
                                            "Bạn đã khôi phục {$cntMember} danh mục thành công!"
                                        );
                                    }
                                }
                            }
                        }
                    }
                } else {
                    return redirect("category/list")->with("status", "Không thể tìm thấy danh mục nào!");
                }
            } else {
                return redirect("category/list")->with("status", "Bạn chưa chọn danh mục nào để thực hiện hành động!");
            }
        } else {
            return redirect("category/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}