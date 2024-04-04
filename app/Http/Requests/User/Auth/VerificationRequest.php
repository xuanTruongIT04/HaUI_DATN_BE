<?php

namespace App\Http\Requests\Users\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerificationRequest extends FormRequest
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
            "string" => ":attribute must be a character string.",
            "required" => ":attribute is not blank.",
            "exists" => "The :attribute does not exist, please register an :attribute!",
            "max" => [
                "number" => ":attribute no greater than :max.",
                "file" => ":attribute is not more than :max KB.",
                "string" => ":attribute is not more than :max characters.",
                "array" => ":attribute is not more than :max item.",
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