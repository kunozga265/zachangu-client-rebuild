<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Http\Resources\EmployersResource;

class EmployerController extends Controller
{
    public function index()
    {
        $employers=Employer::all();

        return Inertia::render('Admin/Employers/Index',[
            'employers'=>EmployersResource::collection($employers)
        ]);
    }

    public function show($id)
    {
        $employer=Employer::find($id);
        if (is_object($employer)){

            $employees=$employer->employees;
           /* foreach($employees as $employee){
                $employee->contractDuration=date('jS F, Y',$employee->contractDuration);
                $employee->physicalAddress=json_decode($employee->physicalAddress);
                $employee->workAddress=json_decode($employee->workAddress);
            }*/


            return Inertia::render('Admin/Employers/Show',[
               'employer'   => $employer,
               'employees'  => EmployeeResource::collection($employees)
            ]);

        }else
            return Redirect::route('dashboard.admin')->with('error','Invalid Employer');
    }

    public function create()
    {
        return Inertia::render('Admin/Employers/New');
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name'                      => ['required'],
            'physicalAddressName'       => ['required'],
            'physicalAddressBox'        => ['required'],
            'physicalAddressLocation'   => ['required'],
            'proxyName'                 => ['required'],
            'proxyEmail'                => ['required','email'],
            'proxyPhoneNumber'          => ['required'],
            'letter'                    => ['required'],
            'email'                     => ['required','email'],
        ])->validate();

        $employer=Employer::create([
            'name'                      =>ucwords($request->name),
            'physicalAddressName'       =>ucwords($request->physicalAddressName),
            'physicalAddressBox'        =>$request->physicalAddressBox,
            'physicalAddressLocation'   =>ucwords($request->physicalAddressLocation),
            'proxyName'                 =>ucwords($request->proxyName),
            'proxyEmail'                =>$request->proxyEmail,
            'proxyPhoneNumber'          =>$request->proxyPhoneNumber,
            'letter'                    =>$request->letter,
            'email'                     =>$request->email,
        ]);
        $employer->save();

        return Redirect::route('employer.admin.show',['id'=>$employer->id]);
//        return Inertia::render('')

    }

    public function edit($id)
    {
        $employer=Employer::find($id);

        if(is_object($employer)){
            return Inertia::render('Admin/Employers/Edit',[
                'employer'=>$employer
            ]);

        }else
            return Redirect::back()->with('error','Invalid employer');

    }

    public function update(Request $request,$id)
    {
        Validator::make($request->all(), [
            'name'                      => ['required'],
            'physicalAddressName'       => ['required'],
            'physicalAddressBox'        => ['required'],
            'physicalAddressLocation'   => ['required'],
            'proxyName'                 => ['required'],
            'proxyEmail'                => ['required','email'],
            'proxyPhoneNumber'          => ['required'],
            'letter'                    => ['required'],
            'email'                     => ['required','email'],
        ])->validate();

        $employer=Employer::find($id);

        if(is_object($employer)){
            $employer->update([
                'name'                      =>ucwords($request->name),
                'physicalAddressName'       =>ucwords($request->physicalAddressName),
                'physicalAddressBox'        =>$request->physicalAddressBox,
                'physicalAddressLocation'   =>ucwords($request->physicalAddressLocation),
                'proxyName'                 =>ucwords($request->proxyName),
                'proxyEmail'                =>$request->proxyEmail,
                'proxyPhoneNumber'          =>$request->proxyPhoneNumber,
                'letter'                    =>$request->letter,
                'email'                     =>$request->email,
            ]);

            return Redirect::route('employer.admin.show',['id'=>$employer->id]);

        }else
            return Redirect::back()->with('error','Invalid employer');

    }
}
