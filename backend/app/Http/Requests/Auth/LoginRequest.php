<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\ValidPassword;  // Asegúrate de tener esta regla definida
use App\Rules\ActiveUser;     // Asegúrate de tener esta regla definida
use App\Models\User;
use Illuminate\Support\Facades\Log;


class LoginRequest extends FormRequest
{
    /**
     * Determina si la solicitud está autorizada.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación básicas.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|max:255',
        ];
    }

    /**
     * Personaliza el manejo de validación fallida.
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
                'status' => true
            ], 422)
        );
    }

    /**
     * Agrega validaciones adicionales después de las reglas básicas.
     */
    public function withValidator(Validator $validator) {
        $validator->after(function ($validator) {
            if ($validator->errors()->isEmpty()) {
                // Obtenemos el usuario con el email proporcionado
                $user = User::where('email', $this->input('email'))->first();

                if ($user) {
                    $fail = function ($message) use ($validator) {
                        $validator->errors()->add('password', $message);
                    };

                    // Validación de la contraseña usando ValidPassword
                    $validPassword = new ValidPassword($user->password);
                    $passwordIsValidated = $validPassword->validate('password', $this->input('password'), $fail);

                    // Validación de usuario activo usando ActiveUser
                    if($validator->errors()->isEmpty()) {
                        $activeUser = new ActiveUser($user);
                        $activeUser->validate('user', null, function ($message) use ($validator) {
                            $validator->errors()->add('user', $message);
                        });
                    }
                }
            }
        });
    }
}
