<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\BillRepository;
use App\Models\Bill;
use App\Helpers\Constant;
use App\Repositories\CartRepository;

class BillService
{
    protected $billRepository, $cartRepository;

    public function __construct(BillRepository $billRepository, CartRepository $cartRepository)
    {
        $this->billRepository = $billRepository;
        $this->cartRepository = $cartRepository;
    }

    public function all()
    {
        return $this->billRepository->getAll();
    }

    public function create($data)
    {
        return $this->billRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->billRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->billRepository->delete($id);
    }

    public function find($id)
    {
        return $this->billRepository->findOrFail($id);
    }

    public function getInfoFromBill($idBill)
    {
        return $this->billRepository->getInfoFromBill($idBill);
    }

    public function getDetailOrder($idBill)
    {
        return $this->billRepository->getDetailOrder($idBill);
    }

    public function getCoupon($idBill)
    {
        return $this->billRepository->getCoupon($idBill);
    }

    public function getUser($idBill)
    {
        return $this->billRepository->getUser($idBill);
    }

    public function searchBills($keyword, $perPage = 20, $where = array())
    {
        return $this->billRepository->searchBills($keyword, $perPage, $where);
    }

    public function countBills($status = "")
    {
        return $this->billRepository->countBills($status);
    }

    public function constraintAction(Request $request)
    {
        // List action
        $listAct = Constant::ACTION_BILL;
        $listStatus = array_keys(Constant::STATUS_BILL);
        // Default status = active
        $status = !empty(request()->input('status')) ? $request->input('status') : "unPaid";
        // Khai báo biến điều kiện => lọc theo trạng thái
        $where = array();
        if ($status == "unPaid") {
            unset($listAct['UNPAID']);
            $where['status'] = $listStatus[0];
        } else if ($status == "paid") {
            unset($listAct['PAID']);
            $where['status'] = $listStatus[1];
        }
        $data = [
            "where" => $where,
            "status" => $status,
            "listAct" => $listAct
        ];
        return $data;
    }

    public function action(Request $requests)
    {

        $listChecked = $requests->input("listCheck");
        $act = $requests->input('act');
        $listStatus = array_keys(Constant::STATUS_BILL);
        if ($act != "") {
            if ($listChecked) {
                $cntMember = count($listChecked);
                if ($cntMember > 0) {
                    if ($act == "UNPAID") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[0];
                            $this->billRepository->update($id, $dataUpdate);
                        }
                        return redirect("bill/list")->with("status", "Bạn đã xét trạng thái chưa thanh toán cho {$cntMember} hoá đơn thành công!");
                    } else if ($act == "PAID") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->billRepository->update($id, $dataUpdate);
                        }
                        return redirect("bill/list")->with("status", "Bạn đã xét trạng thái đã thanh toán cho {$cntMember} hoá đơn thành công!");
                    }
                } else {
                    return redirect("bill/list")->with("status", "Không thể tìm thấy hoá đơn nào!");
                }
            } else {
                return redirect("bill/list")->with("status", "Bạn chưa chọn hoá đơn nào để thực hiện hành động!");
            }
        } else {
            return redirect("bill/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}