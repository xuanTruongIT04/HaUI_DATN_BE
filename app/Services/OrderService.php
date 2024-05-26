<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Helpers\Constant;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function all()
    {
        return $this->orderRepository->getAll();
    }

     public function create($data)
    {
        return $this->orderRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->orderRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->orderRepository->delete($id);
    }

    public function find($id)
    {
        return $this->orderRepository->findOrFail($id);
    }

    public function findWithOrderID($ordertId)
    {
        return $this->orderRepository->findWithOrderID($ordertId);
    }

        public function findByCartID($cartId)
    {
        return $this->orderRepository->findByCartID($cartId);
    }

    public function updateOrCreate($data) {
        return $this->orderRepository->updateOrCreate($data);
    }

    public function getInfoOrder($cartId)
    {
        return $this->orderRepository->getInfoOrder($cartId);
    }

    public function getDetailOrder($id)
    {
        return $this->orderRepository->getDetailOrder($id);
    }

    public function submitOrder($idUser, $data)
    {
        return $this->orderRepository->submitOrder($idUser, $data);
    }

    public function checkStatusOC($cartId)
    {
        return $this->orderRepository->checkStatusOC($cartId);
    }

    public function searchOrders($keyword, $perPage, $where = array())
    {
        return $this->orderRepository->searchOrders($keyword, $perPage, $where);
    }

    public function getCoupon($id)
    {
        return $this->orderRepository->getCoupon($id);
    }

    public function swapInfoStore($user, $orderId) {
        return $this->orderRepository->swapInfoStore($user, $orderId);
    }

    public function restore($id)
    {
        return $this->orderRepository->restore($id);
    }

    public function countOrders($status = "")
    {
        return $this->orderRepository->countOrders($status);
    }

    public function getListOrderByUser($userId) {
        return $this->orderRepository->getListOrderByUser($userId);
    }

    public function constraintAction(Request $request)
    {
        // List action
        $listAct = Constant::ACTION_ORDER;
        $listStatus = array_keys(Constant::STATUS_ORDER);
        // Default status = active
        $status = !empty(request()->input('status')) ? $request->input('status') : "ordered";
        // Khai báo biến điều kiện => lọc theo trạng thái
        $where = array();
        if ($status == "ordered") {
            unset($listAct['ORDERED'], $listAct['PAID']);
            $where['status'] = $listStatus[0];
        } else if ($status == "processing") {
            unset($listAct['PROCESSING'],);
            $where['status'] = $listStatus[1];
        } else if ($status == "paid") {
            unset($listAct['PAID']);
            $where['status'] = $listStatus[2];
        }else if ($status == "cancelled") {
            unset($listAct['CANCELLED'], $listAct['PAID']);
            $where['status'] = $listStatus[3];
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
        $listStatus = array_keys(Constant::STATUS_ORDER);
        if ($act != "") {
            if ($listChecked) {
                $cntMember = count($listChecked);
                if ($cntMember > 0) {
                    if ($act == "CANCELLED") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[3];
                            $this->orderRepository->update($id, $dataUpdate);
                        }
                        return redirect("order/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} đơn hàng thành công!");
                    } else if ($act == "ORDERED") {
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[0];
                            $this->orderRepository->update($id, $dataUpdate);
                        }
                        return redirect("order/list")->with("status", "Bạn đã cấp quyền {$cntMember} đơn hàng thành công!");
                    } else if ($act == "PROCESSING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->orderRepository->update($id, $dataUpdate);
                        }
                        return redirect("order/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} đơn hàng thành công!");
                    }
                    else if ($act == "PAID") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[2];
                            $this->orderRepository->update($id, $dataUpdate);
                        }
                        return redirect("order/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} đơn hàng thành công!");
                    }
                } else {
                    return redirect("order/list")->with("status", "Không thể tìm thấy đơn hàng nào!");
                }
            } else {
                return redirect("order/list")->with("status", "Bạn chưa chọn đơn hàng nào để thực hiện hành động!");
            }
        } else {
            return redirect("order/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}
