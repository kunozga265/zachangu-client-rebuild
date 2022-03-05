<?php

namespace App\Actions\Fortify;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'nationalId' => ['required', 'string', 'max:8'],
//            'employerId' => ['required', 'integer'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();


        $user = new User([
            'firstName' => $input['firstName'],
            'middleName' => $input['middleName'],
            'lastName' => $input['lastName'],
            'nationalId' => strtoupper($input['nationalId']),
//            'employer_id' => $input['employerId'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'subscription'         => 0,
            'address'         => $input['address']
        ]);

        $user->save();

        switch($input['role']){
            case 'borrower':
                $role=Role::where('name','borrower')->first();
                $user->roles()->attach($role);
                break;
            case 'guarantor':
                $role=Role::where('name','guarantor')->first();
                $user->roles()->attach($role);
                break;
            default:
                break;
        }

        return $user;

    }
}
