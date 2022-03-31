<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

    protected $fillable=[
        'name',
        'email',
        'physicalAddressName',
        'physicalAddressBox',
        'physicalAddressLocation',
        'proxyName',
        'proxyEmail',
        'proxyPhoneNumber',
        'letter',
    ];

    protected $hidden=[
        'employees'
    ];
}
