<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    protected $fillable=[
        'photo',
        'firstName',
        'middleName',
        'lastName',
        'phoneNumberMobile',
        'phone_numberWork',
        'position',
        'email',
        'physicalAddress',
        'workAddress',
        'nationalId',
        'contractDuration',
        'employer_id',
    ];

    protected $hidden=[
        'employer'
    ];
}
