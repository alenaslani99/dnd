<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'address' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:50'],
            'zip' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
        ];

        if (! auth()->check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'email', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }
}
