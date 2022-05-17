<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Employer;
use App\Models\Loan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function dashboard()
    {
        $now=Carbon::now()->getTimestamp();

        $pending=Loan::where('progress',2)->get();
        $active=Loan::where('progress',3)->where('dueDate','>',$now)->get();
        $due=Loan::where('progress',3)->where('dueDate','<',$now)->get();

        foreach ($due as $loan){
            $loan->progress=6;
        }

        return Inertia::render('Admin/Dashboard',[
            'pending'   =>  $pending,
            'active'    =>  $active,
            'due'       =>  $due,
        ]);
    }

    public function index()
    {

        $users=User::orderBy('firstName','ASC')->where('id','!=',Auth::id())->get();

        return Inertia::render('Admin/Users/Index',[
            'users'=>UserResource::collection($users),
        ]);

    }

    public function show($id)
    {
        $user=User::find($id);

        if(is_object($user)){

            $loans=$user->loans()->where('progress','>',1)->orderBy('appliedDate','DESC')->get();

            return Inertia::render('Admin/Users/Show',[
               '_user'=>$user,
               'loans'=> $loans
            ]);

        }else
            return Redirect::route('dashboard.admin')->with('error','Invalid user');

    }

    public function showAdmin()
    {
        return Inertia::render('Profile/ShowAdmin');
    }

    public function store()
    {

    }

    public function update(Request $request)
    {
        $user=User::find(Auth::id());

        Validator::make($request->all(), [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->forceFill([
            'firstName'     => $request->firstName,
            'lastName'      => $request->lastName,
            'email'         => $request->email,
        ])->save();

        return Redirect::back();
    }

    public function updateContent(Request $request)
    {
        $role=Role::where('name','admin')->first();
        $user=$role->users()->where('email','admin@zachanguloans.com')->first();

        Validator::make($request->all(), [
            'interest' => ['required'],
            'lowerLimit' => ['required'],
            'upperLimit' => ['required'],
            'bankCharge' => ['required'],

        ])->validateWithBag('updateProfileInformation');

        $user->forceFill([
            'contents'=> json_encode([
                'interest' => floatval($request->interest),
                'lowerLimit' => intval($request->lowerLimit),
                'upperLimit' => intval($request->upperLimit),
                'bankCharge' => floatval($request->bankCharge)
            ]),
        ])->save();

        return Redirect::back();
    }



}
