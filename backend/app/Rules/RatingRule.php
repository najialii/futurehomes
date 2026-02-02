<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RatingRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        // Handle empty values - they should fail
        if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
            return false;
        }

        // Check if value is numeric
        if (!is_numeric($value)) {
            return false;
        }

        $rating = (float) $value;

        // Check if rating is an integer (no decimals allowed)
        if ($rating != (int) $rating) {
            return false;
        }

        $rating = (int) $rating;

        // Check if rating is between 1 and 5
        return $rating >= 1 && $rating <= 5;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute must be a valid rating between 1 and 5 stars.';
    }
}
