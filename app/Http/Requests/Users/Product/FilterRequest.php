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
            "numeric" => ':attribute must be a number.',
            "string" => ":attribute must be a character string.",
            'array' => ':attribute must be an array.',
            "max" => [
                "number" => ":attribute no greater than :max.",
                "file" => ":attribute is not more than :max KB.",
                "string" => ":attribute is not more than :max characters.",
                "array" => ":attribute is not more than :max item.",
            ],
            "min" => [
                "numeric" => ":attribute is not better than:min.",
                "file" => ":attribute is not less than :min KB.",
                "string" => ":attribute is not less than :min characters.",
                "array" => ":attribute must have at least :items.",
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