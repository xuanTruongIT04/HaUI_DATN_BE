<?php

namespace App\Http\Requests\Users\Product;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric'],
            'category' => ['nullable', 'numeric'],
            'brand' => ['nullable', 'array'],
            'brand.*' => ['nullable', 'numeric'],
            'color' => ['nullable', 'array'],
            'color.*' => ['nullable', 'numeric'],
        ];
    }

    public function messages()
    {
        return [
            "numeric" => ':attribute phải là một số.',
            "string" => ":attribute phải là chuỗi ký tự.",
            'array' => ':attribute phải là một mảng.',
            "max" => [
                "number" => ":attribute không được lớn hơn :max.",
                "file" => ":attribute không được lớn hơn :max KB.",
                "string" => ":attribute không được lớn hơn :max ký tự.",
                "array" => ":attribute không được lớn hơn :max phần tử.",
            ],
            "min" => [
                "numeric" => ":attribute không được nhỏ hơn :min.",
                "file" => ":attribute không được nhỏ hơn :min KB.",
                "string" => ":attribute không được nhỏ hơn :min ký tự.",
                "array" => ":attribute phải có ít nhất :min phần tử.",
            ],
        ];
    }


    public function attributes()
    {
        return [
            'min_price' => 'Minimum Price',
            'max_price' => 'Maximum Price',
            'categories' => 'Categories',
            'categories.*' => 'Category',
            'brand' => 'Brand',
            'brand.*' => 'Brand',
            'color' => 'Color',
            'color.*' => 'Color',
        ];
    }
}
