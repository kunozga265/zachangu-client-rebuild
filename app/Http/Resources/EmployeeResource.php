<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    =>$this->id,
            'photo'                 =>$this->photo,
            'firstName'             =>$this->firstName,
            'middleName'            =>$this->middleName,
            'lastName'              =>$this->lastName,
            'phoneNumberMobile'     =>$this->phoneNumberMobile,
            'phoneNumberWork'       =>$this->phoneNumberWork,
            'position'              =>$this->position,
            'email'                 =>$this->email,
            'physicalAddress'       =>json_decode($this->physicalAddress),
            'workAddress'           =>json_decode($this->workAddress),
            'nationalId'            =>$this->nationalId,
            'bankName'              =>$this->bankName,
            'bankAccountName'       =>$this->bankAccountName,
            'bankAccountNumber'     =>$this->bankAccountNumber,
            'contractDuration'      =>date('d M Y',$this->contractDuration),
        ];
    }
}
