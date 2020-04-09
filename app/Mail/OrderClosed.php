<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderClosed extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * OrderCreated constructor.
     * @param $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.order-closed')
            ->subject('Заявка закрыта');
    }
}
