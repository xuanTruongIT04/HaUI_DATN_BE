<?php

namespace App\Http\Requests\Users\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username' => ['required', 'unique:users', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            "string" => ":attribute phải là chuỗi ký tự.",
            "required" => ":attribute không được để trống.",
            "doesntExist" => ":attribute đã tồn tại, vui lòng chọn :attribute khác!",
            "unique" => ":attribute đã tồn tại, vui lòng nhập :attribute khác.",
            'email' => "Địa chỉ email phải là địa chỉ email hợp lệ!",
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
            'email' => "Email address",
        ];
    }
}
