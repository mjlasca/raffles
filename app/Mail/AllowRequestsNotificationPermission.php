<?php

namespace App\Mail;

use App\Models\DeliveryPermission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AllowRequestsNotificationPermission extends Mailable
{
    use Queueable, SerializesModels;

    protected $deliveryPermission;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DeliveryPermission $deliveryPermission)
    {
        $this->deliveryPermission = $deliveryPermission;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.allow-requests-permission')->with([
            'user' => $this->deliveryPermission->uallow->name .' '.$this->deliveryPermission->uallow->lastname,
            'raffle' => $this->deliveryPermission->deliveries->raffle->name,
            'delivery' => $this->deliveryPermission->deliveries->consecutive .' - '. $this->deliveryPermission->deliveries->description,
            'url' => route('entregas.index', ['keyword' => $this->deliveryPermission->deliveries->id]),
        ]);
    }
}
