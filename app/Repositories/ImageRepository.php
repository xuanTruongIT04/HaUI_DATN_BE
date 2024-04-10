<?php

namespace App\Repositories;

use App\Helpers\Constant;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;

class ImageRepository extends BaseRepository
{
    protected $model, $modelProduct, $modelColor;

    public function __construct(Image $model, Product $modelProduct, Color $modelColor)
    {
        $this->model = $model;
        $this->modelProduct = $modelProduct;
        $this->modelColor = $modelColor;
    }

    public function getAll()
    {
        return $this->model::orderBy("level")->get();
    }

    public function searchImages($keyword, $perPage, $status, $where)
    {
        return $this->model::search($keyword, $perPage, $status, $where)->paginate($perPage);
    }

    public function restore($id)
    {
        return $this->model::onlyTrashed()->where("id", $id)->first()->restore();
    }

    public function getImagePC($idProduct)
    {
        $levelImage = Constant::LEVEL_IMAGE;
        $level = [$levelImage[0], $levelImage[1]];
        $status = array_keys(Constant::STATUS);

        if (!empty($level)) {
            $queryProduct = $this->modelProduct::with('images')->find($idProduct);
            $queryColor = $this->modelColor::whereIn('id', function ($query) use ($idProduct, $level, $status) {
                $query->select('color_id')
                    ->from('images')
                    ->where('status', $status[0])
                    ->where('product_id', $idProduct)
                    ->whereIn('level', $level)
                    ->groupBy('color_id')
                    ->havingRaw('COUNT(level) >= 2');
            })
                ->distinct()
                ->get();
            return [
                'product' => $queryProduct->toArray(),
                'colors' => $queryColor->toArray(),
            ];
        }
        return null;
    }

    public function getImageProduct($idProduct, $idColor)
    {
        $status = array_keys(Constant::STATUS);
        $image = $this->model::where("product_id", $idProduct)->where("color_id", $idColor)->where('status', $status[0])->first();

        if ($image) {
            $subImages = $this->model::where('status', $status[0])->where("product_id", $idProduct)->where("color_id", $idColor)->where("level", 1)->pluck("link")->toArray();
            $mainImage = $image->link;
            return [
                'sub_images' => $subImages,
                'main_image' => $mainImage
            ];
        }
        return null;
    }

    public function countImages($condition, $status)
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
    public function findOrCreate($dataUpdateImage)
    {
        $image = $this->checkExists($dataUpdateImage);
        if (!empty($image)) {
            $imageId = $image->id;
        } else {
            $imageId = $this->create($dataUpdateImage)->id;
        }
        return $imageId;
    }

    public function checkExists($dataCheckExists)
    {
        $image = $this->model::where("product_id", $dataCheckExists['product_id'])
            ->where("color_id", $dataCheckExists['color_id'])
            ->where("level", 0)
            ->first();
        if ($image) {
            return $image->link;
        }
        return FALSE;
    }

    public function checkExistsSubThumb($dataCheckExists)
    {
        $image = $this->model::where("product_id", $dataCheckExists['product_id'])
            ->where("color_id", $dataCheckExists['color_id'])
            ->where("level", 1)
            ->first();
        if ($image) {
            return $image->link;
        }
        return FALSE;
    }

    public function updateOrCreate($dataUpdateOrCreate)
    {
        return $this->model::updateOrCreate(
            [
                "product_id" => $dataUpdateOrCreate['product_id'],
                "color_id" => $dataUpdateOrCreate['color_id'],
                "level" => 0
            ],
            ["link" => $dataUpdateOrCreate['link']]
        );
    }

    public function updateOrCreateSubThumb($dataUpdateOrCreate)
    {
        return $this->model::updateOrCreate(
            [
                "product_id" => $dataUpdateOrCreate['product_id'],
                "color_id" => $dataUpdateOrCreate['color_id'],
                "link" => $dataUpdateOrCreate['link'],
                "level" => 1
            ],
            ["link" => $dataUpdateOrCreate['link']]
        );
    }
}