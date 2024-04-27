<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Cart extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'total_item',
        'total_price',
        'status',
    ];

    public function scopeSearch($query, $keyword, $perPage = 20, $where = array())
    {
        if (!empty($where)) {
            $query = $query
                ->where($where)
                ->whereHas('user', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', '%' . $keyword . '%');
                })
                ->orderByDesc('created_at');
        } else {
            $query = $query
                ->whereHas('user', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', '%' . $keyword . '%');
                })
                ->orderByDesc('created_at');
        }
        return $query;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function detailCarts()
    {
        return $this->hasMany(DetailCart::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}