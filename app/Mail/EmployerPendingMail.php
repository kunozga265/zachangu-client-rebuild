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
    public $subject;
    public $loanSummary;

    /**
     * Create a new message instance.
     * string $proxyName
     *
     * @return void
     */
    public function __construct(Loan $loan, $proxyName,$loanSummary)
    {
        $this->proxyName=$proxyName;
        $this->employeeName=$loan->firstName." ".$loan->lastName;
        $this->loanAmount=$loan->amount;
        $this->dueDate=date('jS F, Y',$loan->dueDate);
        $this->subject="Loan[$loan->code] Approval Pending";
        $this->loanSummary=$loanSummary;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.employerPending')->subject($this->subject);
    }
}
