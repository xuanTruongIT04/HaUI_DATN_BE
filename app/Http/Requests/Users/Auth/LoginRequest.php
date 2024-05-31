<?php

namespace App\Http\Requests\Users\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => ['required', 'exists:users', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            "string" => ":attribute phải là chuỗi ký tự.",
            "required" => ":attribute không được để trống.",
            "exists" => "Tên người dùng không tồn tại, vui lòng đăng ký tài khoản!",
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
            'username' => "Username",
            'password' => "Password",
        ];
    }
}
