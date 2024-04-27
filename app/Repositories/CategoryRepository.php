<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        $categories = $this->model::all();
        return $categories;
    }

    public function getAllLicensed()
    {
        $status = array_keys(Constant::STATUS);
        return $this->model::where("status", $status[0])->get();
    }

    public function getAllAscLevel()
    {
        return $this->model::orderBy("level")->get();
    }

    public function getTreeList()
    {
        $status = array_keys(Constant::STATUS);
        $typeProduct = array_keys(Constant::TYPE_CATEGORY);

        $categories = $this->model::where("type", $typeProduct[0])
            ->where("status", $status[0])
            ->orderBy('level')
            ->get();
        $tree = $this->buildTree($categories);

        return $tree;
    }

    private function buildTree($categories, $parentId = -1)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildTree($categories, $category->id);
                $category->children = $children;
                $tree[] = $category->toArray();
            }
        }

        return $tree;
    }

    public function searchCategories($keyword, $perPage, $status, $where)
    {
        return $this->model::search($keyword, $perPage, $status, $where)->paginate($perPage);
    }

    public function restore($id)
    {
        return $this->model::onlyTrashed()->where("id", $id)->first()->restore();
    }

    public function countCategories($condition, $status)
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