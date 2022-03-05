<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class GuarantorController extends Controller
{
    public function create($code)
    {
        $loan=Loan::where('code',$code)->where("progress",1)->first();

        if (is_object($loan)){

            $loan->appliedDate=$loan->appliedDate!=null?date('jS F, Y',$loan->appliedDate):null;
            $loan->dueDate=$loan->dueDate!=null?date('jS F, Y',$loan->dueDate):null;
            $loan->guarantorDate=$loan->guarantorDate!=null?date('jS F, Y',$loan->guarantorDate):null;
            $loan->approvedDate=$loan->approvedDate!=null?date('jS F, Y',$loan->approvedDate):null;
            $loan->closedDate=$loan->closedDate!=null?date('jS F, Y',$loan->closedDate):null;
            $loan->contractDuration=date('jS F, Y',$loan->contractDuration);
            $loan->physicalAddress=json_decode($loan->physicalAddress);
            $loan->workAddress=json_decode($loan->workAddress);

            //return Loan view
            return Inertia::render('Guarantor/Guarantee',[
                'loan'=>$loan,
                'termsAndConditionsGuarantor'=>$this->termsAndConditions($loan, $loan->physicalAddress,$loan->dueDate),
            ]);
        }else
            return Redirect::route('guarantor')->with('error','Invalid code. Try again.');

    }

    public function guarantee(Request $request,$code)
    {

        $loan=Loan::where('code',$code)->first();

        if (is_object($loan)) {

            $loan->update([
                'progress' => 2,
                'guarantor_id' => Auth::id(),
                'termsAndConditionsGuarantor' => $this->termsAndConditions($loan,json_decode($loan->physicalAddress),date('jS F, Y',$loan->dueDate)),
                'guarantorDate'=>Carbon::now()->getTimestamp()
            ]);

            return Redirect::route('guarantor.show',['code'=>$loan->code]);

        }else
            return Redirect::back()->with('error','Invalid loan. Please try again.');
    }

    public function show($code)
    {
        $loan=Loan::where('code',$code)->where("guarantor_id",Auth::id())->first();

        if (is_object($loan)){

            $loan->appliedDate=$loan->appliedDate!=null?date('jS F, Y',$loan->appliedDate):null;
            $loan->dueDate=$loan->dueDate!=null?date('jS F, Y',$loan->dueDate):null;
            $loan->guarantorDate=$loan->guarantorDate!=null?date('jS F, Y',$loan->guarantorDate):null;
            $loan->approvedDate=$loan->approvedDate!=null?date('jS F, Y',$loan->approvedDate):null;
            $loan->closedDate=$loan->closedDate!=null?date('jS F, Y',$loan->closedDate):null;
            $loan->contractDuration=date('jS F, Y',$loan->contractDuration);
            $loan->physicalAddress=json_decode($loan->physicalAddress);
            $loan->workAddress=json_decode($loan->workAddress);

            //return Loan view
            return Inertia::render('Guarantor/Show',[
                'loan'=>$loan,
            ]);
        }else
            return Redirect::route('guarantor')->with('error','You cannot view this loan.');

    }

    private function termsAndConditions($loan, $decodedAddress, $dueDate): string
    {
        $user=User::find(Auth::id());
        $fullname =$user->firstName.' '.$user->lastName;

        $issuedDate=date('d/m/y');
        $day=date('d');
        $month=date('F');
        $year=date('Y');

        $employeeName =$loan->firstName.' '.$loan->lastName;
        $address = $decodedAddress->name.' P. O. Box '.$decodedAddress->box.' '.$decodedAddress->location;

        $interest=10;


        return " <div class='mt-4'><strong>Guarantor Agreement [03/00]</strong></div>
<div class='mt-4'><strong>Issued on: $issuedDate</strong></div>
<div class='mt-4'>This Loan Agreement (this “Agreement”), is executed as of this <span class='underline font-bold'>$day</span> day of <span class='underline font-bold'>$month, $year</span> (the “Effective Date”)&nbsp;</div>
<div class='mt-4'>By and between&nbsp;</div>
<div class='mt-4'> <span class='underline font-bold'>$fullname</span>, located at <span class='underline font-bold'>$address</span> , hereinafter referred to as the “Guarantor”;</div>
<div class='mt-4'>And&nbsp;</div>
<div class='mt-4'><strong>ZACHANGU MICROFINANCE AGENCY</strong>, located at <strong>AREA 49-SHIRE</strong>, hereinafter referred to as the “Lender”;&nbsp;</div>
<div class='mt-4'><strong>WHEREAS</strong> at the request of the Employee, the Lender has agreed to grant a Payday Loan of <span class='underline font-bold'>MK$loan->amount</span> to the Employee <span class='underline font-bold'>$employeeName</span> till <span class='underline font-bold'>$dueDate</span> on terms and conditions hereinafter contained.</div>
<div class='mt-4'><strong>PLEASE NOTE: THIS AGREEMENT IS CORRELATED WITH AGREEMENT [01/00] and [02/00]</strong></div>
<div class='mt-4'>The parties agree as follows:&nbsp;</div>
<div class='mt-4'>Guarantor <span class='underline font-bold'>$fullname</span> located at <span class='underline font-bold'>$address</span> promises to unconditionally guarantee to the Lender, the full payment and performance by Employee of all duties and obligations arising under this Agreement. The Guarantor agrees that this guarantee shall remain in full force and effect and be binding on the Guarantor until this Agreement is satisfied. </div>
<div class='mt-4'>Where the Employee fails to pay back the loan to Zachangu Microfinance, the guarantor will repay the loan together with all outstanding charges indicated in this agreement. </div>";
    }
}
