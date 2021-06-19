<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomInvoice extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoice = null;
    protected $type = null;
    protected $user = null;
    protected $admin = null;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice,$type,$user = null,$admin = null)
    {
        $this->invoice = $invoice;
        $this->type = $type;
        $this->user = $user;
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.custom_invoice', [
            'invoice'=> $this->invoice,
            'receiver_name'=> $this->invoice->receiver_name,
            'store_name'=> $this->invoice->store->name,
            'type'=> $this->type,
            'user'=> $this->user,
            'admin'=> $this->admin,
        ]);
    }
}
