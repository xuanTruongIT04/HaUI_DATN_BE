<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'status'
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

    public function productTags()
    {
        return $this->hasMany(ProductTag::class);
    }
}