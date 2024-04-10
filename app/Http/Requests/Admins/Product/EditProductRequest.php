<?php

namespace App\Http\Requests\Admins\Product;

use Illuminate\Foundation\Http\FormRequest;

class EditProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => ['required', 'numeric', 'min:0'],
            'brand_id' => ['required', 'numeric', 'min:0'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'qty_import' => ['nullable', 'numeric', 'min:0'],
            'qty_sold' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable'],
            'detail' => ['required'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            "string" => ":attribute phải là một chuỗi kí tự.",
            "required" => ":attribute không được bỏ trống.",
            "max" => [
                "numeric" => ":attribute không được lớn hơn :max.",
                "file" => ":attribute không được nhiều hơn :max KB.",
                "string" => ":attribute không được nhiều hơn :max kí tự.",
                "array" => ":attribute không được nhiều hơn :max mục.",
            ],
            "min" => [
                "numeric" => ":attribute không được bé hơn :min.",
                "file" => ":attribute không được ít hơn :min KB.",
                "string" => ":attribute không được ít hơn :min kí tự.",
                "array" => ":attribute phải có ít nhất :min mục.",
            ],

        ];
    }

    public function attributes()
    {
        return [
            'category_id' => "Danh mục sản phẩm",
            'brand_id' => "Nhãn hiệu sản phẩm",
            'code' => "Mã sản phẩm",
            'name' => "Tên sản phẩm",
            'price' => "Giá sản phẩm",
            'discount' => "Phần trăm giảm giá",
            'qty_import' => "Số lượng nhập",
            'qty_sold' => "Số lượng bán",
            'description' => "Mô tả sản phẩm",
            'detail' => "Chi tiết sản phẩm",
            'rate' => "Điểm đánh giá sản phẩm",
            "status" => "Trạng thái sản phẩm",
        ];
    }

}