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
            "string" => ":attribute must be a character string.",
            "required" => ":attribute is not blank.",
            "doesntExist" => ":attribute already exists, please choose another :attribute!",
            "unique" => ":attribute already exists, please enter another email.",
            'email' => "Email address must be an email address in a valid format!",
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
            'username' => "Username",
            'password' => "Password",
            'email' => "Email address",
        ];
    }

}
