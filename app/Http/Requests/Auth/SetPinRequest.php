<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SetPinRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email'   => ['nullable','email','exists:users,email'],
            'txn_pin' => ['required','digits:4'],
        ];
    }

    public function prepareForValidation()
    {
        if (!$this->user_id && !$this->email) {
            $this->merge(['email' => $this->input('email')]); // fallback
        }
    }
}

