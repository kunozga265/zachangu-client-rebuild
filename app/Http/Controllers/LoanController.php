<?php

namespace App\Http\Controllers;

use App\Mail\EmployeePendingMail;
use App\Mail\EmployerPendingMail;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

/*
 * Loan Status
 * 0 - Waiting for application
 * 1 - Waiting for guarantor approval
 * 2 - Waiting for employer to communicate for approval
 * 3 - Active Loan
 * 4 - Closed Loan
 * 5 - Defaulted
 * 6 - Over Due
 * 7 - Rejected
  */

class LoanController extends Controller
{
    public function index($role)
    {
        $user=User::find(Auth::id());

        if($role=='applied'){
            $loans=$user->loans()->orderBy('appliedDate','DESC')->get();

            return Inertia::render('Loan/Index', [
                'loans' => $loans,
                'role'=>$role
            ]);

        }else{
            $loans=Loan::where('guarantor_id',Auth::id())->orderBy('appliedDate','DESC')->get();

            foreach ($loans as $loan){
                $loan->appliedDate=date('jS F, Y',$loan->appliedDate);
            }

            return Inertia::render('Guarantor/Index', [
                'loans' => $loans,
                'role'=>$role
            ]);
        }
    }

    public function show($code)
    {
        $user=User::find(Auth::id());
        $loan=$user->loans()->where('code',$code)->first();

        if($user->employer_id != null){
            //check for eligibility whether the employee data is available
            $employee=$user->employer->employees()->where('nationalId',$user->nationalId)->first();

            //contract duration
            $contractDurationEligibility=false;
            $contractDuration='';

            //current time
            $now=Carbon::now();

            //if the employee's data is available under employer
            if (is_object($employee)){
                $contractDuration=$employee->contractDuration;
                $time=Carbon::createFromTimestamp($contractDuration);

                $diff=$time->diff($now);

                //if the contract date is ahead
                if($diff->invert == 1){
                    //eligible if the contract date is greater than a year
                    if($diff->y > 0){
                        $contractDurationEligibility=true;
                    }
                    else{
                        //eligible if the contract date is equal or greater than 3 months
                        if($diff->m >= 6) {
                            $contractDurationEligibility = true;
                            //eligible if contract expires within 6 months
                        }else{
                            $contractDurationEligibility=false;
                        }
                    }
                }
                //not eligible if contract has expired
                else {
                    $contractDurationEligibility=false;
                    $contractDuration=0;
                }
            }

            if (is_object($loan)) {

                $dueDate=$loan->dueDate;
                $loan->dueDate=$loan->dueDate!=null?date('jS F, Y',$loan->dueDate):null;
                $loan->appliedDate=$loan->appliedDate!=null?date('jS F, Y',$loan->appliedDate):null;
                $loan->guarantorDate=$loan->guarantorDate!=null?date('jS F, Y',$loan->guarantorDate):null;
                $loan->approvedDate=$loan->approvedDate!=null?date('jS F, Y',$loan->approvedDate):null;
                $loan->closedDate=$loan->closedDate!=null?date('jS F, Y',$loan->closedDate):null;
                $loan->physicalAddress=json_decode($loan->physicalAddress);
                $loan->workAddress=json_decode($loan->workAddress);

                $employee=Employee::where('nationalId',$loan->nationalId)->first();

                return Inertia::render('Loan/Show', [
                    'termsAndConditions'=>$this->termsAndConditions($loan, $loan->physicalAddress,$dueDate,$employee),
                    'loan' => $loan,
                    'contractDuration'=>$contractDuration,
                    'contractDurationDate'  => date('jS F, Y',$contractDuration),
                    'contractDurationEligibility'=>$contractDurationEligibility,
                    'employer'=>$employee->employer

                ]);
            }else{
                return Redirect::route('dashboard');
            }

        }else{
            return Redirect::route('employer.index');
        }
    }

    public function create()
    {
        return Inertia::render('Loan/NewLoanApplication');
        /*
         * Subscription disabled
        $user=User::find(Auth::id());

        $today=Carbon::today();
        //subscribed
        if($user->subscription != 0 && $today->getTimestamp() <= $user->subscription){
            return Inertia::render('Loan/NewSubscribedLoanApplication');

        //Not Subscribed
        }else
        */

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
            'nationalId'           => ['required','min:8','max:8'],
            'contractDuration'     => ['required'],
            'payDay'               => ['required'],
            'paySlipFile'          => ['required'],
            'nationalIdFile'       => ['required'],
//            'contractFile'         => ['required'],
            'net'                  => ['required'],
            'payments'             => ['required'],
//            'termsAndConditions'   => ['required'],
            'amount'               => ['required'],
        ])->validate();

        if($this->currentLoansCount()){


            $loan = new Loan([
                'code'                 => Carbon::now()->getTimestamp(), //generate code
                'photo'                => $request->photo,
                'firstName'            => $request->firstName,
                'middleName'           => $request->middleName,
                'lastName'             => $request->lastName,
                'phoneNumberMobile'    => $request->phoneNumberMobile,
                'phoneNumberWork'      => $request->phoneNumberWork,
                'position'             => $request->position,
                'email'                => $request->email,
                'physicalAddress'      => json_encode($request->physicalAddress),
                'workAddress'          => json_encode($request->workAddress),
                'nationalId'           => strtoupper($request->nationalId),
                'contractDuration'     => $request->contractDuration,
                'payDay'               => $request->payDay,
                'paySlip'              => $request->paySlipFile,
                'nationalIdFile'       => $request->nationalIdFile,
                'contract'             => $request->contractFile,
                'progress'             => 0,
                'amount'               => $request->amount,
                'net'                  => $request->net,
                'score'                => 0,
                'payments'             => $request->payments,
                'dueDate'              => $this->calculateDueDate($request->payDay,$request->payments),
                'user_id'              => Auth::id(),
            ]);

            $loan->save();

            $loan->update([
                'score'=>$this->getScore($loan)
            ]);

            return Redirect::route('loan.show',['code'=>$loan->code]);

        }else
            return Redirect::back()->with('error','You already have an active loan.');

    }

    public function storeSubscribed(Request $request)
    {
        Validator::make($request->all(), [
            'amount'               => ['required'],
        ])->validate();

        $user=User::find(Auth::id());
        $today=Carbon::today();
        //check if the subscription is valid
        if($user->subscription != 0) {
            if($today->getTimestamp() <= $user->subscription){
                if($this->currentLoansCount()){
                    //get when the subscription ends
                    $subscribedDate = Carbon::createFromTimestamp($user->subscription);

                    //getting latest loan details
                    $loanDetails = $user->loans()
                        ->where('created_at', '>=', $subscribedDate->subMonth(3)->toDateTimeString()) //within subscribed period
                        ->where('progress', 4) //it has been paid back
                        ->latest()
                        ->first();

                    // if loan exists
                    if(is_object($loanDetails)){
                        //Calculate due date which is the next pay day
                        $now=Carbon::now();
                        $dueDate=Carbon::createFromDate(null,null,$request->payDay);

                        $diff=$dueDate->diff($now);

                        //due date is behind
                        if($diff->invert == 0) {
                            $dueDate->addMonth();
                        }

                        $loan = new Loan([
                            'code'                 => Carbon::now()->getTimestamp(), //generate code
                            'photo'                => $loanDetails->photo,
                            'firstName'            => ucwords($loanDetails->firstName),
                            'middleName'           => ucwords($loanDetails->middleName),
                            'lastName'             => ucwords($loanDetails->lastName),
                            'phoneNumberMobile'    => $loanDetails->phoneNumberMobile,
                            'phoneNumberWork'      => $loanDetails->phoneNumberWork,
                            'position'             => ucwords($loanDetails->position),
                            'email'                => $loanDetails->email,
                            'physicalAddress'      => $loanDetails->physicalAddress,
                            'workAddress'          => $loanDetails->workAddress,
                            'nationalId'           => strtoupper($loanDetails->nationalId),
                            'contractDuration'     => $loanDetails->contractDuration,
                            'payDay'               => $loanDetails->payDay,
                            'paySlip'              => $loanDetails->paySlip,
                            'nationalIdFile'       => $loanDetails->nationalIdFile,
                            'contract'             => $loanDetails->contract,
                            'progress'             => 0,
                            'amount'               => $request->amount,
                            'score'                => $request->score,
                            'dueDate'              => $dueDate->getTimestamp(),
                            'user_id'              => Auth::id(),
                        ]);
                        $loan->save();

                        return Redirect::route('loan.show',['code'=>$loan->code]);

                    // Clear subscription and send them to fill a new form
                    }else{
                        $user->update([
                            'subscription'=>0
                        ]);
                        return Redirect::route('loan.new')->with('error','Error Occurred. Please fill the entire form.');

                    }

                }else
                    return Redirect::back()->with('error','You already have an active loan.');
            }else
                return Redirect::route('loan.new')->with('error','Subscription has expired. Please fill the entire form');
        }else
            return Redirect::route('loan.new')->with('error','Not subscribed. Please fill the entire form.');
    }

    public function edit($code)
    {
        $user=User::find(Auth::id());
        $loan=$user->loans()->where('code',$code)->first();

        if (is_object($loan)) {

            $time=Carbon::createFromTimestamp($loan->contractDuration);
            return Inertia::render('Loan/UpdateLoanApplication',[
                'loan' => $loan,
                'contractDurationISO' => $time->toDateTimeString(),
                'physicalAddress'=>json_decode($loan->physicalAddress),
                'workAddress'=>json_decode($loan->workAddress)
            ]);
        }else
            return Redirect::back()->with('error','Invalid loan.');
    }

    public function update(Request $request, $code)
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
            'nationalId'           => ['required','min:8','max:8'],
            'contractDuration'     => ['required'],
            'payDay'               => ['required'],
            'paySlipFile'          => ['required'],
            'nationalIdFile'       => ['required'],
//            'contractFile'         => ['required'],
            'net'                  => ['required'],
            'payments'             => ['required'],
//            'termsAndConditions'   => ['required'],
            'amount'               => ['required'],
        ])->validate();

        $user=User::find(Auth::id());
        $loan=$user->loans()->where('code',$code)->first();

        if (is_object($loan)) {
            $loan->update([
                'photo'             => $request->photo,
                'firstName'         => ucwords($request->firstName),
                'middleName'        => ucwords($request->middleName),
                'lastName'          => ucwords($request->lastName),
                'phoneNumberMobile' => $request->phoneNumberMobile,
                'phoneNumberWork'   => $request->phoneNumberWork,
                'position'          => ucwords($request->position),
                'email'             => $request->email,
                'physicalAddress'   => json_encode($request->physicalAddress),
                'workAddress'       => json_encode($request->workAddress),
                'contractDuration'  => $request->contractDuration,
                'payDay'            => $request->payDay,
                'paySlip'           => $request->paySlipFile,
                'nationalIdFile'    => $request->nationalIdFile,
                'contract'          => $request->contractFile,
                'amount'            => $request->amount,
                'net'               => $request->net,
                'payments'          => $request->payments,
                'dueDate'           => $this->calculateDueDate($request->payDay,$request->payments),
            ]);

            //Update Score
            $loan->update([
                'score' => $this->getScore($loan)
            ]);

            return Redirect::route('loan.show',['code'=>$loan->code]);

        }else
            return Redirect::back()->with('error','Invalid loan.');
    }

    private function calculateDueDate($payDay,$payments)
    {
        //Calculate due date which is the next pay day
        $today=Carbon::now();
        $dueDate=Carbon::createFromDate(null,null,$payDay);

        $diff=$dueDate->diff($today);

        //due date is behind
        if($diff->invert == 0) {
            $dueDate->addMonth();
        }

        $dueDate->addMonths($payments);

        //correct date
        $dueDate->subMonth();

        return $dueDate->getTimestamp();

    }

    public function apply(Request $request,$code){
        $user=User::find(Auth::id());
        $loan=$user->loans()->where('code',$code)->first();

        if (is_object($loan)) {
//            $macAddress=exec('getmac');

            $ipAddress=\Request::ip();
            $signatureTime=Carbon::now()->toDateTimeString();
            $signature="Signature: [$request->browserInfo, Ip Address: $ipAddress, Time: $signatureTime]";

 	        $employee=Employee::where('nationalId',$loan->nationalId)->first();
            $loan->update([
                'progress' => 2,
                'termsAndConditions' => $this->termsAndConditions($loan,json_decode($loan->physicalAddress),$loan->dueDate,$employee,$signature),
                'appliedDate'=>Carbon::now()->getTimestamp()
            ]);

            /*
            * Subscription Disabled
            //subscribe for the next three months
            $user->update([
                'subscription'=>Carbon::today()->addMonth(3)->getTimestamp()
            ]);*/

            $role=Role::where('name','admin')->first();
            $userAdmin=$role->users()->first();
            $contents=json_decode($userAdmin->contents);
            $loanSummary=$this->paymentsCalculation($loan->dueDate,$loan,$contents);

            $employee=Employee::where('nationalId',$loan->nationalId)->first();
            Mail::to($loan->email)->cc('admin@zachanguloans.com')->send(new EmployeePendingMail($employee,$loan->code));
            Mail::to($employee->employer->proxyEmail)->cc('admin@zachanguloans.com')->send(new EmployerPendingMail($loan,$employee->employer->proxyName,$loanSummary));


            return Redirect::route('loan.show',['code'=>$loan->code]);

        }else
            return Redirect::back();
    }

    public static function getScore($loan){

        $employee=Employee::where('nationalId',$loan->nationalId)->first();
        if(is_object($employee)){
            //decoding physical address
            $employeePhysicalAddress=json_decode($employee->physicalAddress);
            $loanPhysicalAddress=json_decode($loan->physicalAddress);

            //calculating score
            similar_text($employeePhysicalAddress->name,$loanPhysicalAddress->name,$physicalAddressName);
            similar_text($employeePhysicalAddress->box,$loanPhysicalAddress->box,$physicalAddressBox);
            similar_text($employeePhysicalAddress->location,$loanPhysicalAddress->location,$physicalAddressLocation);

            //decoding work address
            $employeeWorkAddress=json_decode($employee->workAddress);
            $loanWorkAddress=json_decode($loan->workAddress);

            //calculating score
            similar_text($employeeWorkAddress->name,$loanWorkAddress->name,$workAddressName);
            similar_text($employeeWorkAddress->box,$loanWorkAddress->box,$workAddressBox);
            similar_text($employeeWorkAddress->location,$loanWorkAddress->location,$workAddressLocation);

            //computing dates
            $employeeContractDuration=date('jS F, Y',$employee->contractDuration);
            $loanContractDuration=date('jS F, Y',$loan->contractDuration);

            similar_text(substr($employeeContractDuration,0,-6),substr($loanContractDuration,0,-6),$contractDurationDate);


            similar_text($employee->firstName,$loan->firstName,$firstName);
            similar_text($employee->middleName,$loan->middleName,$middleName);
            similar_text($employee->lastName,$loan->lastName,$lastName);
            similar_text($employee->email,$loan->email,$email);
            similar_text($employee->phoneNumberMobile,$loan->phoneNumberMobile,$phoneNumberMobile);
            similar_text($employee->phoneNumberWork,$loan->phoneNumberWork,$phoneNumberWork);

            similar_text($employee->position,$loan->position,$position);

            //total score
            return (
                    round($firstName)+
                    round($middleName)+
                    round($lastName)+
                    round($email)+
                    round($phoneNumberMobile)+
                    round($phoneNumberWork)+
                    round(($physicalAddressName + $physicalAddressBox + $physicalAddressLocation)/3)+
                    round($position)+
                    round((/*$workAddressName +*/ $workAddressBox + $workAddressLocation)/2)+
                    round($contractDurationDate))/10;
        }else
            return 0;

    }

    public function uploadFile(Request $request)
    {

        $file_name='';
        if (isset($request->name)){
//            $name=str_slug($request->name);
            $name=$request->name;

            $file=$request->file;
            $type=$request->type;

            //upload new picture and update database
            $explodedFile=explode(',',$file);
            //$decodedFile=base64_decode($explodedFile[1]);


            //develop name
            $ext=$this->getExtension($explodedFile);
            $filename="files/".$name."-".$type."-".uniqid().".".$ext;

            if($type=='photo'||$type=='photo_employee'){
                if($ext=='jpg' || $ext=='png'){
                    try{
                        Storage::disk('public_uploads')->put(
                            $filename,file_get_contents($file)
                        );
                    }catch (\RuntimeException $e){
                        return response()->json([
                            'message' => "Failed to upload",
                        ],501);
                    }
                }else {
                    return response()->json([
                        'message' => "Invalid extension",
                    ],415);
                }
            } else{
                if($ext=='jpg' || $ext=='png' || $ext=='pdf'){
                    try{
                        Storage::disk('public_uploads')->put(
                            $filename,file_get_contents($file)
                        );
                    }catch (\RuntimeException $e){
                        return response()->json([
                            'message' => "Failed to upload",
                        ],501);
                    }
                }else {
                    return response()->json([
                        'message' => "Invalid extension",
                    ],415);
                }
            }

            //for web configuration
//            if($request->type=='agreement' || $request->type=='terms' || $request->type=='photo_employee')
//                $filename='https://console.zachanguloans.com/'.$filename;
//            else
//                $filename='https://app.zachanguloans.com/'.$filename;

            return response()->json([
                'new_file'      =>  $filename
            ],200);
        }else{
            return response()->json([
                'message' => "Invalid extension",
            ],415);
        }
    }

    private function getExtension($explodedImage)
    {
        $imageExtensionDecode=explode('/',$explodedImage[0]);
        $imageExtension=explode(';',$imageExtensionDecode[1]);
        $lowercaseExt=strtolower($imageExtension[0]);
        if($lowercaseExt=='jpeg')
            return 'jpg';
        else
            return $lowercaseExt;
    }

    private function termsAndConditions($loan, $decodedAddress, $dueDate, $employee,$signature=""): string
    {
        $issuedDate=date('d/m/y');
        $day=date('d');
        $month=date('F');
        $year=date('Y');
        $fullname =$loan->firstName.' '.$loan->lastName;
        $address = $decodedAddress->name.' P. O. Box '.$decodedAddress->box.' '.$decodedAddress->location;

        $role=Role::where('name','admin')->first();
        $user=$role->users()->first();
        $contents=json_decode($user->contents);
        $interest=($contents->interest)*100;

        $loanSummary=$this->paymentsCalculation($dueDate,$loan,$contents);

        $dueDate=date('jS F, Y',$dueDate);



        return "

<div class='mt-4'><strong>Employee Agreement [01/00]</strong></div>
<div class='mt-4'><strong>Issued on: $issuedDate</strong></div>
<div class='mt-4'>This Loan Agreement (this “Agreement”), is executed as of this <span class='underline font-bold'>$day</span> day of <span class='underline font-bold'>$month, $year</span> (the “Effective Date”)&nbsp;</div>
<div class='mt-4'>By and between&nbsp;</div>
<div class='mt-4'> <span class='underline font-bold'>$fullname</span>, located at <span class='underline font-bold'>$address</span> , hereinafter referred to as the “Employee” ”Who is also the Borrower”;</div>
<div class='mt-4'>And&nbsp;</div>
<div class='mt-4'><strong>ZACHANGU MICROFINANCE AGENCY</strong>, located at <strong>AREA 49-SHIRE</strong>, hereinafter referred to as the “Lender”;&nbsp;</div>
<div class='mt-4'><strong>WHEREAS</strong> at the request of the Borrower, the Lender has agreed to grant a Loan of <span class='underline font-bold'>MK$loan->amount</span> to the Borrower till <span class='underline font-bold'>$dueDate</span> on terms and conditions hereinafter contained.</div>
<div class='mt-4'><strong>PLEASE NOTE: THIS AGREEMENT IS CORRELATED WITH AGREEMENT [02/00] and [03/00]</strong></div>
<div class='mt-4'>The parties agree as follows:&nbsp;</div>
<div class='mt-4'>1. <strong>Loan Amount</strong>: The Lender agrees to loan the Employee the principal sum of <span class='underline font-bold'>MK$loan->amount</span> (Not more than MK200,000.00).&nbsp;</div>
<div class='mt-4'>2. <strong>Transfer</strong>: Funds will be transferred by the lender to account number <span class='underline font-bold'>$employee->bankAccountNumber</span> held by <span class='underline font-bold'>$employee->bankName</span>  under the name <span class='underline font-bold'>$employee->bankAccountName </span> </div>
<div class='mt-4'>3. <strong>Interest:</strong> The loan bears interest at the rate of <span class='underline font-bold'>$interest%</span> till the Employee’s day of receiving salary: <span class='underline font-bold'>$dueDate</span></div>
<div class='mt-4'>Calculations are as follows:&nbsp;</div>
 <table class='my-4 w-full table-fixed'>
    <thead>
    <tr class='border-gray-400 border-b'>
        <th class='text-center text-tiny sm:text-xs md:text-sm lg:text-base'>Payment Date</th>
        <th class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>Opening Balance</th>
        <th class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>Monthly Payment</th>
        <th class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>Principal</th>
        <th class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>Interest</th>
        <th class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>Closing Balance</th>
    </tr>
    </thead>
    <tbody class='mt-2'>
        $loanSummary
    </body>
</table>
<div class='mt-4'>4. <strong>Repayment of Loan</strong>: The Loan, together with accrued and unpaid interest and all other charges, costs and expenses, is due and payable on or before <span class='underline font-bold'>$dueDate</span>.&nbsp;</div>
<div class='mt-4'>5. <strong>Penalty:</strong> Where the employer fails to honor the date of repayment for the loan, he/she will be given three days as grace period. If the Borrower fails to pay within the grace period of three days, after the three days, each day the borrower agrees to pay 10% of the due payment till payment is made. The Borrower will also pay to the Lender all charges the Lender met to collect the overdue payment.&nbsp;</div>
<div class='mt-4'>6. <strong>Prepayment:</strong> The Borrower has the right to prepay all or any part of the Loan, together with accrued and unpaid interest thereon, at any time without prepayment penalty or premium of any kind.&nbsp;</div>
<div class='mt-4'>7. <strong>Costs and Expenses</strong>: Borrower shall pay to the Lender all costs of collection, including reasonable attorney's fees, the Lender incurs in enforcing this Agreement.&nbsp;</div>
<div class='mt-4'>8. <strong>Waiver:</strong> The Borrower and all sureties, guarantors and endorsers here of, waive presentment, protest and demand, notice of protest, demand and dishonor and nonpayment of this Agreement.&nbsp;</div>
<div class='mt-4'>9. <strong>Successors and Assigns</strong>: This Agreement will inure to the benefit of and be binding on the respective successors and permitted assigns of the Lender and the Borrower.&nbsp;</div>
<div class='mt-4'>10. <strong>Amendment:</strong> This Agreement may be amended or modified only by a written agreement, duly signed by both the Borrower and the Lender.&nbsp;</div>
<div class='mt-4'>11. <strong>Notices:</strong> Any notice or communication under this Loan must be in writing and sent via email.</div>
<div class='mt-4'>12. <strong>No Waiver:</strong> Lender shall not be deemed to have waived any provision of this Agreement or the exercise of any rights held under this Agreement unless such waiver is made expressly and in writing. Waiver by Lender of a breach or violation of any provision of this Agreement shall not constitute a waiver of any other subsequent breach or violation.&nbsp;</div>
<div class='mt-4'>13. <strong>Severability:</strong> In the event that any of the provisions of this Agreement are held to be invalid or unenforceable in whole or in part, the remaining provisions shall not be affected and shall continue to be valid and enforceable as though the invalid or unenforceable parts had not been included in this Agreement.&nbsp;</div>
<div class='mt-4'>14. <strong>Assignment:</strong> Borrower shall not assign this Agreement, in whole or in part, without the written consent of Lender. Lender may assign all or any portion of this Agreement with written notice to Borrower.&nbsp;</div>
<div class='mt-4'>15. <strong>Governing Law:</strong> This Agreement shall be governed by and construed in accordance with the laws of the Republic of Malawi, not including its conflicts of law provisions.&nbsp;</div>
<div class='mt-4'>16. <strong>Disputes:</strong> Any dispute arising from this Agreement shall be resolved in the courts of the Republic of Malawi.&nbsp;</div>
<div class='mt-4'>17. <strong>Entire Agreement:</strong> This Agreement contains the entire understanding between the parties and supersedes and cancels all prior agreements of the parties, whether oral or written, with respect to such subject matter.&nbsp;</div>
<div class='mt-4'>IN WITNESS WHEREOF, the parties have executed this Agreement as of the date first stated above.</div>
<div class='mt-6'>$signature</div>"
            ;
    }

    private function paymentsCalculation($dueDate,$loan,$contents){
        //dates
        $_dueDate=Carbon::createFromTimestamp($dueDate)->subMonths($loan->payments);
        //correct date
        $_dueDate->addMonth();

        //make calculations
        $loanSummary="";
        $balance=$loan->amount;

        $num=$contents->interest*pow((1+$contents->interest),$loan->payments);
        $den=pow((1+$contents->interest),$loan->payments)-1;
        $monthlyPayment=$loan->amount*($num/$den);

        for($payment=1; $payment<=$loan->payments;$payment++){
            $openingBalance=$balance;
            $monthlyInterest=$balance*$contents->interest;
            $balance=floatval($balance*(1+$contents->interest))-floatval($monthlyPayment);
            $principal=floatval($monthlyPayment)-floatval($monthlyInterest);

            $_openingBalance=number_format($openingBalance,2);
            $_monthlyPayment=number_format($monthlyPayment,2);
            $_monthlyInterest=number_format($monthlyInterest,2);
            $_balance=number_format($balance,2);
            $_principal=number_format($principal,2);

            $calculatedDueDate=date('Y-m-d',$_dueDate->getTimestamp());

            $loanSummary .=
                "<tr>
                    <td class='text-center text-tiny sm:text-xs md:text-sm lg:text-base'>$calculatedDueDate</td>
                    <td class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>$_openingBalance</td>
                    <td class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>$_monthlyPayment</td>
                    <td class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>$_principal</td>
                    <td class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>$_monthlyInterest</td>
                    <td class='text-right text-tiny sm:text-xs md:text-sm lg:text-base'>$_balance</td>
                </tr>";

            $_dueDate->addMonth();

        }

        return $loanSummary;
    }

    private function currentLoansCount(): bool
    {
        $user=User::find(Auth::id());
        $currentLoans=$user->loans()->where('progress','<',4)->get();
        return $currentLoans->count()==0;
    }
}
