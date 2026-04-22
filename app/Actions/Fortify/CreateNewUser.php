<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Models\Customer;
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
            'account_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'registration_code' => ['nullable', 'string', 'size:8'],
        ])->after(function ($validator) use ($input) {
            $customerByNumber = Customer::where('customer_id', $input['account_number'])->first();

            if (!$customerByNumber) {
                $validator->errors()->add('account_number', 'This account number could not be found in our system.');
            } else {
                $customerMatch = Customer::where('customer_id', $input['account_number'])
                    ->whereRaw('LOWER(name) = ?', [strtolower($input['name'])])
                    ->whereRaw('LOWER(address) LIKE ?', ['%' . strtolower($input['address']) . '%'])
                    ->first();

                if (!$customerMatch) {
                    $validator->errors()->add('name', 'The name or address provided does not match our records for this account number.');
                    $validator->errors()->add('address', 'The name or address provided does not match our records for this account number.');
                } else {
                    // Check if user already exists for this customer
                    $existingUser = User::where('customer_id', $customerMatch->id)->first();
                    if ($existingUser) {
                        // If account exists, require a registration code to proceed
                        if (empty($input['registration_code'])) {
                            $validator->errors()->add('account_number', 'An account has already been created for this customer. Please provide a registration code to override and create a new one.');
                        } else {
                            // Validate the provided code
                            $code = \App\Models\RegistrationCode::where('code', $input['registration_code'])
                                ->where('is_used', false)
                                ->first();

                            if (!$code) {
                                $validator->errors()->add('registration_code', 'The registration code is invalid or has already been used.');
                            }
                        }
                    }
                }
            }
        })->validate();

        $customer = Customer::where('customer_id', $input['account_number'])->first();

        // If an override is happening, delete the old user first
        if (!empty($input['registration_code'])) {
            $existingUser = User::where('customer_id', $customer->id)->first();
            if ($existingUser) {
                $existingUser->delete();
            }
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'plain_password' => $input['password'],
            'role' => 'consumer',
            'customer_id' => $customer->id,
        ]);

        // Mark code as used if provided
        if (!empty($input['registration_code'])) {
            $code = \App\Models\RegistrationCode::where('code', $input['registration_code'])->first();
            if ($code) {
                $code->update([
                    'is_used' => true,
                    'used_by' => $user->id,
                ]);
            }
        }

        return $user;
    }
}
