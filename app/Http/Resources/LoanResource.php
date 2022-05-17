<?php

namespace App\Http\Resources;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status=0;
        $now=Carbon::now()->getTimestamp();

        if($this->progress==3 && $now>$this->dueDate)
            $this->progress=6;

        $employee=Employee::where('nationalId',$this->nationalId)->first();

        //decoding physical address
        $employeePhysicalAddress=json_decode($employee->physicalAddress);
        $loanPhysicalAddress=json_decode($this->physicalAddress);

        //calculating score
        similar_text($employeePhysicalAddress->name,$loanPhysicalAddress->name,$physicalAddressName);
        similar_text($employeePhysicalAddress->box,$loanPhysicalAddress->box,$physicalAddressBox);
        similar_text($employeePhysicalAddress->location,$loanPhysicalAddress->location,$physicalAddressLocation);

        //decoding work address
        $employeeWorkAddress=json_decode($employee->workAddress);
        $loanWorkAddress=json_decode($this->workAddress);

        //calculating score
        //similar_text($employeeWorkAddress->name,$loanWorkAddress->name,$workAddressName);
        similar_text($employeeWorkAddress->box,$loanWorkAddress->box,$workAddressBox);
        similar_text($employeeWorkAddress->location,$loanWorkAddress->location,$workAddressLocation);

        //computing dates
        $employeeContractDuration=date('jS F, Y',$employee->contractDuration);
        $loanContractDuration=date('jS F, Y',$this->contractDuration);

        similar_text(substr($employeeContractDuration,0,-6),substr($loanContractDuration,0,-6),$contractDurationDate);


        similar_text($employee->firstName,$this->firstName,$firstName);
        similar_text($employee->middleName,$this->middleName,$middleName);
        similar_text($employee->lastName,$this->lastName,$lastName);
        similar_text($employee->email,$this->email,$email);
        similar_text($employee->phoneNumberMobile,$this->phoneNumberMobile,$phoneNumberMobile);
        similar_text($employee->phoneNumberWork,$this->phoneNumberWork,$phoneNumberWork);

        similar_text($employee->position,$this->position,$position);

//        //total score
//        $score=(
//                round($firstName)+
//                round($middleName)+
//                round($lastName)+
//                round($email)+
//                round($phoneNumberMobile)+
//                round($phoneNumberWork)+
//                round(($physicalAddressName + $physicalAddressBox + $physicalAddressLocation)/3)+
//                round($position)+
//                round(($workAddressName + $workAddressBox + $workAddressLocation)/3)+
//                round($contractDurationDate))/10;

        //changing date format for coWorker contract duration
//        $coWorkers=json_decode($this->co_workers);
//        $coWorkers->coWorkerContractDuration1=date('jS F, Y', Carbon::create($coWorkers->coWorkerContractDuration1)->getTimestamp());
//        $coWorkers->coWorkerContractDuration2=date('jS F, Y', Carbon::create($coWorkers->coWorkerContractDuration2)->getTimestamp());



        $time=Carbon::createFromTimestamp($this->contractDuration);
        return [
            'id'                    =>$this->id,
            'code'                  =>$this->code,

            //compare
            'firstName'             =>[
                'form'      =>  $this->firstName,
                'employee'  =>  $employee->firstName,
                'score'     =>  round($firstName)
            ],
            'middleName'            =>[
                'form'      =>  $this->middleName,
                'employee'  =>  $employee->middleName,
                'score'     =>  round($middleName)
            ],
            'lastName'              =>[
                'form'      =>  $this->lastName,
                'employee'  =>  $employee->lastName,
                'score'     =>  round($lastName)
            ],
            'email'                 =>[
                'form'      =>  $this->email,
                'employee'  =>  $employee->email,
                'score'     =>  round($email)
            ],
            'phoneNumberMobile'     =>[
                'form'      =>  $this->phoneNumberMobile,
                'employee'  =>  $employee->phoneNumberMobile,
                'score'     =>  round($phoneNumberMobile)
            ],
            'phoneNumberWork'       =>[
                'form'      =>  $this->phoneNumberWork,
                'employee'  =>  $employee->phoneNumberWork,
                'score'     =>  round($phoneNumberWork)
            ],
            'physicalAddress'       =>[
                'form'      =>  json_decode($this->physicalAddress),
                'employee'  =>  json_decode($employee->physicalAddress),
                'score'     =>  round(($physicalAddressName + $physicalAddressBox + $physicalAddressLocation)/3)
            ],
            'position'              =>[
                'form'      =>  $this->position,
                'employee'  =>  $employee->position,
                'score'     =>  round($position)
            ],
            'workAddress'           =>[
                'form'      =>  json_decode($this->workAddress),
                'employee'  =>  json_decode($employee->workAddress),
                'score'     =>  round((/*$workAddressName +*/ $workAddressBox + $workAddressLocation)/2)
            ],
            'contractDuration'  => [
                'form'      =>  date('jS F, Y',$this->contractDuration),
                'employee'  =>  date('jS F, Y',$employee->contractDuration),
                'score'     =>  round($contractDurationDate)
            ],
            'photo'                 =>[
                'form'      =>  $this->photo,
                'employee'  =>  $employee->photo,
            ],


//            'totalScore'            =>$score,
//            'score'                 =>$this->score,
            'nationalId'      =>$this->nationalId,
            'nationalIdFile'      =>$this->nationalIdFile,

//            'contractDuration'      =>$this->contractDuration,
//            'contractDurationISO'   =>$time->toDateTimeString(),

            'contract'              =>$this->contract,
            'payDay'                =>$this->payDay,
            'paySlip'               =>$this->paySlip,
//            'gross'                 =>$this->gross,
            'net'                   =>$this->net,
//            'referenceLetter'       =>$this->reference_letter,
//            'coWorkers'            =>$coWorkers,
//            'bankStatement'         =>$this->bank_statement,
//            'bankName'              =>$this->bank_name,
//            'bankServiceCenter'     =>$this->bank_service_center,
//            'bankAccountName'       =>$this->bank_account_name,
//            'bankAccountNumber'     =>$this->bank_account_number,
//            'nationalId'            =>$this->national_id,
//            'passportId'            =>$this->passport_id,
//            'licenceId'             =>$this->licence_id,
//            'otherId'               =>$this->other_id,
//            'consent'               =>$this->consent,
            'progress'              =>$this->progress,
            'score'                 =>$this->score,
            'termsAndConditions'  =>$this->termsAndConditions,
            'termsAndConditionsGuarantor'  =>$this->termsAndConditionsGuarantor,
            'amount'                =>$this->amount,
            'dueDate'               =>$this->dueDate!=null?date('jS F, Y',$this->dueDate):null,
            'approvedDate'          =>$this->approvedDate!=null?date('jS F, Y',$this->approvedDate):null,
            'appliedDate'          =>$this->appliedDate!=null?date('jS F, Y',$this->appliedDate):null,
            'closedDate'          =>$this->closedDate!=null?date('jS F, Y',$this->closedDate):null,
            'updatedAt'             =>date('jS F, Y',$this->updated_at->getTimestamp()),
//            'subscription'          =>$this->subscription,
//            'status'                =>$status,
//            'userId'                =>$this->user->id,
            'employeeId'            =>$employee->id,
        ];
    }
}
