<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Store;

class NewOutletRegistered extends Mailable
{
    use Queueable, SerializesModels;
    protected $outlet;
    protected $store;
    protected $admin;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($outlet,$store,$admin)
    {
        $this->outlet = $outlet;
        $this->store = $store;
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.newOutletNotification',[
            'outlet' => $this->outlet, 
            'store' => $this->store , 
            'admin' => $this->admin
        ]);
    }
}
