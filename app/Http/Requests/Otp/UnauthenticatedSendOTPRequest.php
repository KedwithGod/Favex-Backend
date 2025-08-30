<?php

namespace App\Http\Requests\OTP;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnauthenticatedSendOTPRequest extends FormRequest

{
    
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'email' => ['required','email',Rule::unique('users')],
            'purpose' => ['required'],
           
        ];
    }
}

