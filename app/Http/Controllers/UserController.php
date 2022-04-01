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
    public function index()
    {
        $user=User::find(Auth::id());
        $loan=$user->loans()->where('progress','<',4)->orderBy('appliedDate','DESC')->first();
//        $loan=null;
//        if ($user->employer_id != null){

//        }
        return Inertia::render('Dashboard',[
            'loan'=>$loan
        ]);
    }

    public function redirect()
    {
        $user=User::find(Auth::id());

        if($user->hasRole('borrower')){
            return Redirect::route('dashboard');
        }else
            return Inertia::render('Guarantor/Dashboard');

    }



    public function guarantorDashboardView()
    {
        return Inertia::render('Guarantor/Dashboard');
    }
}
