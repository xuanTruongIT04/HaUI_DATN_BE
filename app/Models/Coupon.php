<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'percent',
        'start_date',
        'end_date',
        'status',
    ];

    public function scopeSearch($query, $keyword, $perPage = 20, $status = "with", $where = array())
    {
        if ($status == "with") {
            $query = $query->withTrashed();
        } else if ($status == "without") {
            $query = $query->withoutTrashed();
        } else {
            $query = $query->onlyTrashed();
        }

        if (!empty($where)) {
            $query = $query->where('name', 'like', '%' . $keyword . '%')
                ->where($where)
                ->orderBy('status')
                ->orderByDesc('created_at');
        } else {
            $query = $query->where('name', 'like', '%' . $keyword . '%')
                ->orderBy('status')
                ->orderByDesc('created_at');
        }
        return $query;

    }
}