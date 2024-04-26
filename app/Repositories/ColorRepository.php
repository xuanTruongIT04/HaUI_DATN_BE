<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Color;

class ColorRepository extends BaseRepository
{
    protected $model;

    public function __construct(Color $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function getAllLicensed()
    {
        $status = array_keys(Constant::STATUS);
        return $this->model::where("status", $status[0])->get();
    }

    public function searchColors($keyword, $perPage, $status, $where)
    {
        return $this->model::search($keyword, $perPage, $status, $where)->paginate($perPage);
    }

    public function restore($id) {
        return $this->model::onlyTrashed()->where("id", $id)->first()->restore();
    }

    public function countColors($condition, $status) {
        $cnt = 0;
        if($condition == "without") {
            if(!empty($status) || $status === 0) {
                $cnt = $this->model::withoutTrashed()->where("status", $status)->count();
            }else {
                $cnt = $this->model::withoutTrashed()->count();
            }
        }else {
            $cnt = $this->model::onlyTrashed()->count();
        }
        return $cnt;

    }
}
