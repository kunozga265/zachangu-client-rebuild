<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\LoanController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

Route::group(['middleware'=>['auth:sanctum','verified','roles']],function (){

    /*
     * ------------------------------------------------------
     * Redirects to the appropriate page
     * ------------------------------------------------------
     * URL: /
     * Method: GET
    */
//    Route::get('/', [
//        UserController::class,"redirect",
//    ])->name('home');

    /*
     * ------------------------------------------------------
     * Home Route - Displays the dashboard
     * ------------------------------------------------------
     * URL: /
     * Method: GET
    */
    Route::get('/', [
        "uses" => "App\Http\Controllers\UserController@redirect",
        'roles' =>['client','verified','admin']
    ])->name('redirect');


    /*
     * ------------------------------------------------------
     * Client Routes
     * ------------------------------------------------------
    */

    Route::group([],function (){

        Route::get('/dashboard', [
            "uses" => "App\Http\Controllers\UserController@dashboard",
            'roles' =>['client','verified']
        ])->name('dashboard');


        Route::group(['prefix'=>'employer'],function () {
            /*
             * ------------------------------------------------------
             * Displays employer register form
             * ------------------------------------------------------
             * URL: /employer
             * Method: GET
            */
            Route::get('/register', [
                "uses" => "App\Http\Controllers\EmployerController@create",
                'roles' =>['client']
            ])->name('employer.new');

            /*
             * ------------------------------------------------------
             * Displays employer register form
             * ------------------------------------------------------
             * URL: /employer
             * Method: POST
            */
            Route::post('/register', [
                "uses" => "App\Http\Controllers\EmployerController@register",
                'roles' =>['client']
            ])->name('employer.register');

            /*
             * ------------------------------------------------------
             * Displays select employer form
             * ------------------------------------------------------
             * URL: /employer/select
             * Method: GET
            */
            Route::get('/select', [
                "uses" => "App\Http\Controllers\EmployerController@select",
                'roles' =>['client']
            ])->name('employer.index');

            /*
             * ------------------------------------------------------
             * Assigns a user to an employer
             * ------------------------------------------------------
             * URL: /employer/select
             * Method: POST
            */
            Route::post('/select', [
                "uses" => "App\Http\Controllers\EmployerController@employerSelect",
                'roles' =>['client']
            ])->name('employer.select');
        });

        Route::group(['prefix'=>'loan'],function (){

            /*
             * ------------------------------------------------------
             * Display all user loans
             * ------------------------------------------------------
             * URL: /loan/history
             * Method: GET
            */
            Route::get('/history/{role}',[
                "uses" => "App\Http\Controllers\LoanController@index",
                'roles' =>['client','verified']
            ])->name('loan.index');


            /*
             * ------------------------------------------------------
             * New Loan Application Form
             * ------------------------------------------------------
             * URL: /loan/new
             * Method: GET
            */
            Route::get('/new', [
                "uses" => "App\Http\Controllers\LoanController@create",
                'roles' =>['client','verified']
            ])->name('loan.new');

            /*
             * ------------------------------------------------------
             * Submit New Loan Application Form
             * ------------------------------------------------------
             * URL: /loan/new
             * Method: POST
            */
            Route::post('/new', [
                "uses" => "App\Http\Controllers\LoanController@store",
                'roles' =>['client','verified']
            ])->name('loan.store');

            /*
             * ------------------------------------------------------
             * Submit New Loan Application Form
             * ------------------------------------------------------
             * URL: /loan/new
             * Method: POST
            */
            /*
             * Disabled
            Route::post('/new/subscribed', [
                "uses" => "App\Http\Controllers\LoanController@storeSubscribed",
                'roles' =>['verified']
            ])->name('loan.subscribed');*/

            /*
             * ------------------------------------------------------
             * Displays a loan with its details
             * ------------------------------------------------------
             * URL: /loan/view/{loan-code}
             * Method: GET
            */
            Route::get('/view/{code}', [
                "uses" => "App\Http\Controllers\LoanController@show",
                'roles' =>['client','verified']
            ])->name('loan.show');

            /*
             * ------------------------------------------------------
             * Edits a loan
             * ------------------------------------------------------
             * URL: /loan/edit/{loan-code}
             * Method: GET
            */
            Route::get('/edit/{code}', [
                "uses" => "App\Http\Controllers\LoanController@edit",
                'roles' =>['verified']
            ])->name('loan.edit');

            /*
             * ------------------------------------------------------
             * Submit New Loan Application Form
             * ------------------------------------------------------
             * URL: /loan/edit/{code}
             * Method: POST
            */
            Route::post('/edit/{code}', [
                "uses" => "App\Http\Controllers\LoanController@update",
                'roles' =>['verified']
            ])->name('loan.update');

            /*
             * ------------------------------------------------------
             * Apply For a Loan
             * ------------------------------------------------------
             * URL: /loan/apply/{code}
             * Method: POST
            */
            Route::post('/apply/{code}', [
                "uses" => "App\Http\Controllers\LoanController@apply",
                'roles' =>['verified']
            ])->name('loan.apply');
        });

        /*
         * Disabled Guarantor Functionality
        */
//  Route::group(['prefix'=>'guarantor'],function (){
//        /*
//        * ------------------------------------------------------
//        * Home Route - Displays the dashboard
//        * ------------------------------------------------------
//        * URL: /
//        * Method: GET
//        */
//        Route::get('/', [
//            "uses" => "App\Http\Controllers\UserController@guarantorDashboardView",
//            'roles' =>['verified']
//        ])->name('guarantor');
//
//        /*
//        * ------------------------------------------------------
//        * Displays loan to be guaranteed
//        * ------------------------------------------------------
//        * URL: /guarantee/{code}
//        * Method: GET
//        */
//        Route::get('/guarantee/{code}', [
//            "uses" => "App\Http\Controllers\GuarantorController@create",
//            'roles' =>['verified']
//        ])->name('guarantor.create');
//
//        /*
//        * ------------------------------------------------------
//        * Guarantee a loan
//        * ------------------------------------------------------
//        * URL: /guarantee/{code}
//        * Method: POST
//        */
//        Route::post('/guarantee/{code}', [
//            "uses" => "App\Http\Controllers\GuarantorController@guarantee",
//            'roles' =>['verified']
//        ])->name('guarantor.guarantee');
//
//        /*
//        * ------------------------------------------------------
//        * Displays loan to be guaranteed
//        * ------------------------------------------------------
//        * URL: /guarantee/{code}
//        * Method: GET
//        */
//        Route::get('/loan/{code}', [
//            "uses" => "App\Http\Controllers\GuarantorController@show",
//            'roles' =>['verified']
//        ])->name('guarantor.show');
//
//    });

    });

    /*
     * ------------------------------------------------------
     * Admin Routes
     * ------------------------------------------------------
    */

    Route::group([],function (){

        /*
     * ------------------------------------------------------
     * Shows the Dashboard Page
     * ------------------------------------------------------
     * URL: /
     * Method: GET
    */
        Route::get('/admin/dashboard', [
            "uses" => "App\Http\Controllers\Admin\UserController@dashboard",
            'roles' =>['admin']
        ])->name('dashboard.admin');

        Route::get('/admin/notifications', [
            "uses" => "App\Http\Controllers\Admin\NotificationController@index",
            'roles' =>['admin']
        ])->name('notifications.admin');

        Route::group(['prefix'=>'admin/employers'],function (){

            Route::get('/', [
                "uses" => "App\Http\Controllers\Admin\EmployerController@index",
                'roles' =>['admin']
            ])->name('employer.admin.index');

            Route::post('/', [
                "uses" => "App\Http\Controllers\Admin\EmployerController@store",
                'roles' =>['admin']
            ])->name('employer.admin.store');

            Route::post('/edit/{id}', [
                "uses" => "App\Http\Controllers\Admin\EmployerController@update",
                'roles' =>['admin']
            ])->name('employer.admin.update');

            Route::get('/edit/{id}', [
                "uses" => "App\Http\Controllers\Admin\EmployerController@edit",
                'roles' =>['admin']
            ])->name('employer.admin.edit');

            Route::get('/new', [
                "uses" => "App\Http\Controllers\Admin\EmployerController@create",
                'roles' =>['admin']
            ])->name('employer.admin.new');

            Route::get('/view/{id}', [
                "uses" => "App\Http\Controllers\Admin\EmployerController@show",
                'roles' =>['admin']
            ])->name('employer.admin.show');

            Route::get('/new/{id}', [
                "uses" => "App\Http\Controllers\Admin\EmployeeController@create",
                'roles' =>['admin']
            ])->name('employee.admin.new');

        });

        Route::group(['prefix'=>'admin/employees'],function (){


            Route::post('/', [
                "uses" => "App\Http\Controllers\Admin\EmployeeController@store",
                'roles' =>['admin']
            ])->name('employee.admin.store');

            Route::post('/{id}', [
                "uses" => "App\Http\Controllers\Admin\EmployeeController@update",
                'roles' =>['admin']
            ])->name('employee.admin.update');

            Route::get('/{id}', [
                "uses" => "App\Http\Controllers\Admin\EmployeeController@edit",
                'roles' =>['admin']
            ])->name('employee.admin.edit');

        });

        Route::group(['prefix'=>'admin/users'],function (){

            Route::get('/', [
                "uses" => "App\Http\Controllers\Admin\UserController@index",
                'roles' =>['admin']
            ])->name('users.admin.index');

            Route::get('/{id}', [
                "uses" => "App\Http\Controllers\Admin\UserController@show",
                'roles' =>['admin']
            ])->name('users.admin.show');

            Route::get('/profile/show', [
                "uses" => "App\Http\Controllers\Admin\UserController@showAdmin",
                'roles' =>['admin']
            ])->name('profile.show.admin');

            Route::put('/profile', [
                "uses" => "App\Http\Controllers\Admin\UserController@update",
                'roles' =>['admin']
            ])->name('users.admin.update.profile');

            Route::put('/content', [
                "uses" => "App\Http\Controllers\Admin\UserController@updateContent",
                'roles' =>['admin']
            ])->name('users.admin.update.content');

        });

        Route::group(['prefix'=>'admin/loan'],function (){

            Route::get('/{code}', [
                "uses" => "App\Http\Controllers\Admin\LoanController@show",
                'roles' =>['admin']
            ])->name('loan.admin.show');

            Route::post('/approve/{code}', [
                "uses" => "App\Http\Controllers\Admin\LoanController@approve",
                'roles' =>['admin']
            ])->name('loan.admin.approve');

            Route::post('/reject/{code}', [
                "uses" => "App\Http\Controllers\Admin\LoanController@reject",
                'roles' =>['admin']
            ])->name('loan.admin.reject');

            Route::post('/close/{code}', [
                "uses" => "App\Http\Controllers\Admin\LoanController@close",
                'roles' =>['admin']
            ])->name('loan.admin.close');

            Route::post('/default/{code}', [
                "uses" => "App\Http\Controllers\Admin\LoanController@default",
                'roles' =>['admin']
            ])->name('loan.admin.default');

            Route::post('/make-payment/{code}', [
                "uses" => "App\Http\Controllers\Admin\LoanController@makePayment",
                'roles' =>['admin']
            ])->name('loan.admin.make-payment');

            Route::get('/export/datestamp', [
                "uses" => "App\Http\Controllers\Admin\LoanController@exportDatasheetPage",
                'roles' =>['admin']
            ])->name('loan.admin.export');

            Route::get('/export/datestamp/{datestamp}', [
                "uses" => "App\Http\Controllers\Admin\LoanController@exportDatasheet",
                'roles' =>['admin']
            ])->name('loan.admin.export.datasheet');

            Route::get('/export/pdf/{code}', [
                "uses" => "App\Http\Controllers\Admin\LoanController@exportPDF",
                'roles' =>['admin']
            ])->name('loan.admin.export.pdf');

        });
    });
});





