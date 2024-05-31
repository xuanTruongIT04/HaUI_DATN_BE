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
            // 'phone' => ['nullable', 'string', 'min:10', 'max:255'], // Old validate data
            'phone' => ['nullable', 'string', 'regex:/^(032|033|034|035|036|037|038|039|
                                                        096|097|098|086|083|084|085|081|
                                                        082|088|091|094|070|079|077|076|
                                                        078|090|093|089|056|058|092|059|
                                                        099)[0-9]{7}$/'],  // Update new validate data when testing
            'fax' => ['nullable', 'string', 'min:10', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'string' => ':attribute phải là một chuỗi.',
            'required' => ':attribute là bắt buộc.',
            "max" => [
                "number" => ":attribute không lớn hơn :max.",
                "file" => ":attribute không lớn hơn :max KB.",
                "string" => ":attribute không lớn hơn :max ký tự.",
                "array" => ":attribute không lớn hơn :max mục.",
            ],
            'min' => [
                'numeric' => ':attribute phải ít nhất :min.',
                'file' => ':attribute phải ít nhất :min KB.',
                'string' => ':attribute phải ít nhất :min ký tự.',
                'array' => ':attribute phải có ít nhất :min mục.',
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
