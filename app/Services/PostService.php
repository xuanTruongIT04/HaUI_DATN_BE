<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Models\Post;
use App\Helpers\Constant;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function all()
    {
        return $this->postRepository->getAll();
    }

    public function getAllLatest()
    {
        return $this->postRepository->getAllLatest();
    }

    public function getAllLicensed()
    {
        return $this->postRepository->getAllLicensed();
    }

    public function getDetailPost($idPost)
    {
        return $this->postRepository->getDetailPost($idPost);
    }

    public function create(array $data)
    {
        return $this->postRepository->create($data);
    }

    public function update($id, $data)
    {
        return $this->postRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->postRepository->delete($id);
    }

    public function find($id)
    {
        return $this->postRepository->findOrFail($id);
    }

    public function searchPosts($keyword, $perPage = 20, $status = "with", $where = array())
    {
        return $this->postRepository->searchPosts($keyword, $perPage, $status, $where);
    }

    public function restore($id)
    {
        return $this->postRepository->restore($id);
    }

    public function countPosts($condition = "without", $status = "")
    {
        return $this->postRepository->countPosts($condition, $status);
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
                            $this->postRepository->update($id, $dataUpdate);
                        }
                        Post::destroy($listChecked);
                        return redirect("post/list")->with("status", "Bạn đã xoá tạm thời {$cntMember} bài viết thành công!");
                    } else if ($act == "LICENSED") {
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[0];
                            $this->postRepository->update($id, $dataUpdate);
                        }
                        return redirect("post/list")->with("status", "Bạn đã cấp quyền {$cntMember} bài viết thành công!");
                    } else if ($act == "PENDING") {
                        foreach ($listChecked as $id) {
                            $dataUpdate['status'] = $listStatus[1];
                            $this->postRepository->update($id, $dataUpdate);
                        }
                        return redirect("post/list")->with("status", "Bạn đã xét trạng thái chờ {$cntMember} bài viết thành công!");
                    } else if ($act == "DELETE_PERMANENTLY") {
                        Post::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->forceDelete();
                        return redirect("post/list")->with("status", "Bạn đã xoá vĩnh viễn {$cntMember} bài viết thành công!");
                    } else if ($act == "RESTORE") {
                        Post::onlyTrashed()
                            ->whereIn("id", $listChecked)
                            ->restore();
                        foreach ($listChecked as $id) {

                            $dataUpdate['status'] = $listStatus[1];
                            $this->postRepository->update($id, $dataUpdate);
                        }
                        return redirect("post/list")->with("status", "Bạn đã khôi phục {$cntMember} bài viết thành công!");
                    }
                } else {
                    return redirect("post/list")->with("status", "Không thể tìm thấy bài viết nào!");
                }
            } else {
                return redirect("post/list")->with("status", "Bạn chưa chọn bài viết nào để thực hiện hành động!");
            }
        } else {
            return redirect("post/list")->with("status", "Bạn chưa chọn hành động nào để thực hiện!");
        }
    }


}