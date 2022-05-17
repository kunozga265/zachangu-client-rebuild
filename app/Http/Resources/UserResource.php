<?php

namespace App\Http\Resources;

use App\Models\Loan;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $loans=$this->loans()->where('progress','>',1)->get();

        return [
            'id'                                => $this->id,
            'firstName'                         => $this->firstName,
            'middleName'                        => $this->middleName,
            'lastName'                          => $this->lastName,
            'nationalId'                        => $this->nationalId,
            'email'                             => $this->email,
            'address'                           => $this->address,
            'loansCount'                        => $loans->count(),
        ];
    }
}
