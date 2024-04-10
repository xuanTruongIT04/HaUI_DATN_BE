<?php

namespace App\Http\Requests\Admins\Product;

use Illuminate\Foundation\Http\FormRequest;

class EditImageProductRequest extends FormRequest
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
            "thumb" => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
            "list_thumb" => ['required', 'array', 'min:1'],
            "list_thumb.*" => ['required', 'file', "mimes:jpeg,png,jpg,gif", 'max:21000'],
            "color_id" => ['required', 'numeric', 'min:0'],
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
            "thumb" => "Hình ảnh sản phẩm",
            "color_id" => "Màu sắc sản phẩm",
            'list_thumb' => "Danh sách hình ảnh",
        ];
    }

}