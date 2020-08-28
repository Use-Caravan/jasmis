<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeliveryboyForgotPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $model;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {   
        $this->mailData = $mailData;       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this            
            ->view('email.deliveryboyforgotpasswordmail')
            ->with([
            'userName' => $this->mailData['deliveryboy_name'],
            'email' => $this->mailData['deliveryboy_email'],
            'reset_password' => $this->mailData['reset_password']
        ]);
    }
}
