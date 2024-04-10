<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'price',
        'discount',
        'qty_import',
        'qty_sold',
        'description',
        'detail',
        'rate',
        'category_id',
        'brand_id',
        'slug',
        'status',
    ];

    public function scopeSearch($query, $keyword, $perPage = 20, $status = "with", $where = [])
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

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->generateUniqueCode();
            $model->generateUniqueSlug();
        });
    }
    //GENERATE CODE
    public function generateUniqueCode()
    {
        $code = $this->generateCode();
        $suffix = 1;

        while ($this->codeExists($code)) {
            $code = $this->generateCode();
            $code .= $suffix;
            $suffix++;
        }

        $this->code = '#SBC-' . $code;
    }

    public function generateCode()
    {
        $id = $this->getAttribute('id');
        $name = $this->getAttribute('name');
        return $id . substr(md5($name), 10, 6);
    }

    public function codeExists($code)
    {
        $count = $this->where('code', $code)->count();

        return $count > 0;
    }

    //GENERATE SLUG
    public function generateUniqueSlug()
    {
        $slug = Str::slug($this->name);
        $suffix = 1;

        while ($this->slugExists($slug)) {
            $slug .= '-' . $suffix;
            $suffix++;
        }

        $this->slug = $slug;
    }

    public function slugExists($slug)
    {
        $count = $this->where('slug', $slug)->count();

        return $count > 0;
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id', 'id');
    }

    public function productTags()
    {
        return $this->hasMany(ProductTag::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

}