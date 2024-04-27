<?php

namespace App\Http\Requests\Admins\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\StartsWith;

class EditCouponRequest extends FormRequest
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
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($this->id),
                'starts_with:#SBC_CP'
            ],
            'percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'start_date' => ['required', 'date_format:Y-m-d\TH:i'],
            'end_date' => [
                'required',
                'date_format:Y-m-d\TH:i',
                'after:start_date',
            ],
            'status' => ['required', 'numeric', 'min:0', 'max:127'],
        ];
    }

    public function messages()
    {
        return [
            "string" => ":attribute phải là một chuỗi kí tự.",
            "required" => ":attribute không được bỏ trống.",
            "unique" => ":attribute đã tồn tại trong cơ sở dữ liệu.",
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
            "date_format" => ":attribute phải có định dạng ngày và giờ là Y-m-d\TH:i",
            "after" => ":attribute phải sau :date.",
            "starts_with" => "Phiếu giảm giá phải bắt đầu bằng #SBC_CP"
        ];
    }

    public function attributes()
    {
        return [
            'name' => "Tên phiếu giảm giá",
            'code' => "Mã phiếu giảm giá",
            'percent' => "Phần trăm giảm giá",
            'start_date' => "Ngày bắt đầu phiếu giảm giá",
            'end_date' => "Ngày kết thúc phiếu giảm giá",
            'status' => "Trạng thái phiếu giảm giá",
        ];
    }
}
