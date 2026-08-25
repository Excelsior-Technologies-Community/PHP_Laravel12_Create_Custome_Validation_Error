<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotDisposableEmail implements ValidationRule
{
    protected array $blockedDomains = [
        'mailinator.com',
        '10minutemail.com',
        'tempmail.com',
        'guerrillamail.com',
        'yopmail.com',
        'temp-mail.org',
        'throwawaymail.com',
        'fakeinbox.com',
        'getnada.com',
        'maildrop.cc',
    ];

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if (! is_string($value)) {
            return;
        }

        $email = strtolower(trim($value));

        if (! str_contains($email, '@')) {
            return;
        }

        $domain = substr(
            strrchr($email, '@'),
            1
        );

        if (in_array($domain, $this->blockedDomains, true)) {
            $fail(
                'Temporary or disposable email addresses are not allowed.'
            );
        }
    }
}
