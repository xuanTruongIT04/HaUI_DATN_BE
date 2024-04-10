<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{

    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'type',
        'title',
        'level',
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
            $query = $query->where('title', 'like', '%' . $keyword . '%')
                ->where($where)
                ->orderBy('type')
                ->orderBy('level')
                ->orderBy('status')
                ->orderByDesc('created_at');
        } else {
            $query = $query->where('title', 'like', '%' . $keyword . '%')
                ->orderBy('type')
                ->orderBy('level')
                ->orderBy('status')
                ->orderByDesc('created_at');
        }
        return $query;

    }
}
