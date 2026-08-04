<?php

namespace App\Http\Requests;

use App\Rules\NotCommonPassword;
use App\Rules\PasswordStrength;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare / sanitize the input before validation runs.
     *
     * Trims leading/trailing whitespace and strips HTML tags from
     * user-supplied text fields so the stored data is clean.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitize($this->input('name')),
            'email' => $this->sanitize($this->input('email')),
        ]);
    }

    /**
     * Sanitize a single value:
     *  - trim whitespace
     *  - strip HTML tags
     *  - decode HTML entities back to plain characters
     */
    protected function sanitize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim(strip_tags(html_entity_decode($value, ENT_QUOTES, 'UTF-8')));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'min:5',
                'confirmed',
                new PasswordStrength,
                new NotCommonPassword,
            ],
        ];
    }

    /**
     * Get custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name field is required.',
            'name.string' => 'Name must contain only text characters.',
            'name.max' => 'Name may not be greater than 255 characters.',

            'email.required' => 'Email field is required.',
            'email.email' => 'Email field must be a valid email address.',
            'email.unique' => 'This email is already registered. Please try another.',

            'password.required' => 'Password field is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
