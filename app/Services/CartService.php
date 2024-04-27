<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\CartRepository;
use App\Models\Cart;
use App\Helpers\Constant;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function all()
    {
        return $this->cartRepository->getAll();
    }

    public function update($id, $data)
    {
        return $this->cartRepository->update($id, $data);
    }

        public function updateCart($id, $data)
    {
        return $this->cartRepository->updateCart($id, $data);
    }

    public function delete($id)
    {
        return $this->cartRepository->delete($id);
    }

    public function find($id)
    {
        return $this->cartRepository->findOrFail($id);
    }

    public function getDetailCart($id)
    {
        return $this->cartRepository->getDetailCart($id);
    }

    public function searchCarts($keyword, $perPage = 20, $where = array())
    {
        return $this->cartRepository->searchCarts($keyword, $perPage, $where);
    }

    public function countCarts($status = "")
    {
        return $this->cartRepository->countCarts($status);
    }

    public function getCartByUser($user_id) {
        return $this->cartRepository->getCartByUser($user_id);
    }

    public function addCart($productId, $numberOrder, $userId) {
        return $this->cartRepository->addCart($productId, $numberOrder, $userId);
    }

    public function updateProductInCart($data, $idUser) {
        return $this->cartRepository->updateProductInCart($data, $idUser);
    }

    public function deleteProductInCart($idDC) {
        return $this->cartRepository->deleteProductInCart($idDC);
    }
    
    public function deleteAll($idUser) {
        return $this->cartRepository->deleteAll($idUser);
    }
    public function constraintAction(Request $request)
    {
        // Update records
        // List action
        $listAct = Constant::ACTION_CART;
        $listStatus = array_keys(Constant::STATUS_CART);
        // Default status = active
        $status = !empty(request()->input('status')) ? $request->input('status') : 'active';
        // Khai báo biến điều kiện => lọc theo trạng thái
        $where = array();
        if ($status == "active") {
            unset($listAct['ACTIVE']);
            $where['status'] = $listStatus[0];
        } else if ($status == "paid") {
            unset($listAct['PAID'],);
            $where['status'] = $listStatus[1];
        } else if ($status == "expired") {
            unset($listAct['EXPIRED']);
            $where['status'] = $listStatus[2];
        }else if ($status == "cancelled") {
            unset($listAct['CANCELLED']);
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
        $listStatus = array_keys(Constant::STATUS_CART);
        if ($act != "") {
            if ($listChecked) {
                $cntCart = count($listChecked);
                if ($cntCart > 0) {
                    if ($act == "CANCELLED") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[3];
                            $this->cartRepository->update($id, $dataUpdate);
                        }
                        return redirect("cart/list")->with("status", "Bạn đã huỷ tạm thời {$cntCart} giỏ hàng thành công!");
                    } else if ($act == "ACTIVE") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[0];
                            $this->cartRepository->update($id, $dataUpdate);
                        }
                        return redirect("cart/list")->with("status", "Bạn đã cấp quyền {$cntCart} giỏ hàng thành công!");
                    } else if ($act == "PAID") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->cartRepository->update($id, $dataUpdate);
                        }
                        return redirect("cart/list")->with("status", "Bạn đã xét trạng thái đã thanh toán {$cntCart} giỏ hàng thành công!");
                    } else if ($act == "EXPIRED") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[2];
                            $this->cartRepository->update($id, $dataUpdate);
                        }
                        return redirect("cart/list")->with("status", "Bạn đã xét trạng thái hết hạn {$cntCart} giỏ hàng thành công!");
                    }
                } else {
                    return redirect("cart/list")->with("status", "Không thể tìm thấy giỏ hàng nào!");
                }
            } else {
                return redirect("cart/list")->with("status", "Bạn chưa chọn giỏ hàng nào để thực hiện hành động!");
            }
        } else {
            return redirect("cart/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }
}