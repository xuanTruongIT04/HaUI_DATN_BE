<?php
use App\Helpers\Constant;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Tag;

if (!function_exists('templateCategoryParent')) {
    function templateCategoryParent($categories, $parent_id = "")
    {
        if (!empty($categories)) {
            $str = "<select name='parent_id' id='parent-id' class='form-control'>";
            $str .= '<option value="-1" style="font-style: italic;">*** Không có danh mục cha ***</option>';
            foreach ($categories as $category) {
                $sel = "";
                if ($category->id == $parent_id) {
                    $sel = "selected='selected'";
                }

                $str .= "<option value=" . $category->id . " " . $sel . " >"
                    . str_repeat('-', $category->level) . ' ' . $category->title
                    . "</option>";
            }
            $str .= "</select>";
        } else {
            $str = '<p class="empty-task">Không tồn tại danh mục nào</p>';
        }

        return $str;
    }
}

if (!function_exists('templateCategoryProduct')) {
    function templateCategoryProduct($category_id = "")
    {
        $type_category = array_keys(Constant::TYPE_CATEGORY);
        $categories = Category::all()->where("type", $type_category["0"]);
        if (!empty($categories)) {
            $str = "<select name='category_id' id='category-id' class='form-control'>";
            foreach ($categories as $category) {
                $sel = "";
                if ($category->id == $category_id) {
                    $sel = "selected='selected'";
                }

                $str .= "<option value=" . $category->id . " " . $sel . " >"
                    . str_repeat('-', $category->level) . ' ' . $category->title
                    . "</option>";
            }
            $str .= "</select>";
        } else {
            $str = '<p class="empty-task">Không tồn tại danh mục nào</p>';
        }


        return $str;
    }
}


if (!function_exists('templateBrandProduct')) {
    function templateBrandProduct($brandId = "")
    {
        $brands = Brand::all();
        if (!empty($brands)) {
            $str = "<select name='brand_id' id='brand-id' class='form-control'>";
            foreach ($brands as $brand) {
                $sel = "";
                if ($brand->id == $brandId) {
                    $sel = "selected='selected'";
                }

                $str .= "<option value=" . $brand->id . " " . $sel . " >" . $brand->name . "</option>";
            }
            $str .= "</select>";
        } else {
            $str = '<p class="empty-task">Không tồn tại nhãn hiệu nào</p>';
        }

        return $str;
    }
}

if (!function_exists('templateTagProduct')) {
    function templateTagProduct($tagId = "")
    {
        $tags = Tag::all();
        if (!empty($tags)) {
            $str = "<select name='tag_id' id='tag-id' class='form-control'>";
            foreach ($tags as $tag) {
                $sel = "";
                if ($tag->id == $tagId) {
                    $sel = "selected='selected'";
                }

                $str .= "<option value=" . $tag->id . " " . $sel . " >" . $tag->name . "</option>";
            }
            $str .= "</select>";
        } else {
            $str = '<p class="empty-task">Không tồn tại nhãn hiệu nào</p>';
        }

        return $str;
    }
}


if (!function_exists('templateColorProduct')) {
    function templateColorProduct($colorId = "")
    {
        $colors = Color::orderByDesc("created_at")->get();
        $str = "";

        if (!empty($colors)) {
            $options = ["<option value=''>--Chọn màu--</option>"];

            foreach ($colors as $color) {
                $selected = ($color->id == $colorId) ? "selected='selected'" : "";
                $options[] = "<option value='{$color->id}' {$selected}>{$color->name}</option>";
            }

            $str = "<select name='color_id' id='color-id-prev' class='form-control'>";
            $str .= implode("", $options);
            $str .= "</select>";
        } else {
            $str = '<p class="empty-task">Không tồn tại màu sắc nào</p>';
        }

        return $str;
    }
}

if (!function_exists('templateProduct')) {
    function templateProduct($productId = "")
    {
        $products = Product::orderByDesc("created_at")->get();
        if (!empty($products)) {
            $str = "<select name='product_id' id='product-id-prev' class='form-control'>";
            foreach ($products as $product) {
                $sel = "";
                if ($product->id == $productId) {
                    $sel = "selected='selected'";
                }

                $str .= "<option value=" . $product->id . " " . $sel . " >" . $product->name . "</option>";
            }
            $str .= "</select>";
        } else {
            $str = '<p class="empty-task">Không tồn tại màu sắc nào</p>';
        }

        return $str;
    }
}



if (!function_exists('briefName')) {
    function briefName($str, $nWords)
    {
        if (strlen($str) <= $nWords)
            return $str;
        else {
            $str_temp_1 = explode(" ", $str);
            $str_temp_2 = array();
            for ($i = 0; $i < $nWords; $i++) {
                if (isset($str_temp_1[$i]))
                    $str_temp_2[] = $str_temp_1[$i];
            }
            $str_or = implode(" ", $str_temp_2) . " ...";
            return $str_or;
        }

    }
}
if (!function_exists('brief_code')) {
    function brief_code($string, $countChar)
    {
        if (strlen($string) <= $countChar) {
            return $string;
        } else {
            return substr($string, 0, $countChar) . "...";
        }
    }
}