<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'user_id',
        'status'
    ];

    public function scopeSearch($query, $keyword, $perPage = 20, $where = [])
    {
        if (!empty($keyword)) {
            $query = $query
                ->where(function ($query) use ($keyword) {
                    $query->whereHas('user', function ($query) use ($keyword) {
                        $query->where('first_name', 'like', '%' . $keyword . '%');
                    })
                        ->orWhereHas('order', function ($query) use ($keyword) {
                            $query->where('code', 'like', '%' . $keyword . '%');
                        });
                });
        }

        if (!empty($where)) {
            $query = $query->where($where);
        }

        $query = $query->orderByDesc('created_at');
        return $query;
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}