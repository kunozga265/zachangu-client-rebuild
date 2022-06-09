<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LoanExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Mail\ApprovalMail;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

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
    public function index()
    {
        $user=User::find(Auth::id());

        if($user->hasRole('borrower')){
            $loans=$user->loans()->orderBy('appliedDate','DESC')->get();

            return Inertia::render('Admin/Loan/Index', [
                'loans' => $loans,
            ]);

        }else{
            $loans=Loan::where('guarantor_id',Auth::id())->orderBy('appliedDate','DESC')->get();

            foreach ($loans as $loan){
                $loan->appliedDate=date('jS F, Y',$loan->appliedDate);
            }

            return Inertia::render('Admin/Guarantor/Index', [
                'loans' => $loans,
            ]);

        }




    }

    public function show($code)
    {
        $loan=Loan::where('code', $code)->where('progress','>',1)->first();

        if (is_object($loan)) {

            return Inertia::render('Admin/Loan/Show', [
                '_loan' => new LoanResource($loan),
            ]);
        }else{
            return Redirect::route('dashboard.admin')->with('bannerMessage','Invalid Loan');
        }
    }



    public function approve(Request $request,$code){

        $loan=Loan::where('code',$code)->where('progress',2)->first();

        if (is_object($loan)) {
            $now=Carbon::now()->getTimestamp();

            if($now<$loan->dueDate){

                $loan->update([
                    'progress' => 3,
                    'approvedDate'=>Carbon::now()->getTimestamp()
                ]);

                try {
                    Mail::to($loan->email)->cc('admin@zachanguloans.com')->send(new ApprovalMail($loan));
                }catch (\Swift_TransportException $exception){
                    //do something
                }



            }else
                return Redirect::back()->with('bannerMessage','Due date exceeded. Cannot approve loan.');


            return Redirect::route('loan.admin.show',['code'=>$loan->code]);

        }else
            return Redirect::route('dashboard.admin')->with('bannerMessage','Invalid Loan');
    }


    public function reject(Request $request,$code){

        $loan=Loan::where('code',$code)->where('progress',2)->first();

        if (is_object($loan)) {

            $loan->update([
                'progress' => 7,
                'closedDate'=>Carbon::now()->getTimestamp()
            ]);

            return Redirect::route('loan.admin.show',['code'=>$loan->code]);

        }else
            return Redirect::route('dashboard.admin')->with('bannerMessage','Invalid Loan');
    }

    public function close(Request $request,$code){

        $loan=Loan::where('code',$code)->first();

        if (is_object($loan)) {

            $loan->update([
                'progress' => 4,
                'closedDate'=>Carbon::now()->getTimestamp()
            ]);

            return Redirect::route('loan.admin.show',['code'=>$loan->code]);

        }else
            return Redirect::route('dashboard.admin')->with('bannerMessage','Invalid Loan');
    }

    public function default(Request $request,$code){

        $loan=Loan::where('code',$code)->first();

        if (is_object($loan)) {

            $loan->update([
                'progress' => 5,
                'closedDate'=>Carbon::now()->getTimestamp()
            ]);

            return Redirect::route('loan.admin.show',['code'=>$loan->code]);

        }else
            return Redirect::route('dashboard.admin')->with('bannerMessage','Invalid Loan');
    }

    public function makePayment(Request $request,$code){

        $loan=Loan::where('code',$code)->first();

        if (is_object($loan)) {

            $paymentsMade=$loan->paymentsMade;
            if ($loan->schedule != null){

                $schedule=json_decode($loan->schedule);
                $schedule[$paymentsMade]->paid=true;

                $loan->update([
                    'paymentsMade' => $paymentsMade + 1,
                    'schedule'     => json_encode($schedule)
                ]);

                return Redirect::route('loan.admin.show',['code'=>$loan->code]);
            }
            else
                return Redirect::back()->with('bannerMessage','Loan schedule not available');

        }else
            return Redirect::route('dashboard.admin')->with('bannerMessage','Invalid Loan');
    }

    public function uploadFile(Request $request)
    {

        $file=$request->file;
        $type=$request->type;

        //upload new picture and update database
        $explodedFile=explode(',',$file);
        //$decodedFile=base64_decode($explodedFile[1]);


        //develop name
        $ext=$this->getExtension($explodedFile);
        $filename="files/".$type."-".uniqid().".".$ext;

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

    public function exportDatasheetPage(){
        return Inertia::render('Admin/ExportDatasheet');
    }

    public function exportDatasheet($datestamp)
    {
        $date=Carbon::create($datestamp)->toDateTimeString();


        $loans=Loan::where('created_at','>=',$date)->where('progress','>',0)->get();

        $loansData=[];

        $index=1;



        foreach ($loans as $loan){
            $status='';
            $dueDate=$loan->dueDate!=null?date('jS F, Y',$loan->dueDate):'N/A';
            $approvedDate=$loan->approvedDate!=null?date('jS F, Y',$loan->approvedDate):'N/A';
            $approvedTime=$loan->approvedDate!=null?Carbon::createFromTimestamp($loan->approvedDate,'Africa/Lusaka')->format('h:i A'):'N/A';
            $now=Carbon::now()->getTimestamp();


            if($loan->progress==1){
                $status='Guarantor approval pending';
            }elseif($loan->progress==2){
                $status='Employer approval pending';
            }elseif($loan->progress==3 && $now<$loan->dueDate)
                $status='Active';
            elseif($loan->progress==3 && $now>$loan->dueDate)
                $status='Due';
            elseif($loan->progress==4)
                $status='Closed';
            elseif($loan->progress==5)
                $status='Defaulted';
            elseif($loan->progress==7)
                $status='Rejected';

            $role=Role::where('name','admin')->first();
            $user=$role->users()->first();

            $loansData[] = [
                'index' => $index,
                'firstName' => $loan->firstName,
                'middleName' => $loan->middleName,
                'lastName' => $loan->lastName,
                'phoneNumberMobile' => '+265 ' . $loan->phoneNumberMobile,
                'phoneNumberWork' => '+265 ' . $loan->phoneNumberWork,
                'position' => $loan->position,
                'email' => $loan->email,
                'nationalIdNumber' => $loan->nationalId,
                'loanAmount' => $loan->amount,
                'amountDue' => ($loan->amount) * (1+$user->interest) + $user->bankCharge,
                'approvedTime' => $approvedTime,
                'approvedDate' => $approvedDate,
                'dueDate' => $dueDate,
                'Status' => $status,
                'employer' => $loan->user->employer->name
            ];
            $index++;
        }

        return Excel::download(new LoanExport($loansData),'loans.xlsx');
    }

    public function exportPDF($code)
    {
        $loan=Loan::where('code',$code)->first();

        if (is_object($loan)){

            $content="<style>
                        div{margin-bottom: 8px}
                        </style>
                        $loan->termsAndConditions";

            $pdf=App::make('dompdf.wrapper');
            $pdf->loadHTML($content);
            return $pdf->stream('Loan Agreement - '.$loan->firstName ." ".$loan->lastName);

        }else{
            return Redirect::route('dashboard.admin')->with('bannerMessage','Invalid Loan');
        }
    }



}
