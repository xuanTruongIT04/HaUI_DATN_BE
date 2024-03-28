<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'admin';
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
        'name',
        'email',
        'avatar',
        'address',
        'password',
        'phone',
        'gender',
        'role',
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


    public function scopeSearch($query, $keyword, $perPage = 20, $condition = "with", $where = array())
    {
        if ($condition == "with") {
            $query = $query->withTrashed();
        } else if ($condition == "without") {
            $query = $query->withoutTrashed();
        } else {
            $query = $query->onlyTrashed();
        }

        if (!empty($where)) {
            $query = $query->where('name', 'like', '%' . $keyword . '%')
                ->where($where)
                ->orderBy('status')
                ->orderByDesc('role')
                ->orderByDesc('created_at');
        } else {
            $query = $query->where('name', 'like', '%' . $keyword . '%')
                ->orderBy('status')
                ->orderByDesc('role')
                ->orderByDesc('created_at');
        }
        return $query;

    }
}