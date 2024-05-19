<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function getDetailProduct($slug)
    {
        $status = array_keys(Constant::STATUS);
        $product = $this->model->with([
            'images' => function ($query) {
                $query->where('level', Constant::LEVEL_IMAGE[0]);
            },
            'productTags.tag',
            'category',
            'favorites'
        ])->where("slug", $slug)->where("status", $status[0])->first();
        $idProduct = $product ? $product->id : "";
        $subThumb = Image::where("product_id", $idProduct)->where("level", Constant::LEVEL_IMAGE[1])->get();
        $product["sub_thumb"] = $subThumb;
        return $product;
    }

    public function getProductRelated($idProduct)
    {
        $status = array_keys(Constant::STATUS);

        $product = $this->model->find($idProduct);
        $parentId = $product ? $product->category->parent_id : null;
        return $this->model::selectRaw("`products`.*, '' as `detail`")->with([
            'images' => function ($query) use ($status) {
                $query->where("status", $status[0])->where("level", Constant::LEVEL_IMAGE[0]);
            },
            'favorites',
            'category'
        ])->whereHas('category', function ($query) use ($parentId, $status) {
            $query->where("status", $status[0])->where('parent_id', $parentId);
        })
            ->where("status", $status[0])
            ->get();
    }

    public function getProductSellInDay()
    {
        // $status = array_keys(Constant::STATUS);
        // $products = $this->model::selectRaw("`products`.*, '' as `detail`")->with([
        //     'images' => function ($query) use ($status) {
        //         $query->where("status", $status[0])->where('level', Constant::LEVEL_IMAGE[0]);
        //     },
        //     'favorites'
        // ])->where("status", $status[0])->orderByDesc("id")->get();

        // return $products;
    }

    public function getAllLatest()
    {
        $status = array_keys(Constant::STATUS);
        $products = $this->model::selectRaw("`products`.*, '' as `detail`")->with([
            'images' => function ($query) use ($status) {
                $query->where("status", $status[0])->where('level', Constant::LEVEL_IMAGE[0]);
            },
            'favorites'
        ])->where("status", $status[0])->orderByDesc("id")->get();
        return $products;
    }

    public function getAllProductsWithMainImages()
    {
        $status = array_keys(Constant::STATUS);
        $products = $this->model::selectRaw("`products`.*, '' as `detail`")->with([
            'images' => function ($query) use ($status) {
                $query->where("status", $status[0])->orderBy('level');
            },
            'productTags.tag',
            'favorites'
        ])->where("status", $status[0])->get();
        return $products;
    }

    public function getMMPrice()
    {
        $status = array_keys(Constant::STATUS);
        $result = $this->model::where('status', $status[0])
            ->selectRaw('MAX(price * (100 - COALESCE(discount, 0)) / 100) AS max_value, MIN(price * (100 - COALESCE(discount, 0)) / 100) AS min_value')
            ->first();

        $maxValue = $result->max_value;
        $minValue = $result->min_value;

        return [
            "max_price" => $maxValue,
            "min_price" => $minValue,
        ];
    }

    public function getInfo($idProduct, $fieldInfo = "")
    {
        if (!empty($fieldInfo))
            return $this->model->find($idProduct)->{$fieldInfo};
        return $this->model->find($idProduct);
    }
    public function searchProducts($keyword, $perPage, $status, $where)
    {
        return $this->model::search($keyword, $perPage, $status, $where)->paginate($perPage);
    }

    public function restore($id)
    {
        return $this->model::onlyTrashed()->where("id", $id)->first()->restore();
    }

    public function countProducts($condition, $status)
    {
        $cnt = 0;
        if ($condition == "without") {
            if (!empty($status) || $status === 0) {
                $cnt = $this->model::withoutTrashed()->where("status", $status)->count();
            } else {
                $cnt = $this->model::withoutTrashed()->count();
            }
        } else {
            $cnt = $this->model::onlyTrashed()->count();
        }
        return $cnt;

    }

    public function countProductExpireds() {
        $expiryDayNumber = Constant::EXPIRY_DAY_NUMBER;
        $expiryDate =  \Carbon\Carbon::now()->addDays($expiryDayNumber);
        return $this->model::where("expiry_date", "<=", $expiryDate)->count();
    }

    public function countProductNeedMore() {
        $qtyNeedMore = Constant::QTY_NEED_MORE;
        return $this->model::where("qty_import", "<=", DB::raw("qty_sold + $qtyNeedMore"))->count();
    }

    public function filterProducts($filters)
    {
        $query = $this->model::query();

        if (isset($filters['min_price']) && isset($filters['max_price'])) {
            $query->whereBetween('price', [$filters['min_price'], $filters['max_price']]);
        }

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['brand'])) {
            $query->whereIn('brand_id', $filters['brand']);
        }

        if (!empty($filters['color'])) {
            $query->whereHas('image', function ($subquery) use ($filters) {
                $subquery->whereIn('color_id', $filters['color']);
            });
        }
        $status = array_keys(Constant::STATUS);
        $query->where('status', $status[0]);

        return $query->get();
    }
}
