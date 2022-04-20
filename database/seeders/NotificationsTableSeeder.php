<?php

namespace Database\Seeders;

use App\Models\Employer;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::create([
            'contents'  =>json_encode([

            ]),
            'type'      =>"user_new",
            'user_id'   =>2,
        ]);

        Notification::create([
            'contents'  =>json_encode([

            ]),
            'type'      =>"user_new",
            'user_id'   =>3,
        ]);

        Notification::create([
            'contents'  =>json_encode([
                'amount'    =>  70000,
                'loanCode'  =>  1649269464,
            ]),
            'type'      =>"loan_new",
            'user_id'   =>2,
        ]);

        Notification::create([
            'contents'  =>json_encode([
                'amount'    =>  60000,
                'loanCode'  =>  1649269464,
            ]),
            'type'      =>"loan_new",
            'user_id'   =>2,
        ]);

        Notification::create([
            'contents'  =>json_encode([
                'amount'    =>  45000,
                'loanCode'  =>  1649269464,
            ]),
            'type'      =>"loan_new",
            'user_id'   =>3,
        ]);

        Notification::create([
            'contents'  =>json_encode([
                'amount'    =>  90000,
                'loanCode'  =>  1649269464,
            ]),
            'type'      =>"loan_apply",
            'user_id'   =>2,
        ]);

        Notification::create([
            'contents'  =>json_encode([
                'amount'    =>  80000,
                'loanCode'  =>  1649269464,
            ]),
            'type'      =>"loan_apply",
            'user_id'   =>2,
        ]);

        $employer=new Employer([
            'name'                      =>ucwords("John Fathers"),
            'physicalAddressName'       =>ucwords("Homer"),
            'physicalAddressBox'        =>9,
            'physicalAddressLocation'   =>ucwords("Lilongwe"),
            'proxyName'                 =>ucwords("Hommer Grant"),
            'proxyEmail'                =>"c@gmail.com",
            'proxyPhoneNumber'          =>"997748584",
            'email'                     =>"johnF@gmail.com",
        ]);

        Notification::create([
            'contents'  =>json_encode([
                'employer'  =>  $employer,
            ]),
            'type'      =>"employer_register",
            'user_id'   =>3,
        ]);

        $employer=new Employer([
            'name'                      =>ucwords("National Bank of Malawi"),
            'physicalAddressName'       =>ucwords("Homer"),
            'physicalAddressBox'        =>9,
            'physicalAddressLocation'   =>ucwords("Lilongwe"),
            'proxyName'                 =>ucwords("Hommer Grant"),
            'proxyEmail'                =>"c@gmail.com",
            'proxyPhoneNumber'          =>"997748584",
            'email'                     =>"johnF@gmail.com",
        ]);

        Notification::create([
            'contents'  =>json_encode([
                'employer'  =>  $employer,
            ]),
            'type'      =>"employer_register",
            'user_id'   =>2,
        ]);


    }
}
