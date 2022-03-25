<?php

namespace App\Actions\Fortify;

use App\Mail\WelcomeMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'nationalId' => ['required', 'string', 'min:8','unique:users'],
            'address' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();


        $user = new User([
            'firstName' => ucwords($input['firstName']),
            'middleName' => ucwords($input['middleName']),
            'lastName' => ucwords($input['lastName']),
            'nationalId' => strtoupper($input['nationalId']),
//            'employer_id' => $input['employerId'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
//            'subscription'         => 0,
            'address'         => ucwords($input['address'])
        ]);

        $user->save();

        $role=Role::where('name','client')->first();
        $user->roles()->attach($role);

        Mail::to($user->email)->send(new WelcomeMail($user));

        return $user;

    }
}
