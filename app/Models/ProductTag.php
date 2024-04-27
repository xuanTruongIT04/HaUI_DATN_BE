<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTag extends Model
{
    protected $table = 'product_tags';

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'product_id',
        'tag_id',
    ];

    public function scopeSearch($query, $keyword, $perPage, $status = "with", $where = array())
    {
        if ($status == "with") {
            $query = $query->withTrashed();
        } else if ($status == "without") {
            $query = $query->withoutTrashed();
        } else {
            $query = $query->onlyTrashed();
        }

        $query = $query
            ->WhereHas('tag', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            });

        if (!empty($where)) {
            $query = $query->where($where);
        }

        $query = $query->orderByDesc('created_at');
        return $query;
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

}
