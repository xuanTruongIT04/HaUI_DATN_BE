<?php

namespace App\Http\Requests\Users\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
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
            'email' => ['required', 'exists:users', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            "string" => ":attribute phải là chuỗi ký tự.",
            "required" => ":attribute không được để trống.",
            "exists" => ":attribute không tồn tại, vui lòng đăng ký :attribute!",
            "max" => [
                "number" => ":attribute không được lớn hơn :max.",
                "file" => ":attribute không được lớn hơn :max KB.",
                "string" => ":attribute không được lớn hơn :max ký tự.",
                "array" => ":attribute không được lớn hơn :max phần tử.",
            ],
        ];
    }

    public function attributes()
    {
        return [
            'email' => "Email",
        ];
    }
}
