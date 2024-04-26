<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\ProductTag;

class ProductTagRepository extends BaseRepository
{
    protected $model;

    public function __construct(ProductTag $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function checkExists($dataCheck)
    {
        $productId = $dataCheck['product_id'];
        $tagId = $dataCheck['tag_id'];
        return $this->model::where("product_id", $productId)->where("tag_id", $tagId)->first();
    }

    public function listPopular()
    {
        $status = array_keys(Constant::STATUS);
        return $this->model::where("status", $status[0])->orderByDesc("view_count")->get();
    }

    public function searchProductTags($keyword, $perPage, $status, $where)
    {
        return $this->model::search($keyword, $perPage, $status, $where)->paginate($perPage);
    }

    public function restore($id)
    {
        return $this->model::onlyTrashed()->where("id", $id)->first()->restore();
    }

    public function countProductTags($condition, $status)
    {
        $cnt = 0;
        if ($condition == "without") {
            if (!empty($status) || $status === 0) {
                $cnt = $this->model::withoutTrashed()->count();
            } else {
                $cnt = $this->model::withoutTrashed()->count();
            }
        } else {
            $cnt = $this->model::onlyTrashed()->count();
        }
        return $cnt;

    }
}