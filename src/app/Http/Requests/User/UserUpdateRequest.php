<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => [
                'required',
                'string',
                'max:50',
            ],
            'lastname' => [
                'required',
                'string',
                'max:50',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->id, 'id')->whereNull('deleted_at')
            ],
            'username' => [
                'required',
                'string',
                Rule::unique('users')->ignore($this->id, 'id')->whereNull('deleted_at'),
                'min:6'
            ],
            'password' => [
                'sometimes',
                'required',
                'string',
                'min:6'
            ],
            'phone_number' => [
                'required',
                'numeric',
                'regex:/^(9)\d{9}$/',
            ],
            'address' => [
                'required',
                'string',
            ],
            'postcode' => [
                'required',
                'numeric',
                'min_digits:6',
                'max_digits:6',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'phone_number' => 'Phone Number',
            'postcode' => 'Post Code'
        ];
    }

    public function messages()
    {
        $required = ':attribute is required!';
        
        return [
            'firstname.required' => $required,
            'lastname.required' => $required,
            'username.required' => $required,
            'email.required' => $required,
            'password.required' => $required,
            'phone_number.required' => $required,
            'postcode.required' => $required
        ];
    }


    protected function prepareForValidation(): void
    {

        $this->merge([
            'updated_at' => now()
        ]);

    }
}
