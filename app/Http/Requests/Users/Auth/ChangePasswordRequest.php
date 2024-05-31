<?php

namespace App\Http\Requests\Users\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'different:password'],
            'new_password_confirmation' => ['required', 'string', 'min:8', 'same:new_password'],
        ];
    }

    public function messages()
    {
        return [
            "string" => ":attribute phải là chuỗi ký tự.",
            "required" => ":attribute không được để trống.",
            "exists" => "Tài khoản không tồn tại, vui lòng đăng ký tài khoản!",
            'confirmed' => 'Xác nhận :attribute không khớp.',
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
            'different' => ':attribute phải khác với mật khẩu hiện tại.',
            'same' => ':attribute và :other phải khớp nhau.',
        ];
    }

    public function attributes()
    {
        return [
            'password' => "Mật khẩu",
            'new_password' => "Mật khẩu mới",
            'new_password_confirmation' => "Xác nhận mật khẩu mới",
        ];
    }
}
