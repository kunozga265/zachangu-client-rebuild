<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Employer;
use Illuminate\Database\Seeder;

class EmployerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employer::create([
            'name'   =>  'Zachangu Microfinance Agency',
            'address'   =>  'Chayamba Building P. O. Box 234 Blantyre 3',
           'email'      =>  "e@g.com",
        ]);
        Employer::create([
            'name'       =>  'Escom Limited',
            'address'    =>  'Chipembere Highway P. O. Box 234 Blantyre 3',
            'email'      =>  "e@g.com",
        ]);

        Employee::create([
            'firstName'         => 'Kunozga',
            'lastName'          => 'Mlowoka',
            'position'          => 'CEO',
            'email'             => 'kunozgamlowoka@gmail.com',
            'workAddress'      => json_encode([
                                    "box"=> "18",
                                    "name"=> "House",
                                    "location"=> "Dedza"
                                ]),
            'physicalAddress'   => json_encode([
                                    "box"=> "19",
                                    "name"=> "House",
                                    "location"=> "Dedza"
                                ]),
            'nationalId'        => 'KKKKKKKK',
            'contractDuration'  => 1658966400,
            'employer_id'       => 1,
            ]);
    }
}
