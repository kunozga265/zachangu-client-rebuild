<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function create($id)
    {
        $employer=Employer::find($id);
        if (is_object($employer)){

            return Inertia::render('Admin/Employee/New',[
                'employer'=>$employer
            ]);

        }else
            return Redirect::route('dashboard.admin')->with('error','Invalid Employer');


    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'photo'                => ['required'],
            'firstName'            => ['required'],
            'lastName'             => ['required'],
            'phoneNumberMobile'    => ['required'],
            'phoneNumberWork'      => ['required'],
            'position'             => ['required'],
            'email'                => ['required'],
            'physicalAddress'      => ['required'],
            'workAddress'          => ['required'],
            'nationalId'           => ['required','min:8'],
            'bankName'     => ['required'],
            'bankAccountName'     => ['required'],
            'bankAccountNumber'     => ['required'],
            'contractDuration'     => ['required'],
            'employer_id'          => ['required'],
        ])->validate();

        $employee = new Employee([
            'photo'                => $request->photo,
            'firstName'            => ucwords($request->firstName),
            'middleName'           => ucwords($request->middleName),
            'lastName'             => ucwords($request->lastName),
            'phoneNumberMobile'    => $request->phoneNumberMobile,
            'phoneNumberWork'      => $request->phoneNumberWork,
            'position'             => ucwords($request->position),
            'email'                => $request->email,
            'physicalAddress'      => json_encode($request->physicalAddress),
            'workAddress'          => json_encode($request->workAddress),
            'nationalId'           => strtoupper($request->nationalId),
            'contractDuration'     => $request->contractDuration,
            'bankName'             => ucwords($request->bankName),
            'bankAccountName'      => ucwords($request->bankAccountName),
            'bankAccountNumber'    => $request->bankAccountNumber,
            'employer_id'          => $request->employer_id,
        ]);

        $employee->save();

        return Redirect::route('employer.admin.show',['id'=>$employee->employer_id]);
    }

    public function edit($id)
    {

        $employee=Employee::find($id);

        if (is_object($employee)){
            $time=Carbon::createFromTimestamp($employee->contractDuration);

            return Inertia::render('Admin/Employee/Edit',[
                'employee'=>$employee,
                'physicalAddress'=>json_decode($employee->physicalAddress),
                'workAddress'=>json_decode($employee->workAddress),
                'contractDurationISO' => $time->toDateTimeString(),
            ]);

        }else
            return Redirect::back()->with('error','Invalid employee id');

    }

    public function update(Request $request,$id)
    {
        Validator::make($request->all(), [
            'photo'                => ['required'],
            'firstName'            => ['required'],
            'lastName'             => ['required'],
            'phoneNumberMobile'    => ['required'],
            'phoneNumberWork'      => ['required'],
            'position'             => ['required'],
            'email'                => ['required'],
            'physicalAddress'      => ['required'],
            'workAddress'          => ['required'],
            'nationalId'           => ['required','min:8'],
            'bankName'             => ['required'],
            'bankAccountName'      => ['required'],
            'bankAccountNumber'    => ['required'],
            'contractDuration'     => ['required'],
            'employer_id'          => ['required'],
        ])->validate();

        $employee=Employee::find($id);

        if(is_object($employee)){
            $employee->update([
                'photo'                => $request->photo,
                'firstName'            => ucwords($request->firstName),
                'middleName'           => ucwords($request->middleName),
                'lastName'             => ucwords($request->lastName),
                'phoneNumberMobile'    => $request->phoneNumberMobile,
                'phoneNumberWork'      => $request->phoneNumberWork,
                'position'             => ucwords($request->position),
                'email'                => $request->email,
                'physicalAddress'      => json_encode($request->physicalAddress),
                'workAddress'          => json_encode($request->workAddress),
                'nationalId'           => strtoupper($request->nationalId),
                'contractDuration'     => $request->contractDuration,
                'bankName'             => ucwords($request->bankName),
                'bankAccountName'      => ucwords($request->bankAccountName),
                'bankAccountNumber'    => $request->bankAccountNumber,
                'employer_id'          => $request->employer_id,
            ]);

            return Redirect::route('employer.admin.show',['id'=>$employee->employer_id]);

        }else
            return Redirect::back()->with('error','Invalid Employee');


    }
}
