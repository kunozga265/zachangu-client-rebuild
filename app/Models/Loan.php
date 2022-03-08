<?php

namespace App\Models;

use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable=[
        'code',
        'photo',
        'firstName',
        'middleName',
        'lastName',
        'phoneNumberMobile',
        'phoneNumberWork',
        'position',
        'email',
        'physicalAddress',
        'workAddress',
        'nationalId',
        'contractDuration',
        'payDay',
        'paySlip',
        'gross',
        'net',

        'nationalIdFile',
//        'passport_id',
//        'licence_id',
//        'other_id',
        'contract',
//        'bank_statement',
//        'bank_name',
//        'bank_service_center',
//        'bank_account_name',
//        'bank_account_number',
//        'reference_letter',
//        'co_workers',
        'consent',
        'progress',
        'score',
        'subscription',
        'termsAndConditions',
        'termsAndConditionsGuarantor',
        'amount',
        'approvedDate',
        'dueDate',
        'appliedDate',
        'guarantor_id',
        'guarantorDate',
        'closedDate',
        'user_id',

    ];

   /* protected $hidden=[
        'photo',
        'firstName',
        'middleName',
        'lastName',
        'phoneNumberMobile',
        'phoneNumberWork',
        'position',
        'email',
        'physicalAddress',
        'workAddress',
        'nationalId',
        'contractDuration',
        'payDay',
        'paySlip',
        'gross',
        'net',
        'nationalIdFile',
//        'passport_id',
//        'licence_id',
//        'other_id',
        'contractFile',
//        'bank_statement',
//        'bank_name',
//        'bank_service_center',
//        'bank_account_name',
//        'bank_account_number',
//        'reference_letter',
//        'co_workers',
        'consent',

        'score',
        'subscription',
        'termsAndConditions',

        'user_id',
        'created_at',
        'updated_at',

    ];*/
}
