<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'email'    => ['required','email'],
           'password' => ['required', 'string', 'min:8', Password::min(8)->letters()->numbers()->symbols()->uncompromised()],
        ];
    }
}
