<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $guard = 'user';
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'password',
        'email',
        'phone',
        'fax',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeSearch($query, $keyword, $perPage = 20, $where = array())
    {
        if (!empty($where)) {
            $query = $query->where('first_name', 'like', '%' . '' . '%')
                ->where($where);
        } else {
            $query = $query->where('first_name', 'like', '%' . $keyword . '%')
                ->orderBy('status')
                ->orderByDesc('created_at');
        }
        return $query;
    }

    public function setPasswordAttributes($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
