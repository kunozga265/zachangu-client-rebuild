<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployerPendingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $proxyName;
    public $employeeName;
    public $loanAmount;
    public $dueDate;

    /**
     * Create a new message instance.
     * string $proxyName
     *
     * @return void
     */
    public function __construct(Loan $loan, $proxyName)
    {
        $this->proxyName=$proxyName;
        $this->employeeName=$loan->firstName." ".$loan->lastName;
        $this->loanAmount=$loan->amount;
        $this->dueDate=date('jS F, Y',$loan->dueDate);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.employerPending')->subject('Loan Approval Pending');
    }
}
