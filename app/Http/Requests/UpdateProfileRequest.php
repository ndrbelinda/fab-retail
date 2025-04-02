<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Perbaikan: Gunakan auth() helper dengan guard 'api'
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Perbaikan: Gunakan auth('api')->id()
        $userId = auth('api')->id();
        
        return [
            'username' => [
                'sometimes',
                'string',
                'max:50',
                'unique:users,username,' . $userId
            ],
            'password' => [
                'sometimes',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'username.unique' => 'Username sudah digunakan',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ];
    }
}