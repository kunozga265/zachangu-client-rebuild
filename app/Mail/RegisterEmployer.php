<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterEmployer extends Mailable
{
    use Queueable, SerializesModels;

    public $employer;
    public $employeeName;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employer,$employeeName)
    {
        $this->employer=$employer;
        $this->employeeName=$employeeName;
        $this->subject="New Employer[$employer->name] Alert!";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.registerEmployer')->subject($this->subject);
    }
}
