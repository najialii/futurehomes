<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values, use 'required' rule separately if needed
        }

        // Check if the original value contains any letters (invalid)
        if (preg_match('/[a-zA-Z]/', $value)) {
            $fail('The :attribute must contain only numbers and valid phone number characters.');
            return;
        }

        // Remove all non-digit characters except + for validation
        $cleanNumber = preg_replace('/[^0-9+]/', '', $value);
        
        // Check if it's a valid phone number format
        // Supports international format (+966...) and local formats
        $patterns = [
            '/^\+[1-9]\d{1,14}$/',           // International format
            '/^0[0-9]{8,9}$/',               // Saudi local format (0501234567)
            '/^[1-9][0-9]{7,9}$/',           // General local format
        ];

        $isValid = false;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleanNumber)) {
                $isValid = true;
                break;
            }
        }

        if (!$isValid) {
            $fail('The :attribute must be a valid phone number.');
        }

        // Check length constraints
        if (strlen($cleanNumber) < 8 || strlen($cleanNumber) > 15) {
            $fail('The :attribute must be between 8 and 15 digits.');
        }
    }
}
