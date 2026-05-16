<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^\+381\s?[0-9]{1,2}\s?[0-9]{6,7}$/', 'max:17', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Broj telefona je obavezan.',
            'phone.regex' => 'Broj telefona mora biti u formatu +381 60 1234567.',
        ];
    }
}
