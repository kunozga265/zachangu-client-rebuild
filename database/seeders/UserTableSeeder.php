<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role=Role::where('name','admin')->first();
        $user=new User([
            'firstName' => "Admin",
            'lastName' => "User",
            'email' => "admin@zachanguloans.com",
            'password' => Hash::make("12345678"),
        ]);
        $user->save();
        $user->roles()->attach($role);
    }
}
