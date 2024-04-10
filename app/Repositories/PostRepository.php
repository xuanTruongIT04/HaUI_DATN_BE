<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Post;

class PostRepository extends BaseRepository
{
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function getAllLatest()
    {
        $status = array_keys(Constant::STATUS);
        return $this->model::selectRaw("`posts`.*, '' as `content`")->where("status", $status[0])
            ->orderByDesc("created_at")
            ->get();
    }

    public function getAllLicensed()
    {
        $status = array_keys(Constant::STATUS);
        return $this->model::selectRaw("`posts`.*, '' as `content`")->where("status", $status[0])
            ->orderByDesc("id")
            ->get();
    }

    public function getDetailPost($idPost)
    {
        $status = array_keys(Constant::STATUS);
        return $this->model::where("id", $idPost)->where("status", $status[0])->first();
    }

    public function searchPosts($keyword, $perPage, $status, $where)
    {
        return $this->model::search($keyword, $perPage, $status, $where)->paginate($perPage);
    }

    public function restore($id)
    {
        return $this->model::onlyTrashed()->where("id", $id)->first()->restore();
    }

    public function countPosts($condition, $status)
    {
        $cnt = 0;
        if ($condition == "without") {
            if (!empty($status) || $status === 0) {
                $cnt = $this->model::withoutTrashed()->where("status", $status)->count();
            } else {
                $cnt = $this->model::withoutTrashed()->count();
            }
        } else {
            $cnt = $this->model::onlyTrashed()->count();
        }
        return $cnt;

    }
}
