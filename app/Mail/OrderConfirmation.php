<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Order;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $orderDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderDetails)
    {
        $this->orderDetails = $orderDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $modelOrder = new Order();        
        return $this->view('email.order-confirmation')->with(['order_details' => $this->orderDetails,'modelOrder' => $modelOrder]);
    }
}
