<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name'=>'client'
        ]);

        Role::create([
            'name'=>'verified'
        ]);

        Role::create([
            'name'=>'admin'
        ]);
    }
}
