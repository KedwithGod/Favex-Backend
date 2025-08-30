<?php

namespace App\Http\Requests\Otp;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'email' => ['required','email','exists:users,email'],
            'purpose' => ['required'],
            'otp' => ['required','digits:6'],
        ];
    }
}
