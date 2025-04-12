<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('You Have a New Order')
            ->view('emails.new_order_notification')
            ->with([
                'jobTitle' => $this->order->jobApplication->jobAd->title,
                'clientName' => $this->order->client->name,
            ]);
    }
}
