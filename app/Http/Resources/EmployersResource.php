<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployersResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'email'             => $this->email,
            'address'           => $this->physicalAddressName. " P. O. Box " .$this->physicalAddressBox . ", " . $this->physicalAddressLocation,
            'letter'            => $this->letter,
            'employeesCount'    => $this->employees->count()
        ];
    }
}
