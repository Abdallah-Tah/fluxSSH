<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\MobileDebugger;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Use mobile-friendly debugging
        MobileDebugger::debug($input, 'CreateNewUser Input');
        MobileDebugger::debug([
            'has_name' => isset($input['name']),
            'has_email' => isset($input['email']),
            'has_password' => isset($input['password']),
            'name_value' => $input['name'] ?? 'NOT_SET',
            'email_value' => $input['email'] ?? 'NOT_SET',
            'input_keys' => array_keys($input),
        ], 'CreateNewUser Input Analysis');

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);
    }
}
