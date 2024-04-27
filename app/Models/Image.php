<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Image extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'product_id',
        'color_id',
        'link',
        'level',
        'description',
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
            $query = $query->where('description', 'like', '%' . $keyword . '%')
                ->where($where)
                ->orderBy('level')
                ->orderByDesc('created_at');
        } else {
            $query = $query->where('description', 'like', '%' . $keyword . '%')
                ->orderBy('level')
                ->orderByDesc('created_at');
        }
        return $query;

    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function getLinkAttribute($value)
    {
        return env("APP_URL") . "/" . $value;
    }
}