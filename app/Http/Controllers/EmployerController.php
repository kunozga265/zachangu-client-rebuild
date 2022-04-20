<?php

namespace App\Http\Controllers;

use App\Mail\RegisterEmployer;
use App\Models\Employer;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EmployerController extends Controller
{
    public function select()
    {
        $employers=Employer::all();
        return Inertia::render('Employer/Select',[
             'employers'=>$employers,
        ]);
    }

    public function employerSelect(Request $request)
    {
        Validator::make($request->all(), [
            'employerId' => ['required', 'integer']
        ])->validate();

        $user=User::find(Auth::id());
        $employer=Employer::find($request->employerId);
        $errors=[];

        if (is_object($employer)){
            $employee=$employer->employees()->where('nationalId',$user->nationalId)->first();
            if(is_object($employee)){
                $user->update([
                    'employer_id'=>$employer->id
                ]);

                $client=Role::where('name','client')->first();
                $user->roles()->detach($client);

                $verified=Role::where('name','verified')->first();
                $user->roles()->attach($verified);

                //update score if applied for loan already
                $currentLoan=$user->loans()->where('progress','<',4)->latest()->first();
                if(is_object($currentLoan)){
                    $currentLoan->update([
                        'score' => LoanController::getScore($currentLoan)
                    ]);
                }

//                return Inertia::render('Dashboard');
                return Redirect::route('dashboard');

            }else{
                $errors[] = 'You are not registered under this employer';
                return Redirect::back()->with('error','You are not registered under this employer');
            }
        }else{
            $errors[] = 'The employer does not exist';
            return Redirect::back()->with('error','The employer does not exist');

        }

//        return Redirect::back(302)->with('error',$errors);
//        return Inertia::render('Dashboard',[
//            'errors'=>$errors,s
//            'employers'=>Employer::all(),
//        ]);
    }

    public function create()
    {
        return Inertia::render('Employer/Register');
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
//            'letter'                    => ['required'],
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

        return Redirect::route('employer.show',['id'=>$employer->id]);
//        return Inertia::render('')

    }


    public function register(Request $request)
    {
        Validator::make($request->all(), [
            'name'                      => ['required'],
            'physicalAddressName'       => ['required'],
            'physicalAddressBox'        => ['required'],
            'physicalAddressLocation'   => ['required'],
            'proxyName'                 => ['required'],
            'proxyEmail'                => ['required','email'],
            'proxyPhoneNumber'          => ['required'],
//            'letter'                    => ['required'],
            'email'                     => ['required','email'],
        ])->validate();

        $employer=new Employer([
            'name'                      =>ucwords($request->name),
            'physicalAddressName'       =>ucwords($request->physicalAddressName),
            'physicalAddressBox'        =>$request->physicalAddressBox,
            'physicalAddressLocation'   =>ucwords($request->physicalAddressLocation),
            'proxyName'                 =>ucwords($request->proxyName),
            'proxyEmail'                =>$request->proxyEmail,
            'proxyPhoneNumber'          =>$request->proxyPhoneNumber,
            'email'                     =>$request->email,
        ]);

        $user=User::find(Auth::id());
        $fullname=$user->firstName." ".$user->lastName;

        Notification::create([
            'contents'  =>json_encode([
                'employer'  =>  $employer,
            ]),
            'type'      =>"employer_register",
            'user_id'   =>Auth::id(),
        ]);

        try {
//            Mail::to("admin@zachanguloans.com")->send(new RegisterEmployer($employer,$fullname));
        }catch (\Swift_TransportException $exception){
            //do something
        }

        return Redirect::route('dashboard')->with('bannerMessage',"Details for $employer->name have been submitted. Our administrator will contact your employer to enable you to get a loan.");

    }
}
