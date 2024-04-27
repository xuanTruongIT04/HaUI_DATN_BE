<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInforRequest extends FormRequest
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
            'first_name' => ['nullable', 'string', 'min:2', 'max:255'],
            'last_name' => ['nullable', 'string', 'min:4', 'max:255'],
            'phone' => ['nullable', 'string', 'min:10', 'max:255'],
            'fax' => ['nullable', 'string', 'min:10', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'string' => ':attribute must be a string.',
            'required' => ':attribute is required.',
            "max" => [
                "number" => ":attribute no greater than :max.",
                "file" => ":attribute is not more than :max KB.",
                "string" => ":attribute is not more than :max characters.",
                "array" => ":attribute is not more than :max item.",
            ],
            'min' => [
                'numeric' => ':attribute must be at least :min.',
                'file' => ':attribute must be at least :min KB.',
                'string' => ':attribute must be at least :min characters.',
                'array' => ':attribute must have at least :min items.',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'fax' => 'Fax',
        ];
    }
}