<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Repositories\AdminRepository;
use App\Models\Admin;
use App\Helpers\Constant;

class AdminService
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function all()
    {
        return $this->adminRepository->getAll();
    }

    public function create(array $data)
    {
        return $this->adminRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->adminRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->adminRepository->delete($id);
    }

    public function find($id)
    {
        return $this->adminRepository->findOrFail($id);
    }

    public function searchAdmins($keyword, $perPage = 20, $condition = "with", $where = array())
    {
        return $this->adminRepository->searchAdmins($keyword, $perPage, $condition, $where);
    }

    public function restore($id)
    {
        return $this->adminRepository->restore($id);
    }

    public function countAdmins($condition = "without", $status = "")
    {
        return $this->adminRepository->countAdmins($condition, $status);
    }


    public function constraintAction(Request $request)
    {
        // Update records
        // List action
        $listAct = Constant::ACTION_ADMIN;
        $listStatus = array_keys(Constant::STATUS_ADMIN);
        // Default status = active
        $status = !empty(request()->input('status')) ? $request->input('status') : 'active';
        // Khai báo biến điều kiện => lọc theo trạng thái
        $where = array();
        if ($status == "active") {

            // All record without trashed
            unset($listAct['RESTORE'], $listAct['DELETE_PERMANENTLY']);
            $condition = "without";
        } else {
            if ($status == "licensed") {

                // All record without trashed and status = licened
                unset($listAct['LICENSED'], $listAct['RESTORE'], $listAct['DELETE_PERMANENTLY']);
                $where['status'] = $listStatus[0];
                $condition = "without";
            } else {
                if ($status == "pending") {

                    // All record without trashed and status = pending
                    unset($listAct['PENDING'], $listAct['RESTORE'], $listAct['DELETE_PERMANENTLY']);
                    $where['status'] = $listStatus[1];
                    $condition = "without";
                } else {
                    if ($status == "trashed") {

                        // All record in trashed
                        unset($listAct['LICENSED'], $listAct['PENDING'], $listAct['DELETE']);
                        $condition = "only";
                    }
                }
            }
        }
        $data = [
            "where" => $where,
            "status" => $status,
            "condition" => $condition,
            "listAct" => $listAct
        ];
        return $data;
    }

    public function action(Request $requests)
    {
        $listChecked = $requests->input("listCheck");
        $act = $requests->input('act');
        $listStatus = array_keys(Constant::STATUS_ADMIN);
        if ($act != "") {
            if ($listChecked) {
                foreach ($listChecked as $k => $id) {
                    if (Auth::id() == $id) {
                        unset($listChecked[$k]);
                    }
                }
                $cntMember = count($listChecked);
                if ($cntMember > 0) {
                    if ($act == "DELETE") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[2];
                            $this->adminRepository->update($id, $dataUpdate);
                        }
                        Admin::destroy($listChecked);
                        return redirect("admin/list")->with(
                            "status",
                            "Bạn đã xoá tạm thời {$cntMember} thành viên thành công!"
                        );
                    } else {
                        if ($act == "LICENSED") {
                            foreach ($listChecked as $id) {

                                $dataUpdate['status'] = $listStatus[0];
                                $this->adminRepository->update($id, $dataUpdate);
                            }
                            return redirect("admin/list")->with(
                                "status",
                                "Bạn đã cấp quyền {$cntMember} thành viên thành công!"
                            );
                        } else {
                            if ($act == "PENDING") {
                                foreach ($listChecked as $id) {
                                    $dataUpdate['status'] = $listStatus[1];
                                    $this->adminRepository->update($id, $dataUpdate);
                                }
                                return redirect("admin/list")->with(
                                    "status",
                                    "Bạn đã xét trạng thái chờ {$cntMember} thành viên thành công!"
                                );
                            } else {
                                if ($act == "DELETE_PERMANENTLY") {
                                    Admin::onlyTrashed()
                                        ->whereIn("id", $listChecked)
                                        ->forceDelete();
                                    return redirect("admin/list")->with(
                                        "status",
                                        "Bạn đã xoá vĩnh viễn {$cntMember} thành viên thành công!"
                                    );
                                } else {
                                    if ($act == "RESTORE") {
                                        Admin::onlyTrashed()
                                            ->whereIn("id", $listChecked)
                                            ->restore();
                                        foreach ($listChecked as $id) {

                                            $dataUpdate['status'] = $listStatus[2];
                                            $this->adminRepository->update($id, $dataUpdate);
                                        }
                                        return redirect("admin/list")->with(
                                            "status",
                                            "Bạn đã khôi phục {$cntMember} thành viên thành công!"
                                        );
                                    }
                                }
                            }
                        }
                    }
                } else {
                    return redirect("admin/list")->with("status", "Bạn không thể tự thao tác chính mình!");
                }
            } else {
                return redirect("admin/list")->with("status", "Bạn chưa chọn thành viên nào để thực hiện hành động!");
            }
        } else {
            return redirect("admin/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

}