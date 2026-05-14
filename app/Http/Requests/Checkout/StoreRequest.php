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
            'house_number' => ['required', 'string', 'regex:/^(\d{1,5}|bb)$/i', 'max:5'],
            'zip' => ['required', 'string', 'regex:/^\d{5}$/'],
            'city' => ['required', 'string', 'max:100'],
        ];

        if (! auth()->check()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'email', 'max:255'];
            $rules['phone'] = ['required', 'string', 'regex:/^\+381\s?[0-9]{1,2}\s?[0-9]{6,7}$/', 'max:17'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ime i prezime su obavezni.',
            'email.required' => 'Email adresa je obavezna.',
            'email.email' => 'Unesite važeću email adresu.',
            'phone.required' => 'Broj telefona je obavezan.',
            'phone.regex' => 'Broj telefona mora biti u formatu +381 60 1234567.',
            'address.required' => 'Adresa je obavezna.',
            'house_number.required' => 'Kućni broj je obavezan.',
            'house_number.regex' => 'Kućni broj može biti do 5 cifara ili "bb".',
            'zip.required' => 'Poštanski broj je obavezan.',
            'zip.regex' => 'Poštanski broj mora imati tačno 5 cifara.',
            'city.required' => 'Grad je obavezan.',
        ];
    }
}
