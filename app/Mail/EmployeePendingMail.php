<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeePendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employeeName;
    public $proxyName;
    public $employerName;
    public $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Employee $employee, $loanCode)
    {
        $this->employeeName=$employee->firstName." ".$employee->lastName;
        $this->proxyName=$employee->employer->proxyName;
        $this->employerName=$employee->employer->name;
        $this->subject="Loan[$loanCode] Approval Pending";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.employeePending')->subject($this->subject);
    }
}
