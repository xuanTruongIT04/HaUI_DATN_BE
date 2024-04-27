<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price_sale',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->price_sale = ($model->product->discount) ? $model->product->price * (100 - $model->product->discount) / 100 : $model->product->price;
        });
    }

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
            $query = $query->where('price_sale', 'like', '%' . $keyword . '%')
                ->where($where)
                ->orderByDesc('created_at');
        } else {
            $query = $query->where('price_sale', 'like', '%' . $keyword . '%')
                ->orderByDesc('created_at');
        }
        return $query;

    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}