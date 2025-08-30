<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UniquePhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:20'], 
            // optionally: regex:/^\+?[0-9]{7,15}$/ for international format
        ];
    }
}
