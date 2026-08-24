<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\NotCommonPassword;
use App\Rules\PasswordStrength;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare and sanitize input before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitize($this->input('name')),
            'email' => $this->sanitize($this->input('email')),
        ]);
    }

    /**
     * Sanitize a text value.
     */
    protected function sanitize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim(
            strip_tags(
                html_entity_decode($value, ENT_QUOTES, 'UTF-8')
            )
        );
    }

    /**
     * Get validation rules.
     */
    public function rules(): array
    {
        $user = $this->route('user');

        if (! $user instanceof User) {
            $user = User::find($user);
        }

        return $this->rulesForUser($user);
    }

    /**
     * Rules used by both normal update validation
     * and AJAX validation.
     */
    public function rulesForUser(?User $user): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user?->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                new PasswordStrength,
                new NotCommonPassword,
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name field is required.',
            'name.string' => 'Name must contain only text characters.',
            'name.max' => 'Name may not be greater than 255 characters.',

            'email.required' => 'Email field is required.',
            'email.email' => 'Email field must be a valid email address.',
            'email.max' => 'Email may not be greater than 255 characters.',
            'email.unique' => 'This email is already registered by another user.',

            'password.string' => 'Password must be a valid text value.',
            'password.min' => 'New password must be at least 8 characters long.',
            'password.confirmed' => 'New password confirmation does not match.',
        ];
    }
}