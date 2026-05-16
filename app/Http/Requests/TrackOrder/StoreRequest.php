<?php

namespace App\Http\Requests\TrackOrder;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_number.required' => 'Broj porudžbine je obavezan.',
            'email.required' => 'Email adresa je obavezna za verifikaciju.',
            'email.email' => 'Unesite važeću email adresu.',
        ];
    }
}
