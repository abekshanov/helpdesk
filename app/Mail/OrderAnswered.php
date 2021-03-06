<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderAnswered extends Mailable
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
        if ($this->order->file_link) {
            return $this->view('mail.order-answered')
                ->subject('Ответ на заявку')
                ->attach($this->order->file_link);
        }

        return $this->view('mail.order-answered')
            ->subject('Ответ на заявку');
    }
}
