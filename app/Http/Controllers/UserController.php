<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class UserController extends Controller
{
    public function redirect()
    {
        $user=User::find(Auth::id());

        if($user->hasRole('borrower')){
            return Redirect::route('dashboard');
        }else
            return Inertia::render('Guarantor/Dashboard');

    }

    public function index()
    {
        $employers=Employer::all();
        $user=User::find(Auth::id());
        $loan=null;
        if ($user->employer_id != null){
            $loan=$user->loans()->where('progress','<',4)->orderBy('appliedDate','DESC')->first();
        }
        return Inertia::render('Dashboard',[
            'employers'=>$employers,
            'loan'=>$loan
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

                $role=Role::where('name','verified')->first();
                $user->roles()->attach($role);

//                return Inertia::render('Dashboard');
                return Redirect::route('dashboard');

            }else{
                array_push($errors,'You are not registered under this employer');
                return Redirect::back()->with('error','You are not registered under this employer');
            }
        }else{
            array_push($errors,'The employer does not exist');
            return Redirect::back()->with('error','The employer does not exist');

        }

//        return Redirect::back(302)->with('error',$errors);
//        return Inertia::render('Dashboard',[
//            'errors'=>$errors,s
//            'employers'=>Employer::all(),
//        ]);
    }

    public function guarantorDashboardView()
    {
        return Inertia::render('Guarantor/Dashboard');
    }
}
