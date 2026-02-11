<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RatingRule implements Rule
{
    
    public function passes($attribute, $value): bool
    {

        if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
            return false;
        }

        if (!is_numeric($value)) {
            return false;
        }

        $rating = (float) $value;

        if ($rating != (int) $rating) {
            return false;
        }

        $rating = (int) $rating;

        return $rating >= 1 && $rating <= 5;
    }

    
    public function message(): string
    {
        return 'The :attribute must be a valid rating between 1 and 5 stars.';
    }
}
