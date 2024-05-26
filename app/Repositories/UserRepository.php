<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getInfor($idUser, $fieldInfor = "")
    {
        if (!empty($fieldInfor))
            return $this->model->find($idUser)->{$fieldInfor};
        return $this->model->find($idUser);
    }

    public function checkInfor($idUser)
    {
        if (!empty($idUser)) {
            $result = $this->model::where('id', $idUser)
                ->whereNotNull('first_name')
                ->where('first_name', '<>', '')
                ->whereNotNull('last_name')
                ->where('last_name', '<>', '')
                ->whereNotNull('phone')
                ->where('phone', '<>', '')
                ->first();

            if ($result) {
                return $result;
            }
        }

        return null;
    }

    public function all()
    {
        $users = $this->model::all();
        return $users;
    }
    public function searchUsers($keyword, $perPage, $where)
    {
        return $this->model::search($keyword, $perPage, $where)->paginate($perPage);
    }

    public function restore($id)
    {
        return $this->model::onlyTrashed()->where("id", $id)->first()->restore();
    }

    public function countUsers($status)
    {
        $cnt = 0;
        if (!empty($status) || $status === 0) {
            $cnt = $this->model::where("status", $status)->count();
        } else {
            $cnt = $this->model::count();
        }

        return $cnt;

    }
}
