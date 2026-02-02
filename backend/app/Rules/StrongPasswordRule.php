<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values, use 'required' rule separately if needed
        }

        $password = (string) $value;

        // Check minimum length
        if (strlen($password) < 8) {
            $fail('The :attribute must be at least 8 characters long.');
            return;
        }

        // Check for at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $fail('The :attribute must contain at least one uppercase letter.');
            return;
        }

        // Check for at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $fail('The :attribute must contain at least one lowercase letter.');
            return;
        }

        // Check for at least one number
        if (!preg_match('/[0-9]/', $password)) {
            $fail('The :attribute must contain at least one number.');
            return;
        }

        // Check for at least one special character
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $fail('The :attribute must contain at least one special character.');
            return;
        }

        // Check for common weak passwords
        $weakPasswords = [
            'password', 'password123', '123456789', 'qwerty123',
            'admin123', 'welcome123', 'letmein123', 'password1'
        ];

        if (in_array(strtolower($password), $weakPasswords)) {
            $fail('The :attribute is too common. Please choose a more secure password.');
        }
    }
}
