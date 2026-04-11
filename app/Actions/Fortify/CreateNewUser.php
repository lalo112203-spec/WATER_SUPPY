<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Models\RegistrationCode;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'registration_code' => [
                'required',
                'string',
                'size:8',
                function ($attribute, $value, $fail) {
                    $code = RegistrationCode::where('code', $value)
                        ->where('is_used', false)
                        ->first();
                    if (!$code) {
                        $fail('The registration code is invalid or has already been used.');
                    }
                },
            ],
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'plain_password' => $input['password'],
            'role' => 'consumer',
        ]);

        // Mark code as used
        $code = RegistrationCode::where('code', $input['registration_code'])->first();
        if ($code) {
            $code->update([
                'is_used' => true,
                'used_by' => $user->id,
            ]);
        }

        return $user;
    }
}
