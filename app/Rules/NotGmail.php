<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotGmail implements ValidationRule {
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!is_string($value) || !str_contains($value, "@")) {
            $fail("The :attribute is not a valid email address.");
            return;
        }

        if (explode('@', $value)[1] !== 'gmail.com') {
            $fail('The :attribute must be a Gmail address.');
        }
    }
}
