<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
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


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}