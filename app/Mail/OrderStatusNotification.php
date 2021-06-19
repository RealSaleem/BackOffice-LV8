<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Store;
use Auth;

class OrderStatusNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$status)
    {
        $this->user = $user;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $store = Store::find(Auth::user()->store_id);

        $subject = sprintf('Order status has been updated');

        return $this->subject($subject)->view('email.orderStatusNotification',[
            'user' => $this->user, 
            'status' => $this->status,
            'store' => $store
        ]);
    }
}
