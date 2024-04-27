<?php

namespace App\Http\Requests\Admins\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EditAdminRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'numeric', 'min:0', 'max:127'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:12'],
            'email' => ['nullable', 'string', 'max:255', 'unique:admins'],
            'avatar' => ['nullable', 'file', 'max:21000'],
            'role' => ['nullable', 'string'],
            'status' => ['nullable', 'numeric', 'min:0', 'max:127'],
        ];
    }

    public function messages()
    {
        return [
            "string" => ":attribute phải là một chuỗi kí tự.",
            "required" => ":attribute không được bỏ trống.",
            "file" => ":attribute phải là một tệp tin.  ",
            "numeric" => ":attribute phải là một số.",
            'unique' => ":attribute đã được đăng ký, xin vui lòng nhập :attribute khác",
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
            'name' => "Tên người dùng",
            'gender' => 'Giới tính',
            'address' => 'Địa chỉ',
            'phone' => 'Số điện thoại',
            'email' => 'Địa chỉ Email',
            'avatar' => "Ảnh đại diện",
            'role' => "Quyền",
            'password' => "Mật khẩu",
            'password_confirmation' => "Xác nhận mật khẩu",
            'status' => "Trạng thái",
        ];
    }

}