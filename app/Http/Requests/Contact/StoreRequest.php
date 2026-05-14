<?php

namespace App\Http\Requests\Contact;

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ime i prezime su obavezni.',
            'email.required' => 'Email adresa je obavezna.',
            'email.email' => 'Unesite važeću email adresu.',
            'message.required' => 'Poruka je obavezna.',
            'message.min' => 'Poruka mora imati najmanje 10 karaktera.',
            'message.max' => 'Poruka može imati najviše 2000 karaktera.',
        ];
    }
}
