<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class ValidPassword implements ValidationRule
{
    private $cryptPassword;

    public function __construct($cryptPassword)
    {
        $this->cryptPassword = $cryptPassword;
    }
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Hash::check($value, $this->cryptPassword)) {
            $fail("La contraseña no es válida.");
        }
    }
}
