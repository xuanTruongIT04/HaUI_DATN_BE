<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Models\Product;
use App\Helpers\Constant;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function all()
    {
        return $this->productRepository->getAll();
    }

    public function getAllLatest()
    {
        return $this->productRepository->getAllLatest();
    }

    public function getDetailProduct($slug)
    {
        return $this->productRepository->getDetailProduct($slug);
    }

    public function getProductRelated($idProduct)
    {
        return $this->productRepository->getProductRelated($idProduct);
    }

    public function getMMPrice()
    {
        return $this->productRepository->getMMPrice();
    }

    public function getAllProductsWithMainImages()
    {
        return $this->productRepository->getAllProductsWithMainImages();
    }

    public function getInfo($idProduct, $fieldInfo = "")
    {
        return $this->productRepository->getInfo($idProduct, $fieldInfo);
    }

    public function create(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->productRepository->delete($id);
    }

    public function find($id)
    {
        return $this->productRepository->findOrFail($id);
    }

    public function searchProducts($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->productRepository->searchProducts($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->productRepository->restore($id);
    }

    public function countProducts($condition = "without", $status = "")
    {
        return $this->productRepository->countProducts($condition, $status);
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
                            $this->productRepository->update($id, $dataUpdate);
                        }
                        Product::destroy($listChecked);
                        return redirect("product/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} sản phẩm thành công!");
                    } else if ($act == "LICENSED") {
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[0];
                            $this->productRepository->update($id, $dataUpdate);
                        }
                        return redirect("product/list")->with("status", "Bạn đã cấp quyền {$cntMember} sản phẩm thành công!");
                    } else if ($act == "PENDING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->productRepository->update($id, $dataUpdate);
                        }
                        return redirect("product/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} sản phẩm thành công!");
                    } else if ($act == "DELETE_PERMANENTLY") {
                        Product::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->forceDelete();
                        return redirect("product/list")->with("status", "Bạn đã xoá vĩnh viễn {$cntMember} sản phẩm thành công!");
                    } else if ($act == "RESTORE") {
                        Product::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->restore();
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[1];
                            $this->productRepository->update($id, $dataUpdate);
                        }
                        return redirect("product/list")->with("status", "Bạn đã khôi phục {$cntMember} sản phẩm thành công!");
                    }
                } else {
                    return redirect("product/list")->with("status", "Không thể tìm thấy sản phẩm nào!");
                }
            } else {
                return redirect("product/list")->with("status", "Bạn chưa chọn sản phẩm nào để thực hiện hành động!");
            }
        } else {
            return redirect("product/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }

    public function filterProducts($filters)
    {
        return $this->productRepository->filterProducts($filters);
    }


}