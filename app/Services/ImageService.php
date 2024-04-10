<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\ImageRepository;
use App\Models\Image;
use App\Helpers\Constant;

class ImageService
{
    protected $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function all()
    {
        return $this->imageRepository->getAll();
    }

    public function create(array $data)
    {
        return $this->imageRepository->create($data);
    }

    public function updateOrCreate(array $dataUpdateOrCreateImage)
    {
        return $this->imageRepository->updateOrCreate($dataUpdateOrCreateImage);
    }

    public function updateOrCreateSubThumb(array $dataUpdateOrCreateImage)
    {
        return $this->imageRepository->updateOrCreateSubThumb($dataUpdateOrCreateImage);
    }

    public function checkExists(array $dataCheckExists)
    {
        return $this->imageRepository->checkExists($dataCheckExists);
    }

    public function checkExistsSubThumb(array $dataCheckExistsSubThumb)
    {
        return $this->imageRepository->checkExistsSubThumb($dataCheckExistsSubThumb);
    }

    public function find($dataImage)
    {
        return $this->imageRepository->findOrFail($dataImage);
    }

    public function getImagePC($idProduct)
    {
        return $this->imageRepository->getImagePC($idProduct);
    }

    public function getImageProduct($idProduct, $idColor)
    {
        return $this->imageRepository->getImageProduct($idProduct, $idColor);
    }

    public function findOrCreate($dataUpdateImage)
    {
        return $this->imageRepository->findOrCreate($dataUpdateImage);
    }

    public function update($id, $data)
    {
        return $this->imageRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->imageRepository->delete($id);
    }

    public function searchImages($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->imageRepository->searchImages($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->imageRepository->restore($id);
    }

    public function countImages($condition = "without", $status = "")
    {
        return $this->imageRepository->countImages($condition, $status);
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
                            $this->imageRepository->update($id, $dataUpdate);
                        }
                        Image::destroy($listChecked);
                        return redirect("image/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} hình ảnh thành công!");
                    } else if ($act == "LICENSED") {
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[0];
                            $this->imageRepository->update($id, $dataUpdate);
                        }
                        return redirect("image/list")->with("status", "Bạn đã cấp quyền {$cntMember} hình ảnh thành công!");
                    } else if ($act == "PENDING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->imageRepository->update($id, $dataUpdate);
                        }
                        return redirect("image/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} hình ảnh thành công!");
                    } else if ($act == "DELETE_PERMANENTLY") {
                        Image::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->forceDelete();
                        return redirect("image/list")->with("status", "Bạn đã xoá vĩnh viễn {$cntMember} hình ảnh thành công!");
                    } else if ($act == "RESTORE") {
                        Image::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->restore();
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[1];
                            $this->imageRepository->update($id, $dataUpdate);
                        }
                        return redirect("image/list")->with("status", "Bạn đã khôi phục {$cntMember} hình ảnh thành công!");
                    }
                } else {
                    return redirect("image/list")->with("status", "Không thể tìm thấy hình ảnh nào!");
                }
            } else {
                return redirect("image/list")->with("status", "Bạn chưa chọn hình ảnh nào để thực hiện hành động!");
            }
        } else {
            return redirect("image/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }
}