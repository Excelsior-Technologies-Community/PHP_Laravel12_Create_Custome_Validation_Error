<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotCommonPassword implements ValidationRule
{
    /**
     * List of commonly-used (weak) passwords that should be rejected.
     *
     * @var array<int, string>
     */
    protected array $commonPasswords = [
        'password', '123456', '12345678', 'qwerty', 'abc123', '123456789',
        '12345', '1234', '111111', '1234567', 'password1', 'iloveyou',
        'admin', 'welcome', 'monkey', '123123', 'letmein', 'football',
        'qwerty123', '123321', 'sunshine', 'princess', 'qwerty1',
        'passw0rd', 'password123', 'admin123', 'welcome1', 'root', 'toor',
    ];

    /**
     * Run the validation rule.
     *
     * Rejects passwords that appear in the common-passwords list.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        if (in_array(strtolower($value), $this->commonPasswords, true)) {
            $fail('The :attribute is too common. Please choose a stronger password.');
        }
    }
}
