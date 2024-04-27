<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'address_delivery',
        'payment_method',
        'total_mount',
        'order_date',
        'status',
        'cart_id',
        'coupon_id',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->generateUniqueCode();
            $percent = $model->coupon->percent ?? '0.00';
            $percent = floatval($percent);
            $model->total_mount = $model->cart->total_price * (100 - $percent) / 100;
        });
    }

    //GENERATE CODE
    public function generateUniqueCode()
    {
        $code = Uuid::uuid4()->toString();
        $this->code = '#CSC-' . $code;
    }

    public function generateCode()
    {
        $id = $this->getAttribute('id');
        $orderDate = $this->getAttribute('order_date');
        return $id . substr(md5($orderDate), 10, 6);
    }

    public function codeExists($code)
    {
        $count = $this->where('code', $code)->count();

        return $count > 0;
    }

    public function scopeSearch($query, $keyword, $perPage = 20, $where = array())
    {
        $query = $query->orderByDesc('created_at');

        if (!empty($where)) {
            $query = $query->where($where);
        }

        $query = $query->where(function ($query) use ($keyword) {
            $query->where('code', 'like', '%' . $keyword . '%')
                ->orWhereHas('cart', function ($query) use ($keyword) {
                    $query->whereHas('user', function ($query) use ($keyword) {
                        $query->where('first_name', 'like', '%' . $keyword . '%');
                    });
                })
                ->orderByDesc("created_at");
        });

        return $query;
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}