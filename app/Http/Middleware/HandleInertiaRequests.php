<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'publicPath'=> function() use ($request){
                return env("APP_URL");
            },
            'flash'=>function() use ($request){
                return[
                    'success'=>$request->session()->get('success'),
                    'error'=>$request->session()->get('error'),
                ];
            },
            'contents'=>function() use ($request){
                $role=Role::where('name','admin')->first();
                $user=$role->users()->where('email','admin@zachanguloans.com')->first();

                if (is_object($user)){
                    $contents=json_decode($user->contents);
                    return[
                        'interest'=>$contents->interest,
                        'lowerLimit'=>$contents->lowerLimit,
                        'upperLimit'=>$contents->upperLimit,
                        'bankCharge'=>$contents->bankCharge,
                    ];
                }else
                    return null;

            },
            'bannerMessage'=> function() use ($request){
                return $request->session()->get('bannerMessage');
            },
            'admin'=> function() use ($request){
                if(Auth::check()){
                    $user=User::find(Auth::id());
                    return $user->hasRole('admin');
                }
                return false;
            },
        ]);
    }
}
