<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all()
    {
        return $this->userRepository->getAll();
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }

    public function find($id)
    {
        return $this->userRepository->findOrFail($id);
    }

    public function getInfor($idUser, $fieldInfo = "")
    {
        return $this->userRepository->getInfor($idUser, $fieldInfo);
    }

    public function checkInfor($idUser)
    {
        return $this->userRepository->checkInfor($idUser);
    }

    public function searchUsers($keyword, $perPage = 20, $where = array())
    {
        return $this->userRepository->searchUsers($keyword, $perPage, $where);
    }

    public function update($id, $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function restore($id)
    {
        return $this->userRepository->restore($id);
    }

    public function countUsers($status = "")
    {
        return $this->userRepository->countUsers($status);
    }

    public function constraintAction(Request $request)
    {
        // List action
        $listAct = Constant::ACTION_USER;
        $listStatus = array_keys(Constant::STATUS_USER);
        // Default status = active
        $status = !empty(request()->input('status')) ? $request->input('status') : 'active';
        // Khai báo biến điều kiện => lọc theo trạng thái
        $where = array();
        if ($status == "active") {
            // All record without blocked
            unset($listAct['ACTIVE']);
            $where['status'] = $listStatus[0];
        } else if ($status == "pending") {
            // All record without blocked and status = pending
            unset($listAct['PENDING']);
            $where['status'] = $listStatus[1];
        } else if ($status == "blocked") {
            // All record in blocked
            unset($listAct['BLOCKED']);
            $where['status'] = $listStatus[2];
        }
        $data = [
            "where" => $where,
            "listAct" => $listAct
        ];
        return $data;
    }

    public function action(Request $requests)
    {
        $listChecked = $requests->input("listCheck");
        $act = $requests->input('act');
        $listStatus = array_keys(Constant::STATUS_USER);
        if ($act != "") {
            if ($listChecked) {
                $cntMember = count($listChecked);
                if ($cntMember > 0) {
                    if ($act == "BLOCKED") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[2];
                            $this->userRepository->update($id, $dataUpdate);
                        }
                        return redirect("user/list")->with("status", "Bạn đã khoá tạm thời {$cntMember} khách hàng thành công!");
                    } else if ($act == "ACTIVE") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[0];
                            $this->userRepository->update($id, $dataUpdate);
                        }
                        return redirect("user/list")->with("status", "Bạn đã cấp quyền {$cntMember} khách hàng thành công!");
                    } else if ($act == "PENDING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->userRepository->update($id, $dataUpdate);
                        }
                        return redirect("user/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} khách hàng thành công!");
                    }
                } else {
                    return redirect("user/list")->with("status", "Không thể tìm thấy khách hàng nào!");
                }
            } else {
                return redirect("user/list")->with("status", "Bạn chưa chọn khách hàng nào để thực hiện hành động!");
            }
        } else {
            return redirect("user/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }
}
