<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'first_name' => ['required','string','max:100'],
            'last_name'  => ['required','string','max:100'],
            'username'   => ['required','string','alpha_dash','max:50','unique:users,username'],
            'email'      => ['required','email','max:255','unique:users,email'],
            'phone'      => ['required','string','max:30','unique:users,phone'],
            'where_heard'=> ['nullable','string','max:255'],
            'referral_tag'=>['nullable','string','max:255'],

            'password' => ['required', 'string', 'min:8', Password::min(8)->letters()->numbers()->symbols()->uncompromised()],
           
        ];
    }
}


