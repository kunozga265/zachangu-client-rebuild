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
        "uses" => "App\Http\Controllers\UserController@index",
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





