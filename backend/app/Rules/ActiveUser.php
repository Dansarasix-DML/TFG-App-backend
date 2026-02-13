<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class ActiveUser implements ValidationRule {
    private $user;

    public function __construct($user) {
        $this->user = $user;
    }
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->user->active) {
            $fail("El usuario no está activado.");
        }
    }
}
