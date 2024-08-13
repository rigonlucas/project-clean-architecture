<?php

namespace App\Http\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email:rfc,dns'],
            'password' => ['required', 'string', 'min:8'],
            'birthday' => ['required', 'date', 'before:today'],
            'account_uuid' => ['nullable', 'uuid'],
            'account_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
