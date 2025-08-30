<?php

namespace App\Http\Requests\OTP;

use Illuminate\Foundation\Http\FormRequest;

class UnauthenticatedVerifyOTPRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'email' => ['required','email'],
            'purpose' => ['required'],
            'otp' => ['required','digits:6'],
        ];
    }
}
